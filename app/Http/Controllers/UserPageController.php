<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Hash;
use App\Models\CloseNumber;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;


class UserPageController extends Controller
{
    //index
    public function index(){
       return view("web.user.index");
    }

    public function number_store(Request $request)
    {
        $request->validate([
            'number' => 'required',
        ]);
    
        $full = $request->input('number'); // e.g., "N 600"
    
        if (strpos($full, ' ') === false) {
            return back()->withErrors(['number' => 'တင်ငွေရိုက်ထည့်ပါ။ဥပမာ N 600']);
        }
    
        list($number, $price) = explode(' ', $full, 2);
        $number = trim($number); 
        $price = trim($price);   
    
        if (Auth::user()->max_limit < $price) {
            return redirect()->back()->with("error", "တင်ငွေသည်သက်မှတ်ထားသော တကွက်အများဆုံးခွင့်ပြုငွေထက်ကျော်လွန်နေပါသည်။");
        }
    
        // Step 1: Extract letters and digits from input
        $letters = [];
        $digits = '';
        for ($i = 0; $i < strlen($number); $i++) {
            $char = $number[$i];
            if (ctype_alpha($char)) {
                $letters[] = strtoupper($char);
            } elseif (ctype_digit($char)) {
                $digits .= $char;
            }
        }
    
        // Step 2: Split digits into two-digit groups
        $digitGroups = [];
        for ($i = 0; $i < strlen($digits) - 1; $i += 2) {
            $group = substr($digits, $i, 2);
            if (strlen($group) == 2) {
                $digitGroups[] = $group;
            }
        }
    
        // Step 3: Predefined rules
        $rules = [
            'A' => ["00", "11", "22", "33", "44", "55", "66", "77", "88", "99"],
            'X' => ["01", "09", "10", "12", "21", "23", "32", "34", "43", "45", "54", "56", "65", "67", "76", "78", "87", "89", "90", "98"],
            'N' => ["07", "18", "24", "35", "42", "53", "69", "70", "81", "96"],
            'W' => ["05", "16", "27", "38", "49", "50", "61", "72", "83", "94"],
        ];
    
        $finalResult = [];
        $isReverse = in_array('R', $letters);
    
        // Step 4: Apply rules from letters
        foreach ($letters as $letter) {
            if (isset($rules[$letter])) {
                $finalResult = array_merge($finalResult, $rules[$letter]);
            }
    
            if ($letter === 'S') {
                for ($i = 0; $i <= 99; $i += 2) {
                    $finalResult[] = str_pad($i, 2, '0', STR_PAD_LEFT);
                }
            }
    
            if ($letter === 'M') {
                for ($i = 1; $i <= 99; $i += 2) {
                    $finalResult[] = str_pad($i, 2, '0', STR_PAD_LEFT);
                }
            }
        }
    
        // Step 5: Include raw digitGroups if no letter rule was matched
        if (empty($finalResult) && !empty($digitGroups)) {
            foreach ($digitGroups as $group) {
                $finalResult[] = $group;
    
                if ($isReverse) {
                    $reverse = strrev($group);
                    if ($reverse !== $group) {
                        $finalResult[] = $reverse;
                    }
                }
            }
        }
    
        // Step 6: Fetch block numbers
        $blockNumbers = CloseNumber::where("date", $request->input("date"))
            ->where("section", $request->input("section"))
            ->where("manager_id", Auth::user()->manager->id)
            ->first();
    
        $blockNumberArray = [];
        if ($blockNumbers && !empty($blockNumbers->number)) {
            $blockNumberArray = array_map('trim', explode(',', $blockNumbers->number));
        }
    
        // Step 7: Filter blocked numbers from finalResult
        $filteredResult = [];
        foreach ($finalResult as $num) {
            if (!in_array($num, $blockNumberArray)) {
                $filteredResult[] = $num;
            }
        }
    
        // Step 8: Ensure no duplicates
        $filteredResult = array_unique($filteredResult);
    
        if (empty($filteredResult)) {
            return redirect()->back()->with("error", "No valid numbers found to place the order.");
        }
    
        $totalPrice = count($filteredResult) * $price;
    
        // Step 9: Create the order
        $order = Order::create([
            'order_number' => $this->generateOrderNumber(),
            'user_id' => Auth::id(),
            'manager_id' => Auth::user()->manager_id,
            'manager_commission' => Auth::user()->manager->commission,
            'manager_rate' => Auth::user()->manager->rate,
            'order_type' => $number,
            'price' => $totalPrice,
            'status' => 0,
            'user_order_status' => 0,
            "date" =>  $request->input("date"),
            "section" =>  $request->input("section"),
        ]);
    
        // Step 10: Create order details
        foreach ($filteredResult as $value) {
            OrderDetail::create([
                'order_number' => $this->generateOrderNumber(),
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'manager_id' => Auth::user()->manager_id,
                'number' => $value,
                'order_type' => $number,
                'price' => $price,
                'user_order_status' => 'pending',
            ]);
        }
    
        return redirect()->back()->with("success", "Order placed successfully!");
    }
    
    // Helper method
    private function generateOrderNumber()
    {
        return 'ORD-' . strtoupper(uniqid());
    }
    

    //order_status
    public function order_status(Request $request){
        $id = $request->id;
        Order::where("id",$id)->update([
            "user_order_status" => 1
        ]);

        OrderDetail::where("order_id",$id)->update([
            "user_order_status" => 1
        ]);
        return redirect()->back()->with("success", "Order Confirm successfully!");

    }

    //order_delete
    public function order_delete(Request $request){
        $id = $request->id;
        Order::where("id",$id)->delete();

        OrderDetail::where("order_id",$id)->delete();
        return redirect()->back()->with("success", "Order Cancel successfully!");
    }

    //my_order  
    public function my_order()
    {
        $user_id = Auth::user()->id;

        // First paginate the orders
        $orders = Order::where('user_id', $user_id)
                    ->orderBy('date', 'desc')
                    ->paginate(100); // Paginate 30 orders per page

        return view('web.user.orderHistory', compact('orders'));
    }


    //changePassword
    public function changePassword(){
        return view('web.user.changePassword');
    }

    public function updatePassword(Request $request)
    {
        // Validate inputs
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if old password matches
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'အဟောင်း စကားဝှက် မှားနေပါသည်။']);
        }

        // Update new password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'စကားဝှက် ပြောင်းပြီးပါပြီ။');
    }

    //report
    public function report($type)
    {
        $user_id = Auth::user()->id;

        // Calculate date range based on $type
        $date = session('selected_date') ?? now()->toDateString();

        $startDate = $endDate = Carbon::parse($date);

        switch ($type) {
           case 'daily':
                $startDate = Carbon::parse($date)->startOfDay();
                $endDate = Carbon::parse($date)->endOfDay();
              
                break;

            case 'weekly':
                $startDate = Carbon::parse($date)->startOfWeek(Carbon::MONDAY);
                $endDate = Carbon::parse($date)->endOfWeek(Carbon::SUNDAY);
                break;

            case 'monthly':
                $startDate = Carbon::parse($date)->startOfMonth();
                $endDate = Carbon::parse($date)->endOfMonth();
                break;

            case 'yearly':
                $startDate = Carbon::parse($date)->startOfYear();
                $endDate = Carbon::parse($date)->endOfYear();
                break;

            default:
                abort(400, 'Invalid report type.');
        }

        if($type != "daily"){
          

            // ✅ Sell Orders
            $sellOrders = Order::select('user_id', DB::raw('SUM(price) as total_amount'), 'users.name as user_name')
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->where('orders.user_id', $user_id)
                ->where('orders.status', 1)
                ->whereBetween('orders.date', [$startDate, $endDate])
                ->where('orders.buy_sell_type', 'sell')
                ->groupBy('user_id', 'users.name')
                ->get();
            foreach ($sellOrders as $order) {
                $user = \App\Models\User::find($order->user_id);

                $winningDetails = OrderDetail::where('user_id', $user_id)
                    ->where('user_id', $order->user_id)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->where('buy_sell_type', 'sell')
                    ->where('win_lose', 1)
                    ->get();

                $rate = $user->rate ?? 0;
                $totalNumber = $winningDetails->sum('price');
                $dethPauk = $totalNumber * $rate;
                $order->deth_pauk = $dethPauk;
                $order->user = $user;
            }
        }else{
            

            // ✅ Sell orders grouped by user_id with user name
            $sellOrders = Order::select('user_id', DB::raw('SUM(price) as total_amount'), 'users.name as user_name')
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->where("orders.user_id", Auth::user()->id)
                ->where("orders.status", 1)
                ->where("orders.date", $date)
                ->where("orders.buy_sell_type", "sell")
                ->groupBy('user_id', 'users.name')
                ->get();

            // ✅ Calculate Deth Pauk & attach user
            foreach ($sellOrders as $order) {
                $user = \App\Models\User::find($order->user_id);

                $winningDetails = OrderDetail::where('user_id', Auth::user()->id)
                    ->where('user_id', $order->user_id)
                    ->where('date', $date)
                    ->where('buy_sell_type', 'sell')
                    ->where('win_lose', 1)
                    ->get();

                $rate = $user->rate ?? 0;
                $totalNumber = $winningDetails->sum('price');
                $dethPauk = $totalNumber * $rate;
                dd($dethPauk);
                $order->deth_pauk = $dethPauk;
                $order->user = $user;
            }
        }


        return view("web.user.report", compact( "sellOrders", "type", "startDate", "endDate"));
    }



    
}