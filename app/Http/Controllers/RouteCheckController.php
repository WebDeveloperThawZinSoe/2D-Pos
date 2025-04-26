<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class RouteCheckController extends Controller
{
    //check
    public function check(){
        if(Auth::user()){
            $user = Auth::user();
            if($user->hasRole('admin')){
                // dd("admin");
                return redirect("/admin");
            }elseif($user->hasRole('shop')){
                // dd("shop");
                return redirect("/shop");
            }elseif($user->hasRole('user')){
                // dd("user");
                return redirect("/user");
            }else{
                return abort(403);
            }
        }else{
            return redirect("/login");
        }
       
    }
}
