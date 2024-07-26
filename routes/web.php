<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:web')->group(function () {
	Route::view('/signin', 'pages.signin')->name('signin');
	Route::view('/signup', 'pages.signup')->name('signup');
	Route::post('signup', [AuthController::class, 'signup'])->name('post_signup');
	Route::post('signin', [AuthController::class, 'signin'])->name('post_signin');
});

// with session
Route::get('/', [InvoiceController::class, 'view_invoice'])->name('view_invoice')->middleware("auth");
Route::get('/new-invoice', [InvoiceController::class, 'newInvoice'])->name('view_new_invoice')->middleware("auth");

