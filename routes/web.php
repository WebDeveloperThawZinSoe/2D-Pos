<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RouteCheckController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\DineController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Http\Controllers\UserPageController;




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



Route::get("/",[PageController::class, 'index'])->name("home");


Route::middleware(['web'])->group(function () {
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    Route::middleware(['role:user', 'require.date.section'])->prefix('user')->name('user.')->group(function () {
        Route::get('/', [UserPageController::class, 'index'])->name('index');
        Route::post("/number_store",[UserPageController::class,"number_store"])->name("number.store");
        Route::post("/order/status",[UserPageController::class,"order_status"])->name("order.status");
        Route::post("/order/delete",[UserPageController::class,"order_delete"])->name("order.delete");
        Route::get("/my/order",[UserPageController::class,"my_order"])->name("my.order");
        Route::get("/change/password",[UserPageController::class,"changePassword"])->name("changePassword");
        Route::post('/password/update', [UserPageController::class, 'updatePassword'])->name('password.update')->middleware('auth');
    });


    Route::middleware(['role:shop', 'require.date.section'])->prefix('dine')->name('dine.')->group(function () {
        Route::get("/", [DineController::class, "index"])->name("index");
        Route::get("/agents", [DineController::class, "agents"])->name("agents");
        Route::post("/agents", [DineController::class, "storeAgent"])->name("storeAgent");
        Route::get("/agents/{id}/edit", [DineController::class, "editAgent"])->name("editAgent");
        Route::post("/agents/{id}/update", [DineController::class, "updateAgent"])->name("updateAgent");
        Route::post("/agents/{id}/delete", [DineController::class, "deleteAgent"])->name("deleteAgent");
        Route::get("/redine", [DineController::class, "redine"])->name("redine");
        Route::post("/redine", [DineController::class, "storeRedine"])->name("storeRedine");
        Route::get("/redine/{id}/edit", [DineController::class, "editRedine"])->name("editRedine");
        Route::post("/redine/{id}/update", [DineController::class, "updateRedine"])->name("updateRedine");
        Route::post("/redine/{id}/delete", [DineController::class, "updateRedine"])->name("updateRedine");
    });


    Route::middleware(['require.date.section','role:shop'])->group(function () {
        Route::post("/number_store",[OrderController::class,"number_store"])->name("number.store");
        Route::post("/multi/number_store",[OrderController::class,"number_store_multi"])->name("number_store_multi.store");
        Route::post("/order/status",[OrderController::class,"order_status"])->name("order.status");
        Route::post("/order/delete",[OrderController::class,"order_delete"])->name("order.delete");
        Route::post("/order/confirm/all",[OrderController::class,"order_cofirm_all"]);
        Route::post("/delete/all",[OrderController::class,"delete_all"]);
        Route::post("/close/number/store",[OrderController::class,"close_number_store"]);
        Route::post("/close/number/delete",[OrderController::class,"close_number_delete"]);
        Route::post("/limit/store",[OrderController::class,"limit_store"]);
        Route::get("/rebuy",[OrderController::class,"rebuy"]);
    });
    

    Route::get('/select-date-section', function (Request $request) {
        $date = Request::query('get_date');
        $section = Request::query('get_am_pm');
    
        // If selected, redirect user based on role
        if ($date && $section) {
            session([
                'selected_date' => $date,
                'selected_section' => $section,
            ]);
            if (auth()->check()) {
                if (auth()->user()->hasRole('user')) {
                    return redirect()->route('user.index');
                }
                if (auth()->user()->hasRole('shop')) {
                    return redirect()->route('dine.index');
                }
            }
            return redirect('/'); // fallback
        }
        return view('web.select-date-section')->with('error', 'Please select both date and section.');
    })->name('select.date.section');
    
    
});


Route::fallback(function () {
    if (abort(403)) {
        return redirect()->route('home');
    }   
    if(abort(404)) {
        return redirect()->route('home');
    }
});

