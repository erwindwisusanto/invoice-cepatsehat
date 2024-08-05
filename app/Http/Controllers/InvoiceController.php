<?php

namespace App\Http\Controllers;

use App\Http\Services\InvoiceService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

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

	public function newInvoice()
	{
		$year 					= date('Y');
		$month 					= date('m');
		$string 				= "CSI";
		$dynamicNumber 	= $this->invoiceService->getLatestUniqueNumber();

		$invoiceNumber =  $dynamicNumber . '/'. $string .'/' . $month. '/' . $year;
		$date = date('d F Y');

		$paymentMethods = $this->invoiceService->ListPaymentMethod();
		$icdxs = $this->invoiceService->ListIcdxs();
		$cpts = $this->invoiceService->ListCpts();

		return view('pages.new-invoice', compact('invoiceNumber', 'date', 'paymentMethods', 'icdxs', 'cpts'));
	}

	public function createNewInvoice(Request $request)
	{
		$form = [];
    parse_str($request->input('form'), $form);
		$form2 = json_decode($request->input('form2'), true);

		$result = $this->invoiceService->saveNewInvoice($form, $form2);

		if (!$result['success']) {
			return response()->json([
				'status' => 'error',
				'message' => $result['message'],
			]);
		}

		return response()->json([
			'status' => 'success',
			'message' => $result['message'],
    ]);
	}

	public function invoices(Request $request)
	{
		$invoices = $this->invoiceService->GetInvoices();
		return DataTables::of($invoices)->make(true);
	}
}
