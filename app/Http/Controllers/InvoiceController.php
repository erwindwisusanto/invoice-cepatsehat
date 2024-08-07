<?php

namespace App\Http\Controllers;

use App\Http\Services\InvoiceService;
use Carbon\Carbon;
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
		$infusions = $this->invoiceService->getInfusions();

		return view('pages.new-invoice', compact('invoiceNumber', 'date', 'paymentMethods', 'icdxs', 'cpts', 'infusions'));
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

	public function getDefaultCpt()
	{
		$data = $this->invoiceService->DefaultCpt();
		return response()->json([
			'status' => 'success',
			'data' => $data,
    ]);
	}

	public function invoices()
	{
		$invoices = $this->invoiceService->GetInvoices();
		return DataTables::of($invoices)->make(true);
	}

	public function draftInvoice(Request $request, $invoiceId)
	{
		$invoice = $this->invoiceService->getInvoice(decryptData($invoiceId));

		$invoiceNumber = $invoice->invoice_number;
		$date = Carbon::createFromFormat('Y-m-d H:i:s', $invoice->created_at);
		$formattedDate = $date->format('Y-M-d');
		$address = $invoice->address;
		$phone = $invoice->phone;
		$complimentaryDiscount = $invoice->complimentary_discount;
		$medicalTeamTransportCost = $invoice->medical_team_transport_cost;
		$paymentMethodSelected = $invoice->payment_method;
		$diagnosis = json_decode($invoice->diagnosis) ?? [];
		$username = $invoice->username;

		$paymentMethods = $this->invoiceService->ListPaymentMethod();
		$infusions = $this->invoiceService->getInfusions();
		$icdxs = $this->invoiceService->ListIcdxs();
		$cpts = $this->invoiceService->ListCpts();

		return view('pages.draft-invoice',
			compact(
				'invoiceNumber',
				'address',
				'phone',
				'complimentaryDiscount',
				'medicalTeamTransportCost',
				'paymentMethods',
				'paymentMethodSelected',
				'diagnosis',
				'formattedDate',
				'icdxs',
				'cpts',
				'username',
				'infusions'
				)
		);
	}

	public function viewInvoiceGuest($invoiceId)
	{
		// $invoice = $this->invoiceService->getInvoice(decryptData($invoiceId));
		return view('pages.preview-invoice');
	}
}
