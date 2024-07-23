<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::view('/signin', 'pages.signin')->name('signin');
Route::view('/signup', 'pages.signup')->name('signup');
Route::post('signup', [AuthController::class, 'signup'])->name('post_signup');
Route::post('signin', [AuthController::class, 'signin'])->name('post_signin');

// with session
Route::get('/', [InvoiceController::class, 'view_invoice'])->name('view_invoice')->middleware("auth");

