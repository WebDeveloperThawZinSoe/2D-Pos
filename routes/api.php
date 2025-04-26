<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\MainController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::get("/category",[MainController::class,"category"]);
Route::get("/region",[MainController::class,"region"]);
Route::get("/category/{id}",[MainController::class, 'categoryDetail']);
Route::get("/region/{id}",[MainController::class, 'regionDetail']);
Route::get("/shop/{id}",[MainController::class,"shopDetail"]);
Route::get("/ads",[MainController::class,"getAds"]);
Route::get("/nearby/{lat}/{long}",[MainController::class,"nearBy"]);