<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CookieController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/cookie/create', [CookieController::class, 'store'])->name('cookie.create');
Route::get('/cookie', [CookieController::class, 'index'])->name('cookie.index');
Route::delete('/cookie/{cookie}', [CookieController::class, 'destroy'])->name('cookie.destroy');
Route::get('/cookie/{cookie}/edit', [CookieController::class, 'edit'])->name('cookie.edit');
Route::put('/cookie/{cookie}', [CookieController::class, 'update'])->name('cookie.update');
