<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::middleware('guest:web')->group(function () {
	Route::view('/signin', 'pages.signin')->name('signin');
	Route::view('/signup', 'pages.signup')->name('signup');
	Route::post('/signup', [AuthController::class, 'signup'])->name('post_signup');
	Route::post('/signin', [AuthController::class, 'signin'])->name('post_signin');

});

Route::middleware('auth')->group(function () {
	Route::get('/', [InvoiceController::class, 'view_invoice'])->name('view_invoice');
	Route::get('/new-invoice', [InvoiceController::class, 'newInvoice'])->name('view_new_invoice');
	Route::get('/draft-invoice/{id}', [InvoiceController::class, 'draftInvoice'])->name('view_draft_invoice');
	Route::get('/invoices', [InvoiceController::class, 'invoices'])->name('invoices');
	Route::get('/getDefaultCpt', [InvoiceController::class, 'getDefaultCpt'])->name('get_default_cpt');

	Route::get('success', [InvoiceController::class, 'success'])->name('success');

	Route::post('/new-invoice', [InvoiceController::class, 'createNewInvoice'])->name('post_new_invoice');
});

Route::get('/invoice/{id}', [InvoiceController::class, 'viewInvoiceGuest'])->name('view_invoice_guest');
Route::post('doctor-action', [InvoiceController::class, 'doctorAction'])->name('doctor-action')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);



