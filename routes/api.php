<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CookieController;
use App\Http\Controllers\AuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("/test-cookies", function() {
    return response()->json([
        'message'=> 'Cookies List API'
    ]);
});


Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');



Route::group(['middleware' => 'jwt'], function () {
    Route::get("/cookies",[CookieController::class,'get_cookies']);
    Route::post("/cookies",[CookieController::class,'create_cookie']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
