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
            if($user->hasRole('admin')){
                return redirect("/admin");
            }elseif($user->hasRole('shop')){
                return redirect("/shop");
            }elseif($user->hasRole('user')){
                return redirect("/user");
            }else{
                return abort(403);
            }
        }else{
            return redirect("/login");
        }
    }

   

}
