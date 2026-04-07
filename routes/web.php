<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

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

Route::middleware('guest')->group(function () {
    Route::get('/register' , [RegisterController::class , 'showForm'])->name('register');
    Route::post('/register' , [RegisterController::class , 'register']);

    Route::get('/login', [LoginController::class , 'showForm'])->name('login');
    Route::post('/login',[LoginController::class , 'login']);
});

Route::middleware('auth.custom')->group(function () {
    Route::get('/dashboard' , fn() => view('dashboard'))->name('dashboard');
    Route::post('/logout' , [LoginController::class, 'logout'])->name('logout');

});

Route::middleware(['auth.custom' , 'auth.admin'])->prefix('admin')->group(function () {

    Route::get('/dashboard' , fn() => view('admin.dashboard'))->name('admin.dashboard');
});
