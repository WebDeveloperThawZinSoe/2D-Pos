<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserPageController extends Controller
{
    //index
    public function index(){
       return view("web.user.index");
    }
}
