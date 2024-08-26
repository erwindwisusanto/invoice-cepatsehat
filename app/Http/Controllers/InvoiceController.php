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
		$cpts = $this->invoiceService->ListCpts();
		$infusions = $this->invoiceService->getInfusions();
		$services = $this->invoiceService->ListServices();

		return view('pages.new-invoice', compact('invoiceNumber', 'date', 'paymentMethods', 'cpts', 'infusions', 'services'));
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
		$data = $this->invoiceService->DefaultCpt(1);
		if ($data && is_object($data)) {
			// Return a JSON response with CPT data
			return response()->json([
					'data' => [
							'cpt_id' => $data->id ?? null,  // Use null coalescing operator for safety
							'cpt_code' => $data->code ?? '',  // Use default empty string if not set
							'cpt_pax' => 1,
							'cpt_desc' => $data->description ?? '',  // Default empty string if description not set
							'cpt_price' => $data->price ?? 0.0,  // Default to 0.0 if price not set
							'cpt_infusion' => $data->infusion ?? '',  // Use default empty string if infusion not set
							'cpt_additional' => $data->additional ?? '',  // Use default empty string if additional not set
							'cpt_icd' => $data->icd ?? []  // Ensure cpt_icd is an array, default to empty array if not set
					]
			]);
		} else {
				// Return an error response if data is not valid
				return response()->json([
						'error' => 'Default CPT data not found or invalid.'
				], 404);
		}
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
		$costNightService = $invoice->cost_night_service;

		$paymentMethods = $this->invoiceService->ListPaymentMethod();
		$infusions = $this->invoiceService->getInfusions();
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
				'cpts',
				'username',
				'infusions',
				'invoiceId',
				'services',
				'service_selected',
				'costNightService'
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
		$costNightService = $invoice->cost_night_service;
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
				'paymentMethods',
				'costNightService'
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

	public function getIcdxs(Request $request)
	{
		$searchTerm = $request->input('search');
		$page = $request->input('page', 1);
		$pageSize = 50;

		$result = $this->invoiceService->ListIcdxs($searchTerm, $page, $pageSize);

		return response()->json([
			'items' => $result['items'],
			'total' => $result['total'],
			'pageSize' => $pageSize
		]);
	}
}
