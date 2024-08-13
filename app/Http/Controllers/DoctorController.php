<?php

namespace App\Http\Controllers;

use App\Http\Services\InvoiceService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
	protected $invoiceService;
	public function __construct(InvoiceService $invoiceService)
	{
		$this->invoiceService = $invoiceService;
	}

	public function doctorAction(Request $request)
	{
		$status = $request->status;
    $invoiceId = $request->invoice_id;

    if ($status === "ACCEPT") {
			$response = $this->invoiceService->Accept($invoiceId);
			if ($response) {
				$redirect = true;
				$type = 'ACCEPT';
			}
    } else if ($status === "REJECT") {
			$response = $this->invoiceService->EditInvoice($invoiceId);
			if ($response) {
				$redirect = true;
				$type = 'EDIT';
			}
		}

    return response()->json(['success' => $redirect, 'type' => $type]);
	}

	public function viewEditInvoiceDoctor($invoiceId)
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

		return view('pages.edit-invoice',
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

	public function saveInvoiceDoctor(Request $request)
	{
		$form = [];
    parse_str($request->input('form'), $form);
		$form2 = json_decode($request->input('form2'), true);

		$result = $this->invoiceService->updateInvoiceDoctor($form, $form2);

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
}
