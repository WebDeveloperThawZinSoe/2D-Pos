<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DineController extends Controller
{
    //index
    public function index(){
       return view("web.dine.index");
    }
}
