<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

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
            'price'  => 'required|numeric',   
        ]);

        $number = $request->input('number');
        $price = $request->input('price');

        if (Auth::user()->max_limit < $price) {
            return redirect()->back()->with("error", "Your limit is exceeded");
        }

        // Split letters and digits
        $letters = [];
        $digits = '';

        for ($i = 0; $i < strlen($number); $i++) {
            $char = $number[$i];
            if (ctype_alpha($char)) {
                $letters[] = strtoupper($char); // force uppercase
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

        // First: add rules based on letters
        foreach ($letters as $letter) {
            if (isset($rules[$letter])) {
                $finalResult = array_merge($finalResult, $rules[$letter]);
            }
            
            if ($letter == 'S') {
                // Add all even numbers from 00 to 99
                for ($i = 0; $i <= 99; $i++) {
                    if ($i % 2 == 0) {
                        $finalResult[] = str_pad($i, 2, '0', STR_PAD_LEFT);
                    }
                }
            }

            if ($letter == 'M') {
                // Add all odd numbers from 00 to 99
                for ($i = 0; $i <= 99; $i++) {
                    if ($i % 2 != 0) {
                        $finalResult[] = str_pad($i, 2, '0', STR_PAD_LEFT);
                    }
                }
            }
        }

        // Then: add digit groups
        foreach ($digitGroups as $group) {
            $finalResult[] = $group;

            if ($isReverse) {
                $reverse = strrev($group);
                if ($reverse !== $group) {
                    $finalResult[] = $reverse;
                }
            }
        }

        // Remove duplicates
        $finalResult = array_unique($finalResult);

        // Show the final result
        dd($finalResult);
    }


    
}
