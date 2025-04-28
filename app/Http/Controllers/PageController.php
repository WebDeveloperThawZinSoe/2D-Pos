<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Auth;

class PageController extends Controller
{
    //index
    public function index(){
        if(Auth::check()){

            $user = Auth::user();
            if($user->status == 1){
                // dd($user->getRoleNames());
                if($user->hasRole('admin')){
                    return redirect("/admin");
                }elseif($user->hasRole('shop')){
                    $start_date = $user->join_date;
                    $end_date = $user->end_date;
                    $current_date = Carbon::now();  
                    if($current_date->greaterThanOrEqualTo($start_date) && $current_date->lessThanOrEqualTo($end_date)){
                        return redirect("/shop");
                    }else{
                        Auth::logout();
                        return redirect("/login")->with("error", "Your account is expire. Please contact support.");
                    }
                    
                }elseif($user->hasRole('user')){
                    // dd("Test");
                    return redirect("/user");
                }else{
                    return abort(403);
                }
            }else{
                Auth::logout();
                return redirect("/login")->with("error", "Your account is not active. Please contact support.");
            }
          
        }else{
            return redirect("/login");
        }
    }

   

}
