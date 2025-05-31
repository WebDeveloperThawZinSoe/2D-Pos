<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Hash;
use App\Models\CloseNumber;
use App\Models\User;
use App\Models\DineHeadLimit;
use App\Models\ReDine;
use App\Models\WinNumber;
use Auth;


class OrderController extends Controller
{



        //number_store Working GPT Optomize 26 May 2025
        // public function number_store(Request $request)
        // {
        //     $request->validate([
        //         'number' => 'required',
        //     ]);
        
        //     $userId = $request->client;
        //     $full = $request->input('number');
        
        //     if (!str_contains($full, ' ')) {
        //         return back()->withErrors(['number' => 'တင်ငွေရိုက်ထည့်ပါ။ဥပမာ N 600']);
        //     }
        
        //     [$number, $price] = array_map('trim', explode(' ', $full, 2));
        
        //     $user = $userId ? User::find($userId) : null;
        
        //     if ($userId && !$user) {
        //         return redirect()->back()->with("error", "User not found.");
        //     }
        
        //     if ($user && $user->max_limit < $price) {
        //         return redirect()->back()->with("error", "တင်ငွေသည်သက်မှတ်ထားသော တကွက်အများဆုံးခွင့်ပြုငွေထက်ကျော်လွန်နေပါသည်။");
        //     }
        
        //     // Extract letters and digits
        //     $letters = [];
        //     $digits = '';
        //     foreach (str_split($number) as $char) {
        //         if (ctype_alpha($char)) {
        //             $letters[] = strtoupper($char);
        //         } elseif (ctype_digit($char)) {
        //             $digits .= $char;
        //         }
        //     }
        
        //     // Split digits into 2-digit groups
        //     $digitGroups = [];
        //     for ($i = 0; $i < strlen($digits) - 1; $i += 2) {
        //         $group = substr($digits, $i, 2);
        //         if (strlen($group) == 2) {
        //             $digitGroups[] = $group;
        //         }
        //     }
        
        //     $rules = [
        //         'A' => ["00", "11", "22", "33", "44", "55", "66", "77", "88", "99"],
        //         'X' => ["01", "09", "10", "12", "21", "23", "32", "34", "43", "45", "54", "56", "65", "67", "76", "78", "87", "89", "90", "98"],
        //         'N' => ["07", "18", "24", "35", "42", "53", "69", "70", "81", "96"],
        //         'W' => ["05", "16", "27", "38", "49", "50", "61", "72", "83", "94"],
        //     ];
        
        //     $finalResult = [];
        //     $isReverse = in_array('R', $letters);
        
        //     foreach ($letters as $letter) {
        //         if (isset($rules[$letter])) {
        //             $finalResult = array_merge($finalResult, $rules[$letter]);
        //         } elseif ($letter === 'S') {
        //             for ($i = 0; $i <= 99; $i += 2) {
        //                 $finalResult[] = str_pad($i, 2, '0', STR_PAD_LEFT);
        //             }
        //         } elseif ($letter === 'M') {
        //             for ($i = 1; $i <= 99; $i += 2) {
        //                 $finalResult[] = str_pad($i, 2, '0', STR_PAD_LEFT);
        //             }
        //         }
        //     }
        
        //     if (empty($finalResult) && !empty($digitGroups)) {
        //         foreach ($digitGroups as $group) {
        //             $finalResult[] = $group;
        //             if ($isReverse) {
        //                 $reverse = strrev($group);
        //                 if ($reverse !== $group) {
        //                     $finalResult[] = $reverse;
        //                 }
        //             }
        //         }
        //     }
        
        //     $dineId = $request->dine;
        
        //     // Step 6: Only apply block number filter if NOT both user and dine exist
        //     $blocked = [];
        //     if (!($userId && $dineId)) {
        //         $blockNumbers = CloseNumber::where("date", $request->input("date"))
        //             ->where("section", $request->input("section"))
        //             ->where("manager_id", $user->manager_id ?? null)
        //             ->first();
        
        //         if ($blockNumbers && $blockNumbers->number) {
        //             $blocked = array_map('trim', explode(',', $blockNumbers->number));
        //         }
        //     }
        
        //     // Filter and deduplicate
        //     $filteredResult = array_values(array_unique(array_filter($finalResult, fn($n) => !in_array($n, $blocked))));
        
        //     if (empty($filteredResult)) {
        //         return redirect()->back()->with("error", "No valid numbers found to place the order.");
        //     }
        
        //     $totalPrice = count($filteredResult) * $price;
        //     $commission = $user->commission ?? 0;
        //     $rate = $user->rate ?? 80;
        
        //     if ($dineId) {
        //         $dine = ReDine::find($dineId);
        //         if ($dine) {
        //             $commission = $dine->commission;
        //             $rate = $dine->rate;
        //         }
        //     }
        
        //     // Create the order
        //     $order = Order::create([
        //         'order_number' => $this->generateOrderNumber(),
        //         'user_id' => $userId,
        //         'manager_id' => $request->manager_id ?? null,
        //         'commission' => $commission,
        //         'rate' => $rate,
        //         'order_type' => $full,
        //         'price' => $totalPrice,
        //         'status' => 0,
        //         'user_order_status' => 0,
        //         'date' => $request->input("date"),
        //         'section' => $request->input("section"),
        //         'created_by' => Auth::id(),
        //         'buy_sell_type' => $request->buy_sell ?? "sell",
        //         'dine_id' => $dineId,
        //     ]);
        
        //     // Create order details
        //     foreach ($filteredResult as $value) {
        //         OrderDetail::create([
        //             'order_number' => $this->generateOrderNumber(),
        //             'order_id' => $order->id,
        //             'user_id' => $userId,
        //             'manager_id' => $request->manager_id ?? null,
        //             'number' => $value,
        //             'order_type' => $full,
        //             'price' => $price,
        //             'user_order_status' => 'pending',
        //             'date' => $request->input("date"),
        //             'section' => $request->input("section"),
        //             'buy_sell_type' => $request->buy_sell ?? "sell",
        //             'commission' => $commission,
        //             'rate' => $rate,
        //             'dine_id' => $dineId,
        //         ]);
        //     }
        
        //     return redirect()->back()->with("success", "Order placed successfully!");
        // }


    


        public function number_store(Request $request)
        {
            $request->validate([
                'number' => 'required',
            ]);

            $userId = $request->client;
            $rawInput = str_replace(' ', '', $request->input('number'));

            // Handle input: if digits only, split first 2 digits as number, rest as price
            if (ctype_digit($rawInput)) {
                if (strlen($rawInput) < 3) {
                    return back()->withErrors(['number' => 'မှန်ကန်သော နံပါတ်နှင့် ငွေပမာဏ ရိုက်ထည့်ပါ။']);
                }
                $lettersDigits = substr($rawInput, 0, 2);
                $digitsOnly = substr($rawInput, 2);
            } else {
                // Existing pattern for letters+digits
                if (!preg_match('/^([A-Za-z\d]+?)(\d{2,})$/', $rawInput, $matches)) {
                    return back()->withErrors(['number' => 'တင်ငွေရိုက်ထည့်ပါ။ ဥပမာ - N600 သို့မဟုတ် 281000']);
                }
                $lettersDigits = $matches[1] ?? '';
                $digitsOnly = $matches[2];
            }

            if (!is_numeric($digitsOnly) || intval($digitsOnly) <= 0) {
                return back()->withErrors(['number' => 'မှန်ကန်သော ငွေပမာဏ ရိုက်ထည့်ပါ။']);
            }

            $price = (int) $digitsOnly;

            $rules = [
                'A' => ["00", "11", "22", "33", "44", "55", "66", "77", "88", "99"],
                'X' => ["01", "09", "10", "12", "21", "23", "32", "34", "43", "45", "54", "56", "65", "67", "76", "78", "87", "89", "90", "98"],
                'N' => ["07", "18", "24", "35", "42", "53", "69", "70", "81", "96"],
                'W' => ["05", "16", "27", "38", "49", "50", "61", "72", "83", "94"],
            ];

            $finalResult = [];
            $isReverse = false;

            // P rule
            if (preg_match('/^(\d)[Pp]$/', $lettersDigits, $pMatches)) {
                $pDigit = $pMatches[1];
                for ($i = 0; $i <= 9; $i++) {
                    $finalResult[] = str_pad($pDigit . $i, 2, '0', STR_PAD_LEFT);
                    $finalResult[] = str_pad($i . $pDigit, 2, '0', STR_PAD_LEFT);
                }
                $finalResult = array_unique($finalResult);
            }
            // F/f rule
            elseif (preg_match('/^(\d)([Ff])$/', $lettersDigits, $fMatches)) {
                $digit = $fMatches[1];
                $letter = $fMatches[2];
                for ($i = 0; $i <= 9; $i++) {
                    if ($letter === 'F') {
                        $finalResult[] = str_pad($digit . $i, 2, '0', STR_PAD_LEFT);
                    } else {
                        $finalResult[] = str_pad($i . $digit, 2, '0', STR_PAD_LEFT);
                    }
                }
            }
            // Z/z rule
            elseif (preg_match('/^(\d{2,})[Zz]$/', $lettersDigits, $zMatches)) {
                $allowedDigits = str_split($zMatches[1]);
                for ($i = 0; $i <= 9; $i++) {
                    for ($j = 0; $j <= 9; $j++) {
                        if (in_array((string)$i, $allowedDigits) && in_array((string)$j, $allowedDigits)) {
                            $finalResult[] = "{$i}{$j}";
                        }
                    }
                }
            }
            // B/b rule
            elseif (preg_match('/^(\d)[Bb]$/', $lettersDigits, $bMatches)) {
                $targetSum = intval($bMatches[1]);
                for ($i = 0; $i <= 9; $i++) {
                    for ($j = 0; $j <= 9; $j++) {
                        if ($i + $j === $targetSum) {
                            $finalResult[] = "{$i}{$j}";
                        }
                    }
                }
            }
            else {
                $letters = str_split($lettersDigits);
                foreach ($letters as $letter) {
                    $upper = strtoupper($letter);
                    if ($upper === 'R') {
                        $isReverse = true;
                        continue;
                    }
                    if (isset($rules[$upper])) {
                        $finalResult = array_merge($finalResult, $rules[$upper]);
                    } elseif ($upper === 'S') {
                        for ($i = 0; $i <= 99; $i += 2) {
                            $finalResult[] = str_pad($i, 2, '0', STR_PAD_LEFT);
                        }
                    } elseif ($upper === 'M') {
                        for ($i = 1; $i <= 99; $i += 2) {
                            $finalResult[] = str_pad($i, 2, '0', STR_PAD_LEFT);
                        }
                    }
                }

                if (empty($finalResult)) {
                    if (preg_match('/^(\d{2})R$/i', $lettersDigits, $match)) {
                        $number = $match[1];
                        $rev = strrev($number);
                        $finalResult[] = $number;
                        if ($rev !== $number) {
                            $finalResult[] = $rev;
                        }
                    } elseif (strlen($lettersDigits) >= 2 && is_numeric($lettersDigits)) {
                        $number = substr($lettersDigits, 0, 2);
                        $finalResult[] = $number;
                        if ($isReverse) {
                            $rev = strrev($number);
                            if ($rev !== $number) {
                                $finalResult[] = $rev;
                            }
                        }
                    }
                }
            }

            $user = $userId ? User::find($userId) : null;

            if ($userId && !$user) {
                return redirect()->back()->with("error", "User not found.");
            }

            if ($user && $user->max_limit < $price) {
                return redirect()->back()->with("error", "တင်ငွေသည်သက်မှတ်ထားသော တကွက်အများဆုံးခွင့်ပြုငွေထက်ကျော်လွန်နေပါသည်။");
            }

            $dineId = $request->dine;
            $blocked = [];

            if (!($userId && $dineId)) {
                $blockNumbers = CloseNumber::where("date", $request->input("date"))
                    ->where("section", $request->input("section"))
                    ->where("manager_id", $user->manager_id ?? null)
                    ->first();

                if ($blockNumbers && $blockNumbers->number) {
                    $blocked = array_map('trim', explode(',', $blockNumbers->number));
                }
            }

            $filteredResult = array_values(array_unique(array_filter($finalResult, fn($n) => !in_array($n, $blocked))));

            if (empty($filteredResult)) {
                return redirect()->back()->with("error", "No valid numbers found to place the order.");
            }

            $totalPrice = count($filteredResult) * $price;
            $commission = $user->commission ?? 0;
            $rate = $user->rate ?? 80;

            if ($dineId) {
                $dine = ReDine::find($dineId);
                if ($dine) {
                    $commission = $dine->commission;
                    $rate = $dine->rate;
                }
            }

            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => $userId,
                'manager_id' => $request->manager_id ?? null,
                'commission' => $commission,
                'rate' => $rate,
                'order_type' => $rawInput,
                'price' => $totalPrice,
                'status' => 0,
                'user_order_status' => 0,
                'date' => $request->input("date"),
                'section' => $request->input("section"),
                'created_by' => Auth::id(),
                'buy_sell_type' => $request->buy_sell ?? "sell",
                'dine_id' => $dineId,
            ]);

            foreach ($filteredResult as $value) {
                OrderDetail::create([
                    'order_number' => $this->generateOrderNumber(),
                    'order_id' => $order->id,
                    'user_id' => $userId,
                    'manager_id' => $request->manager_id ?? null,
                    'number' => $value,
                    'order_type' => $rawInput,
                    'price' => $price,
                    'user_order_status' => 'pending',
                    'date' => $request->input("date"),
                    'section' => $request->input("section"),
                    'buy_sell_type' => $request->buy_sell ?? "sell",
                    'commission' => $commission,
                    'rate' => $rate,
                    'dine_id' => $dineId,
                ]);
            }
            return redirect()->back();
            // return redirect()->back()->with("success", "Order placed successfully!");
        }




        


        //number_store_multi GPT Optomize Code 8 May 2025
        // public function number_store_multi(Request $request)
        // {
        //     $clientId = $request->client;
        //     $numbers = $request->numbers;
        //     $price = $request->amount;
        //     $date = $request->date;
        //     $section = $request->section;
        //     $managerId = $request->manager_id;
        //     $buySellType = $request->buy_sell ?? 'sell';
        //     $dineId = $request->dine;
        
        //     if (!$date || !$section || $date === "Not set" || $section === "Not set") {
        //         return redirect()->back()->with("error", "Date Section မရွေးထားပါ။");
        //     }
        
        //     $user = $clientId ? User::find($clientId) : null;
        
        //     if ($clientId && !$user) {
        //         return redirect()->back()->with("error", "သုံးစွဲသူမတွေ့ပါ။");
        //     }
        
        //     if ($user && $price > $user->max_limit) {
        //         return redirect()->back()->with("error", "သက်မှတ်ထားသော အများဆုံးထိုးငွေပမာဏထက်ကျော်လွန်နေသည်။");
        //     }
        
        //     // Step 1: Clean & extract valid 2-digit numbers
        //     $numberArray = explode('-', $numbers);
        //     $validNumbers = array_values(array_unique(array_filter($numberArray, function ($num) {
        //         return strlen(trim($num)) == 2 && is_numeric($num);
        //     })));
        
        //     if (empty($validNumbers)) {
        //         return redirect()->back()->with("error", "မှန်ကန်သော ၂လုံးဂဏန်း မရှိပါ။");
        //     }
        
        //     $finalNumbers = $validNumbers;
        
        //     // Step 2: Blocked numbers logic (only if clientId  or dineId Not is present)
        //     if ($clientId || !$dineId) {
        //         $closeNumber = CloseNumber::where("date", $date)
        //             ->where("section", $section)
        //             ->where("manager_id", $managerId)
        //             ->first();
        
        //         $blockedNumbers = [];
        //         if ($closeNumber && !empty($closeNumber->number)) {
        //             $delimiter = str_contains($closeNumber->number, '-') ? '-' : ',';
        //             $blockedNumbers = array_map('trim', explode($delimiter, $closeNumber->number));
        //         }
        
        //         $finalNumbers = array_filter($validNumbers, fn($num) => !in_array($num, $blockedNumbers));
        
        //         if (empty($finalNumbers)) {
        //             return redirect()->back()->with("error", "ပိတ်သီးများကြောင့် တင်သွင်းနိုင်သော ဂဏန်းမရှိပါ။");
        //         }
        //     }
        
        //     // Step 3: Metadata preparation
        //     $numberType = implode('-', $validNumbers) . "_" . $price;
        //     $totalPrice = $price * count($finalNumbers);
        //     $commission = $user->commission ?? 0;
        //     $rate = $user->rate ?? 80;
        
        //     if ($dineId) {
        //         $dine = ReDine::find($dineId);
        //         if ($dine) {
        //             $commission = $dine->commission;
        //             $rate = $dine->rate;
        //         }
        //     }
        
        //     // Step 4: Create main order
        //     $order = Order::create([
        //         'order_number' => $this->generateOrderNumber(),
        //         'user_id' => $clientId,
        //         'manager_id' => $managerId,
        //         'commission' => $commission,
        //         'rate' => $rate,
        //         'order_type' => $numberType,
        //         'price' => $totalPrice,
        //         'status' => 0,
        //         'user_order_status' => 0,
        //         'date' => $date,
        //         'section' => $section,
        //         'created_by' => Auth::id(),
        //         'buy_sell_type' => $buySellType,
        //         'dine_id' => $dineId,
        //     ]);
        
        //     // Step 5: Create order details
        //     foreach ($finalNumbers as $number) {
        //         OrderDetail::create([
        //             'order_number' => $this->generateOrderNumber(),
        //             'order_id' => $order->id,
        //             'user_id' => $clientId,
        //             'manager_id' => $managerId,
        //             'number' => $number,
        //             'order_type' => $numberType,
        //             'price' => $price,
        //             'user_order_status' => 'pending',
        //             'date' => $date,
        //             'section' => $section,
        //             'buy_sell_type' => $buySellType,
        //             'commission' => $commission,
        //             'rate' => $rate,
        //             'dine_id' => $dineId,
        //         ]);
        //     }
        //   return redirect()->back();
        //     // return redirect()->back()->with("success", "အော်ဒါတင်ခြင်း အောင်မြင်ပါသည်။");
        // }
        public function number_store_multi(Request $request)
        {
            $clientId = $request->client;
            $numbers = $request->numbers;
            $rawAmount = $request->amount;
            $date = $request->date;
            $section = $request->section;
            $managerId = $request->manager_id;
            $buySellType = $request->buy_sell ?? 'sell';
            $dineId = $request->dine;

            if (!$date || !$section || $date === "Not set" || $section === "Not set") {
                return redirect()->back()->with("error", "Date Section မရွေးထားပါ။");
            }

            $user = $clientId ? User::find($clientId) : null;

            if ($clientId && !$user) {
                return redirect()->back()->with("error", "သုံးစွဲသူမတွေ့ပါ။");
            }

            // Parse price and reverse logic
            $price = 0;
            $reversePrice = null;
            $hasReverse = false;

            if (preg_match('/^(\d+)[Rr](\d+)?$/', $rawAmount, $matches)) {
                $price = (int) $matches[1];
                $reversePrice = isset($matches[2]) ? (int) $matches[2] : $price;
                $hasReverse = true;
            } elseif (is_numeric($rawAmount)) {
                $price = (int) $rawAmount;
            } else {
                return redirect()->back()->with("error", "ငွေပမာဏ မှားယွင်းနေပါသည်။");
            }

            if ($user && $price > $user->max_limit) {
                return redirect()->back()->with("error", "သက်မှတ်ထားသော အများဆုံးထိုးငွေပမာဏထက်ကျော်လွန်နေသည်။");
            }

            // Step 1: Clean & extract valid 2-digit numbers
            $numberArray = explode('-', $numbers);
            $validNumbers = array_values(array_filter(array_unique(array_map('trim', $numberArray)), function ($num) {
                return strlen($num) == 2 && is_numeric($num);
            }));

            if (empty($validNumbers)) {
                return redirect()->back()->with("error", "မှန်ကန်သော ၂လုံးဂဏန်း မရှိပါ။");
            }

            // Step 2: Blocked number check
            $blockedNumbers = [];
            if ($clientId || !$dineId) {
                $closeNumber = CloseNumber::where("date", $date)
                    ->where("section", $section)
                    ->where("manager_id", $managerId)
                    ->first();

                if ($closeNumber && !empty($closeNumber->number)) {
                    $delimiter = str_contains($closeNumber->number, '-') ? '-' : ',';
                    $blockedNumbers = array_map('trim', explode($delimiter, $closeNumber->number));
                }
            }

            // Filter valid numbers
            $finalNumbers = array_filter($validNumbers, fn($num) => !in_array($num, $blockedNumbers));

            if (empty($finalNumbers)) {
                return redirect()->back()->with("error", "ပိတ်သီးများကြောင့် တင်သွင်းနိုင်သော ဂဏန်းမရှိပါ။");
            }

            // Step 3: Reverse numbers if needed
            $reverseNumbers = [];
            if ($hasReverse) {
                foreach ($finalNumbers as $num) {
                    $rev = strrev($num);
                    if ($rev !== $num) {
                        $reverseNumbers[] = $rev;
                    }
                }

                // Remove duplicates and check for blocks
                $reverseNumbers = array_filter(array_unique($reverseNumbers), fn($num) => !in_array($num, $blockedNumbers));
            }

            // Combine both sets
            $combinedNumbers = array_values(array_unique(array_merge($finalNumbers, $reverseNumbers)));

            if (empty($combinedNumbers)) {
                return redirect()->back()->with("error", "တင်သွင်းနိုင်သော ဂဏန်းမရှိပါ။");
            }

            // Step 4: Setup commission/rate
            $commission = $user->commission ?? 0;
            $rate = $user->rate ?? 80;

            if ($dineId) {
                $dine = ReDine::find($dineId);
                if ($dine) {
                    $commission = $dine->commission;
                    $rate = $dine->rate;
                }
            }

            // Step 5: Metadata & Preview Output
            $numberType = implode('-', $validNumbers) . "_" . $rawAmount;
            $totalPrice = ($price * count($finalNumbers)) + ($hasReverse ? $reversePrice * count($reverseNumbers) : 0);

            // ✅ Show parsed result for debugging
            // dd([
            //     'Input Amount' => $rawAmount,
            //     'Price' => $price,
            //     'Reverse Price' => $reversePrice,
            //     'Has Reverse' => $hasReverse,
            //     'Valid Numbers' => $validNumbers,
            //     'Reverse Numbers' => $reverseNumbers,
            //     'Final Combined Numbers' => $combinedNumbers,
            //     'Blocked Numbers' => $blockedNumbers,
            //     'Total Price' => $totalPrice,
            //     'User Max Limit' => $user->max_limit ?? null,
            // ]);

            // Step 6: Create main order (remove dd() to continue)
            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => $clientId,
                'manager_id' => $managerId,
                'commission' => $commission,
                'rate' => $rate,
                'order_type' => $numberType,
                'price' => $totalPrice,
                'status' => 0,
                'user_order_status' => 0,
                'date' => $date,
                'section' => $section,
                'created_by' => Auth::id(),
                'buy_sell_type' => $buySellType,
                'dine_id' => $dineId,
            ]);

            // Step 7: Create order details
            foreach ($combinedNumbers as $num) {
                $isReverse = in_array($num, $reverseNumbers);
                OrderDetail::create([
                    'order_number' => $this->generateOrderNumber(),
                    'order_id' => $order->id,
                    'user_id' => $clientId,
                    'manager_id' => $managerId,
                    'number' => $num,
                    'order_type' => $numberType,
                    'price' => $isReverse ? $reversePrice : $price,
                    'user_order_status' => 'pending',
                    'date' => $date,
                    'section' => $section,
                    'buy_sell_type' => $buySellType,
                    'commission' => $commission,
                    'rate' => $rate,
                    'dine_id' => $dineId,
                ]);
            }

            // return redirect()->back()->with("success", "အော်ဒါတင်ခြင်း အောင်မြင်ပါသည်။");
            return redirect()->back();
        }



    
        //order_cofirm_all
        public function order_cofirm_all(Request $request)
        {
            $date = $request->date;
            $section = $request->section;
            $user_id = Auth::id();
            $buy_sell = $request->buy_sell;
        
            // Get matching orders
            $orders = Order::where("date", $date)
                ->where("section", $section)
                ->where("created_by", $user_id)
                ->where("manager_id", $user_id)
                ->where("status", 0)
                ->where("buy_sell_type",$buy_sell)
                ->get();
        
            // Update orders
            foreach ($orders as $order) {
                $order->update(['status' => 1, 'user_order_status' => 1,]);
        
                // Update related OrderDetails
                OrderDetail::where("order_id", $order->id)->update([
                    "user_order_status" => 1
                ]);
            }
          return redirect()->back();
            // return redirect()->back()->with("success", "Order(s) confirmed successfully!");
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
                "user_order_status" => 1,
                "status" => 1
            ]);

            OrderDetail::where("order_id",$id)->update([
                "user_order_status" => 1
            ]);
             return redirect()->back();
            //return redirect()->back()->with("success", "Order Confirm successfully!");

        }

        //order_delete
        public function order_delete(Request $request){
            $id = $request->id;
            Order::where("id",$id)->delete();

            OrderDetail::where("order_id",$id)->delete();
            return redirect()->back()->with("success", "Order Cancel successfully!");
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
            
            CloseNumber::where("manager_id", $user_id)
                ->where("date", $date)
                ->where("section", $section)
                ->delete();

            DineHeadLimit::where("manager_id", $user_id)
                ->where("date", $date)
                ->where("section", $section)
                ->delete();

            
            WinNumber::where("manager_id", $user_id)
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

        //win_store
        public function win_store(Request $request){
             $validated = $request->validate([
                'manager_id' => 'required|integer',
                'section' => 'required|string',
                'date' => 'required|date',
                
            ]);
        
            $existing = WinNumber::where('manager_id', $validated['manager_id'])
                ->where('section', $validated['section'])
                ->where('date', $validated['date'])
                ->first();
            
     
            
        
           if ($request->number == "" ) {
                if ($existing) {
                    $existing->delete();
                     OrderDetail::where('manager_id', $validated['manager_id'])
                    ->where('section', $validated['section'])
                    ->where('date', $validated['date'])->update([
                        "win_lose" => 0
                    ]);
                    return back()->with('success', 'ပေါက်သီး ဖြတ်ပြီးပါပြီ။');
                } else {
                    OrderDetail::where('manager_id', $validated['manager_id'])
                    ->where('section', $validated['section'])
                    ->where('date', $validated['date'])->update([
                        "win_lose" => 0
                    ]);
                    return back()->with('error', 'ပေါက်သီး မရှိသေးပါ။ ဖြတ်ရန်အချက်အလက် မတွေ့ပါ။');
                }
            }else{
                OrderDetail::where('manager_id', $validated['manager_id'])
                ->where('section', $validated['section'])
                ->where('date', $validated['date'])->where("number",$request->number)->update([
                    "win_lose" => 1
                ]);
            }

            if ($existing) {
                $existing->update([
                    'number' => $request->number,
                ]);
            } else {
                WinNumber::create([
                    'manager_id' => $validated['manager_id'],
                    'section' => $validated['section'],
                    'date' => $validated['date'],
                    'number' => $request->number,
                ]);
            }
        
            return back()->with('success', 'ပေါက်သီး သက်မှတ်ပြီးပါပြီ။');
        }


        //rebuy
        public function rebuy(){
            $date = session('selected_date');
            $section = session('selected_section');
            $buyOrders = Order::where("manager_id",Auth::user()->id)->where("status",1)->where("date",$date)->where("section",$section)->where("buy_sell_type","buy")->get();
            $sellOrders = Order::where("manager_id",Auth::user()->id)->where("status",1)->where("date",$date)->where("section",$section)->where("buy_sell_type","sell")->get();
            return view("web.dine.rebuy",compact("buyOrders","sellOrders"));
        }


        //rebuy_store
        public function rebuy_store(Request $request)
        {
            $clientId = $request->dine; // dine is used as the rebuy client
            $numbers = $request->numbers ?? [];
            $rebuyPercent = $request->rebuy_percent ?? 100;
            $date = $request->date;
            $section = $request->section;
            $managerId = $request->manager_id;
            $buySellType = $request->buy_sell ?? 'buy';
        
            if (!$date || !$section || $date === "Not set" || $section === "Not set") {
                return redirect()->back()->with("error", "Date Section မရွေးထားပါ။");
            }
        
            $user = ReDine::find($clientId);
            $commission = $user->commission ?? 0;
            $rate = $user->rate ?? 80;
        
            $rebuyNumberTexts = [];
            $totalRebuyPrice = 0;
        
            // Step 1: Create main Order
            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => $clientId,
                'manager_id' => $managerId,
                'commission' => $commission,
                'rate' => $rate,
                'order_type' => 'rebuy_auto',
                'price' => 0, // temp, updated below
                'status' => 0,
                'user_order_status' => 0,
                'date' => $date,
                'section' => $section,
                'created_by' => Auth::id(),
                'buy_sell_type' => $buySellType,
                'dine_id' => $clientId,
            ]);
        
            // Step 2: Create OrderDetails for each number
            foreach ($numbers as $num => $detail) {
                $originalAmount = abs($detail['amount']); // ensure positive
                $rebuyAmount = round($originalAmount * ($rebuyPercent / 100));
        
                if ($rebuyAmount <= 0) continue;
        
                OrderDetail::create([
                    'order_number' => $this->generateOrderNumber(),
                    'order_id' => $order->id,
                    'user_id' => $clientId,
                    'manager_id' => $managerId,
                    'number' => $num,
                    'order_type' => "rebuy_{$rebuyAmount}",
                    'price' => $rebuyAmount,
                    'user_order_status' => 'pending',
                    'date' => $date,
                    'section' => $section,
                    'buy_sell_type' => $buySellType,
                    'commission' => $commission,
                    'rate' => $rate,
                    'dine_id' => $clientId,
                ]);
        
                $totalRebuyPrice += $rebuyAmount;
                $rebuyNumberTexts[] = $num;
            }
        
            // Update total price & order type summary
            $order->update([
                'price' => $totalRebuyPrice,
                'order_type' => implode('-', $rebuyNumberTexts) . '_' . $rebuyPercent . '%',
            ]);
        
            return redirect()->back()->with('success', 'ပြန်ဝယ်မှု အောင်မြင်ပါသည်။');
        }


        //print
        public function print($id)
        {
            $order = Order::where("id", $id)->first();
            $orderType = $order->order_type;

            if (preg_match('/[a-zA-Z]/', $orderType)) {
                return view('web.dine.print', compact('orderType'));
            } else {
                $orderDetails = OrderDetail::where("order_id", $id)->get();

                $seen = [];
                $reversedOutput = [];

                foreach ($orderDetails as $detail) {
                    $num = $detail->number;
                    $price = $detail->price;
                    $rev = strrev($num);

                    if ($num === $rev) continue; // skip palindromes like 88, 99

                    if (in_array($rev, $seen)) {
                        $pair = min($num, $rev) . 'R';
                        if (!in_array($pair, $reversedOutput)) {
                            $reversedOutput[] = $pair;
                        }
                    } else {
                        $seen[] = $num;
                    }
                }

                return view('web.dine.print', compact('reversedOutput','price'));
            }
        }





        
    
}