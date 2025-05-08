<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Hash;
use App\Models\CloseNumber;
use App\Models\User;
use App\Models\DineHeadLimit;
use Auth;


class OrderController extends Controller
{

    //number_store
    public function number_store(Request $request)
    {
        $request->validate([
            'number' => 'required',
        ]);
    
        $user_id = $request->client;
    
        $full = $request->input('number'); // e.g., "N 600"
    
        if (strpos($full, ' ') === false) {
            return back()->withErrors(['number' => 'တင်ငွေရိုက်ထည့်ပါ။ဥပမာ N 600']);
        }
    
        list($number, $price) = explode(' ', $full, 2);
        $number = trim($number); 
        $price = trim($price);   
    
        // ❌ FIXED: Incorrect use of ->get() on a single find()
        $user = User::find($user_id); // Removed ->get()
    
        if (!$user) {
            return redirect()->back()->with("error", "User not found.");
        }
    
        if ($user->max_limit < $price) {
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
            ->where("manager_id", $user->manager_id) // ✅ safer to use manager_id directly
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
            'user_id' => $user->id,
            'manager_id' => $user->manager_id,
            'commission' => $user->commission ?? 0,
            'rate' => $user->rate ?? 0,
            'order_type' => $full,
            'price' => $totalPrice,
            'status' => 0,
            'user_order_status' => 0,
            "date" =>  $request->input("date"),
            "section" =>  $request->input("section"),
            "created_by" => Auth::id(),
            "buy_sell_type" => "sell"
        ]);
    
        // Step 10: Create order details
        foreach ($filteredResult as $value) {
            OrderDetail::create([
                'order_number' => $this->generateOrderNumber(),
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'manager_id' => $user->manager_id,
                'number' => $value,
                'order_type' => $full,
                'price' => $price,
                'user_order_status' => 'pending',
                "date" =>  $request->input("date"),
                "section" =>  $request->input("section"),
                "buy_sell_type" => "sell",
                'commission' => $user->commission,
                'rate' => $user->rate,
            ]);
        }
    
        return redirect()->back()->with("success", "Order placed successfully!");
    }
    
    //order_cofirm_all
    public function order_cofirm_all(Request $request)
    {
        $date = $request->date;
        $section = $request->section;
        $user_id = Auth::id();
    
        // Get matching orders
        $orders = Order::where("date", $date)
            ->where("section", $section)
            ->where("created_by", $user_id)
            ->where("manager_id", $user_id)
            ->where("status", 0)
            ->get();
    
        // Update orders
        foreach ($orders as $order) {
            $order->update(['status' => 1]);
    
            // Update related OrderDetails
            OrderDetail::where("order_id", $order->id)->update([
                "user_order_status" => 1
            ]);
        }
    
        return redirect()->back()->with("success", "Order(s) confirmed successfully!");
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


    //number_store_multi
    public function number_store_multi(Request $request)
    {
        $client = $request->client;
        $numbers = $request->numbers;
        $price = $request->amount;
        $date = $request->date;
        $section = $request->section;

        if (!$date || !$section || $date === "Not set" || $section === "Not set") {
            return redirect()->back()->with("error", "Date Section မရွေးထားပါ။");
        }

        $user = User::find($client);

        if (!$user) {
            return redirect()->back()->with("error", "သုံးစွဲသူမတွေ့ပါ။");
        }

        if ($price > $user->max_limit) {
            return redirect()->back()->with("error", "သက်မှတ်ထားသော အများဆုံးထိုးငွေပမာဏထက်ကျော်လွန်နေသည်။");
        }

        // Step 1: Clean & Extract valid 2-digit numbers
        $numberArray = explode('-', $numbers);
        $filtered = array_filter($numberArray, function ($num) {
            return strlen(trim($num)) == 2 && is_numeric($num);
        });

        // Step 2: Remove duplicates and reindex
        $validNumbers = array_values(array_unique($filtered));

        if (empty($validNumbers)) {
            return redirect()->back()->with("error", "မှန်ကန်သော ၂လုံးဂဏန်း မရှိပါ။");
        }

        // Step 3: Fetch closed (blocked) numbers
        $closeNumber = CloseNumber::where("date", $date)
            ->where("section", $section)
            ->where("manager_id", $user->manager_id)
            ->first();

        $blockedNumbers = [];

        if ($closeNumber && !empty($closeNumber->number)) {
            // Support comma-separated or dash-separated close numbers
            $delimiter = str_contains($closeNumber->number, '-') ? '-' : ',';
            $blockedNumbers = array_map('trim', explode($delimiter, $closeNumber->number));
        }

        // Step 4: Remove blocked numbers from input
        $finalNumbers = array_filter($validNumbers, function ($num) use ($blockedNumbers) {
            return !in_array($num, $blockedNumbers);
        });

        if (empty($finalNumbers)) {
            return redirect()->back()->with("error", "ပိတ်သီးများကြောင့် တင်သွင်းနိုင်သော ဂဏန်းမရှိပါ။");
        }

        // Step 5: Prepare metadata
        $number_type = implode('-', $validNumbers) . "_" . $price;
        $total_price = $price * count($finalNumbers);
        $order_number = $this->generateOrderNumber();

        // Step 6: Create the main order
        $order = Order::create([
            'order_number' =>  $this->generateOrderNumber(),
            'user_id' => $user->id,
            'manager_id' => Auth::id(),
            'commission' => $user->commission,
            'rate' => $user->rate,
            'order_type' => $number_type,
            'price' => $total_price,
            'status' => 0,
            'user_order_status' => 0,
            'date' => $date,
            'section' => $section,
            'created_by' => Auth::id(),
             "buy_sell_type" => "sell"
        ]);

        // Step 7: Create order details
        foreach ($finalNumbers as $number) {
            OrderDetail::create([
                'order_number' =>  $this->generateOrderNumber(),
                'order_id' => $order->id,
                'user_id' => $user->id,
                'manager_id' => Auth::id(),
                'number' => $number,
                'order_type' => $number_type,
                'price' => $price,
                'user_order_status' => 'pending',
                "date" =>  $request->input("date"),
                "section" =>  $request->input("section"),
                 "buy_sell_type" => "sell",
                 'commission' => $user->commission,
                'rate' => $user->rate,
            ]);
        }

        return redirect()->back()->with("success", "အော်ဒါတင်ခြင်း အောင်မြင်ပါသည်။");
    }





    //delete_all
    public function delete_all(Request $request)
    {
        $user_id = Auth::id();
        $date = $request->input("date");
        $section = $request->input("section");
    
        // Get all orders matching the criteria
        $orders = Order::where("manager_id", $user_id)
            ->where("date", $date)
            ->where("section", $section)
            ->get();
    
        // Delete all related OrderDetail records
        foreach ($orders as $order) {
            OrderDetail::where("order_id", $order->id)->delete();
        }
    
        // Now delete the orders themselves
        Order::where("manager_id", $user_id)
            ->where("date", $date)
            ->where("section", $section)
            ->delete();
    
        return redirect()->back()->with("success", "All cleared successfully!");
    }

    //close_number_store
    public function close_number_store(Request $request){
        $numbers = $request->numbers;
        $date = $request->date;
        $section = $request->section;
    
        if ($date == null || $section == null || $date == "Not set" || $section == "Not set") {
            return redirect()->back()->with("error", "Date Section မရွှေးထားပါ");
        }
    
       


        CloseNumber::create([
            'manager_id' =>  Auth::user()->id,
            'date' => $date,
            'section' => $section,
            'number' => $numbers
        ]);
    
        return redirect()->back()->with("success", "ပိတ်သီးထည့်ခြင်းအောင်မြင်ပါသည်။");

    }

    //close_number_delete
    public function close_number_delete(Request $request){
        $date = $request->date;
        $section = $request->section;
        CloseNumber::where("manager_id", Auth::user()->id)->where("date",$date)->where("section",$section)->delete();

        return redirect()->back()->with("success", "ပိတ်သီးဖြတ်ခြင်းအောင်မြင်ပါသည်။");
    }
    

    //limit_store
    public function limit_store(Request $request)
    {
        $validated = $request->validate([
            'manager_id' => 'required|integer',
            'section' => 'required|string',
            'date' => 'required|date',
            'limit' => 'required|numeric|min:0',
        ]);
    
        $existing = DineHeadLimit::where('manager_id', $validated['manager_id'])
            ->where('section', $validated['section'])
            ->where('date', $validated['date'])
            ->first();
    
        if ($existing) {
            $existing->update([
                'amount' => $validated['limit'],
            ]);
        } else {
            DineHeadLimit::create([
                'manager_id' => $validated['manager_id'],
                'section' => $validated['section'],
                'date' => $validated['date'],
                'amount' => $validated['limit'],
            ]);
        }
    
        return back()->with('success', 'ခေါင်ကျော် Limit သက်မှတ်ပြီးပါပြီ။');
    }

}
