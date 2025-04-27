<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RouteCheckController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;




Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function(){
        return redirect("/route/check");
    })->name('dashboard');
});


Route::get("/route/check", [PageController::class, 'index'])->name("routeCheck");

// Route::middleware(['role:admin'])->group(function () {
//     Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
//     // Other admin routes
// });

// Route::middleware(['role:shop'])->group(function () {
//     Route::get('/shop/dashboard', [ShopController::class, 'dashboard']);
//     // Other shop routes
// });

// Route::middleware(['role:user'])->group(function () {
//     Route::get('/user/dashboard', [UserController::class, 'dashboard']);
//     // Other user routes
// });


Route::get("/",[PageController::class, 'index'])->name("home");


Route::middleware(['web'])->group(function () {
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});


Route::fallback(function () {
    if (abort(403)) {
        return redirect()->route('home');
    }   
    if(abort(404)) {
        return redirect()->route('home');
    }
});

