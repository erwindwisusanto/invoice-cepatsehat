<?php
namespace App\Http\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
	public function ListPaymentMethod()
	{
		$paymentMethods = DB::table('payment')->where('is_active', 1)->get();
		return $paymentMethods;
	}

	public function ListIcdxs()
	{
		$icdx = DB::table('icdx')->where('is_active', 1)->get();
		return $icdx;
	}

	public function ListCpts()
	{
		$cpt = DB::table('cpt')->get();
		return $cpt;
	}

	public function saveNewInvoice($form, $form2)
	{
		$username = $form['username'] ?? null;
    $address 	= $form['address'] ?? null;
    $phoneNumber = $form['phone_number'] ?? null;
    $complimentaryDiscount = !empty($form['complimentary_discount']) ? $form['complimentary_discount'] : 0;
    $medicalTeamTransportCost = !empty($form['medical_team_transport_cost']) ? $form['medical_team_transport_cost'] : 0;
    $invoiceNumber = $form['invoice_number'] ?? null;

		$payment_methods = [];
    if (isset($form['payment_method']) && is_array($form['payment_method'])) {
      $payment_methods = array_map('intval', $form['payment_method']);
    }

		$cpt_data = [];
    if (is_array($form2)) {
			foreach ($form2 as $item) {
				$cpt_data[] = [
					'cpt_id' => $item['cpt_id'],
					'cpt_code' => $item['cpt_code'],
					'cpt_pax' => $item['cpt_pax'],
					'cpt_desc' => $item['cpt_desc'],
					'cpt_icd' => $item['cpt_icd']
				];
			}
    }

		$uniqueNumbers = $this->getLatestUniqueNumber();

		$payment_methods_json = json_encode($payment_methods);
    $cpt_data_json = json_encode($cpt_data);

		DB::beginTransaction();

		try {
			sleep(1);
			DB::table('invoice')->insert([
				'username' => $username,
				'address' => $address,
				'phone' => $phoneNumber,
				'invoice_numner' => $invoiceNumber,
				'complimentary_discount' => $complimentaryDiscount,
				'medical_team_transport_cost' => $medicalTeamTransportCost,
				'payment_method' => $payment_methods_json,
				'diagnosis' => $cpt_data_json,
				'unique_invoice_number' => $uniqueNumbers,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now(),
			]);

			DB::commit();

			return [
				'success' => true,
				'message' => 'success create new invoice',
			];
		} catch (\Exception $e) {
			DB::rollBack();
			return [
				'success' => false,
				'message' => $e->getMessage(),
			];
		}
	}

	public function getLatestUniqueNumber()
	{
		$currentMonth = Carbon::now()->format('m');
		$currentYear = Carbon::now()->format('Y');

		$latestInvoice = DB::table('invoice')
			->orderBy('created_at', 'desc')
			->orderBy('unique_invoice_number', 'desc')
			->first();

		$newNumber = '00001';

		if ($latestInvoice) {
			$latestMonth = Carbon::parse($latestInvoice->created_at)->format('m');
			$latestYear = Carbon::parse($latestInvoice->created_at)->format('Y');

			if ($currentMonth == $latestMonth && $currentYear == $latestYear) {
				$latestNumber = (int)$latestInvoice->unique_invoice_number;
				$newNumber = str_pad($latestNumber + 1, 5, '0', STR_PAD_LEFT);
			}
		}

		return $newNumber;
	}

	public function GetInvoices()
	{
		try {
			$invoices = DB::table('invoice')->orderByDesc('id')->get();
			return $invoices;
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}
}
