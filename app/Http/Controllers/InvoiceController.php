<?php

namespace App\Http\Controllers;

use App\Http\Services\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{

	protected $invoiceService;
	public function __construct(InvoiceService $invoiceService)
	{
		$this->invoiceService = $invoiceService;
	}

	public function view_invoice()
	{
		return view('pages.invoice');
	}

	public function newInvoice() {
		$year 					= date('Y');
		$month 					= date('m');
		$string 				= "CSI";
		$dynamicNumber 	= "0";

		$invoiceNumber =  $dynamicNumber . '/'. $string .'/' . $month. '/' . $year;
		$date = date('d F Y');

		$paymentMethods = $this->invoiceService->ListPaymentMethod();
		$icdxs = $this->invoiceService->ListIcdxs();
		$cpts = $this->invoiceService->ListCpts();

		return view('pages.new-invoice', compact('invoiceNumber', 'date', 'paymentMethods', 'icdxs', 'cpts'));
	}
}
