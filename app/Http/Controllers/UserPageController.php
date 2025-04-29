<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Hash;


class UserPageController extends Controller
{
    //index
    public function index(){
       return view("web.user.index");
    }

    public function number_store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'number' => 'required',
        ]);

        $full = $request->input('number'); // e.g., "N 600"

        if (strpos($full, ' ') === false) {
            // Handle error if no space found
            return back()->withErrors(['number' => 'တင်ငွေရိုက်ထည့်ပါ။ဥပမာ N 600']);
        }
    
        list($number, $price) = explode(' ', $full, 2);

        $number = trim($number); 
        $price = trim($price);   

        if (Auth::user()->max_limit < $price) {
            return redirect()->back()->with("error", "တင်ငွေသည်သက်မှတ်ထားသော တကွက်အများဆုံးခွင့်ပြုငွေထက်ကျော်လွန်နေပါသည်။");
        }

        // Split letters and digits
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

        // Split digits into two-digit groups
        $digitGroups = [];
        for ($i = 0; $i < strlen($digits) - 1; $i += 2) {
            $group = substr($digits, $i, 2);
            $digitGroups[] = $group;
        }

        // Predefined Rules
        $rules = [
            'A' => ["00", "11", "22", "33", "44", "55", "66", "77", "88", "99"],
            'X' => ["01", "09", "10", "12", "21", "23", "32", "34", "43", "45", "54", "56", "65", "67", "76", "78", "87", "89", "90", "98"],
            'N' => ["07", "18", "24", "35", "42", "53", "69", "70", "81", "96"],
            'W' => ["05", "16", "27", "38", "49", "50", "61", "72", "83", "94"],
        ];

        $finalResult = [];
        $isReverse = in_array('R', $letters);

        // First: handle letters
        foreach ($letters as $letter) {
            if (isset($rules[$letter])) {
                $finalResult = array_merge($finalResult, $rules[$letter]);
            }
            if ($letter == 'S') {
                for ($i = 0; $i <= 99; $i += 2) {
                    $finalResult[] = str_pad($i, 2, '0', STR_PAD_LEFT);
                }
            }
            if ($letter == 'M') {
                for ($i = 1; $i <= 99; $i += 2) {
                    $finalResult[] = str_pad($i, 2, '0', STR_PAD_LEFT);
                }
            }
        }

        // Then: handle number groups
        foreach ($digitGroups as $group) {
            $finalResult[] = $group;
            if ($isReverse) {
                $reverse = strrev($group);
                if ($reverse !== $group) {
                    $finalResult[] = $reverse;
                }
            }
        }
        // dd(count($finalResult));
        // Remove duplicates
        // $finalResult = array_unique($finalResult);
        // dd($finalResult.length());
        $totalPrice = count($finalResult) * $price;
        if (empty($finalResult)) {
            return redirect()->back()->with("error", "No valid numbers found to place the order.");
        }

        // Create Order
        $order = Order::create([
            'order_number' => $this->generateOrderNumber(),
            'user_id' => Auth::id(),
            'manager_id' => Auth::user()->manager_id,
            'manager_commission' => Auth::user()->manager->commission,
            'manager_rate' => Auth::user()->manager->rate,
            'order_type' => $number,
            'price' => $totalPrice,
            'status' => 0, // Optional default
            'user_order_status' => 0, // Optional default
            "date" =>  $request->input("date"),
            "section" =>  $request->input("section"),
        ]);

        // Create Order Details
        foreach ($finalResult as $value) {
            OrderDetail::create([
                'order_number' => $this->generateOrderNumber(),
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'manager_id' => Auth::user()->manager_id,
                'number' => $value,
                'order_type' => $number,
                'price' => $price, // you can also divide if needed
                'user_order_status' => 'pending',
            ]);
        }

        return redirect()->back()->with("success", "Order placed successfully!");
    }


    // Helper function to generate random order number
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


    
}
