<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{
	public function view_invoice() {
		return view('pages.invoice');
	}
}
