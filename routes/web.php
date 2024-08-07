<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:web')->group(function () {
	Route::view('/signin', 'pages.signin')->name('signin');
	Route::view('/signup', 'pages.signup')->name('signup');
	Route::post('/signup', [AuthController::class, 'signup'])->name('post_signup');
	Route::post('/signin', [AuthController::class, 'signin'])->name('post_signin');

});

Route::middleware('auth')->group(function () {
	Route::get('/', [InvoiceController::class, 'view_invoice'])->name('view_invoice')->middleware("auth");
	Route::get('/new-invoice', [InvoiceController::class, 'newInvoice'])->name('view_new_invoice')->middleware("auth");
	Route::get('/draft-invoice/{id}', [InvoiceController::class, 'draftInvoice'])->name('view_draft_invoice')->middleware("auth");
	Route::get('/invoices', [InvoiceController::class, 'invoices'])->name('invoices')->middleware("auth");
	Route::get('/getDefaultCpt', [InvoiceController::class, 'getDefaultCpt'])->name('get_default_cpt')->middleware("auth");

	Route::post('/new-invoice', [InvoiceController::class, 'createNewInvoice'])->name('post_new_invoice')->middleware("auth");
});

Route::get('/invoice/{id}', [InvoiceController::class, 'viewInvoiceGuest'])->name('view_invoice_guest');




