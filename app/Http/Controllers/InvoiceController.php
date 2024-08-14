<?php

namespace App\Http\Controllers;

use App\Http\Services\InvoiceService;
use App\Http\Services\QontakService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class InvoiceController extends Controller
{

	protected $invoiceService;
	protected $qontakService;
	public function __construct(InvoiceService $invoiceService, QontakService $qontakService)
	{
		$this->invoiceService = $invoiceService;
		$this->qontakService = $qontakService;
	}

	public function view_invoice()
	{
		return view('pages.invoice');
	}

	public function success()
	{
		return view('pages.success');
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
		$services = $this->invoiceService->ListServices();

		return view('pages.new-invoice', compact('invoiceNumber', 'date', 'paymentMethods', 'icdxs', 'cpts', 'infusions', 'services'));
	}

	public function createNewInvoice(Request $request)
	{
		$form = [];
    parse_str($request->input('form'), $form);
		$form2 = json_decode($request->input('form2'), true);
		$formType = (string) $request->input('formType');
		$buttonType = (string) $request->input('buttonType');

		if ($formType === "NEW INVOICE") {
			$result = $this->invoiceService->saveNewInvoice($form, $form2, $buttonType);
		}

		if ($formType === "DRAFT INVOICE") {
			$result = $this->invoiceService->updateInvoice($form, $form2, $buttonType);
		}

		if (!$result['success']) {
			return response()->json([
				'status' => 'error',
				'message' => $result['message'],
			]);
		}

		return response()->json([
			'status' => 'success',
			'message' => $result['message'],
			'isDraft' => $result['isDraft'],
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
		if ($invoices->isEmpty()) {
			return DataTables::of([])->make(true);
		}
		return DataTables::of($invoices)->make(true);
	}

	public function draftInvoice(Request $request, $invoiceId)
	{
		$invoice = $this->invoiceService->getInvoice(decryptId($invoiceId));

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
		$invoiceId = encryptID($invoice->id);
		$service_selected = (int) $invoice->service;

		$paymentMethods = $this->invoiceService->ListPaymentMethod();
		$infusions = $this->invoiceService->getInfusions();
		$icdxs = $this->invoiceService->ListIcdxs();
		$cpts = $this->invoiceService->ListCpts();
		$services = $this->invoiceService->ListServices();

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
				'infusions',
				'invoiceId',
				'services',
				'service_selected'
				)
		);
	}

	public function viewInvoiceGuest(Request $request, $invoiceId)
	{

		if (!$request->query('view')) {
			return redirect()->route('view_invoice');
		}

		$invoice = $this->invoiceService->getInvoice(decryptID($invoiceId));

		if ($invoice->status === 3) {
			return redirect()->route('view_invoice_approved');
		}

		$invoiceNumber = $invoice->invoice_number;
		$date = Carbon::parse($invoice->created_at)->format('F, j Y');
		$username = $invoice->username;
		$address = $invoice->address;
		$diagnosis = json_decode($invoice->diagnosis) ?? [];
		$complimentaryDiscount = $invoice->complimentary_discount;
		$medicalTeamTransportCost = $invoice->medical_team_transport_cost;
		$invoiceId = encryptID($invoice->id);
		$paymentMethods = $this->invoiceService->paymentMethodName(json_decode($invoice->payment_method));

		return view('pages.preview-invoice',
			compact(
				'invoiceNumber',
				'date',
				'username',
				'address',
				'diagnosis',
				'complimentaryDiscount',
				'medicalTeamTransportCost',
				'invoiceId',
				'paymentMethods'
			)
		);
	}

	public function viewInvoicePatient($invoiceId)
	{
		$invoice = $this->invoiceService->getInvoice(decryptID($invoiceId));

		$invoiceNumber = $invoice->invoice_number;
		$date = Carbon::parse($invoice->created_at)->format('F, j Y');
		$username = $invoice->username;
		$address = $invoice->address;
		$diagnosis = json_decode($invoice->diagnosis) ?? [];
		$complimentaryDiscount = $invoice->complimentary_discount;
		$medicalTeamTransportCost = $invoice->medical_team_transport_cost;

		$paymentMethod = $this->invoiceService->paymentMethodName(json_decode($invoice->payment_method));

		$data = [
			'invoiceNumber' => $invoiceNumber,
			'date' => $date,
			'username' => $username,
			'address' => $address,
			'diagnosis' => $diagnosis,
			'complimentaryDiscount' => $complimentaryDiscount,
			'medicalTeamTransportCost' => $medicalTeamTransportCost,
			'paymentMethod' => $paymentMethod
		];

		$filename = "invoice-$invoiceId.pdf";
		return Pdf::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
		->setPaper('A4')
		->loadView('pdf.invoice', $data)
		->stream($filename);
	}

	public function viewInvoiceApproved()
	{
		return view('pages.approved');
	}
}
