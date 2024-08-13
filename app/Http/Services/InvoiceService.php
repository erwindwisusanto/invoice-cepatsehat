<?php
namespace App\Http\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InvoiceService
{

	protected $qontakService;
	public function __construct(QontakService $qontakService)
	{
		$this->qontakService = $qontakService;
	}

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

	public function ListServices()
	{
		$cpt = DB::table('service')->get();
		return $cpt;
	}

	public function saveNewInvoice($form, $form2, $buttonType)
	{
		$username = $form['username'] ?? null;
    $address 	= $form['address'] ?? null;
    $phoneNumber = $form['phone_number'] ?? null;
    $complimentaryDiscount = !empty($form['complimentary_discount']) ? str_replace('.','', $form['complimentary_discount']) : 0;
    $medicalTeamTransportCost = !empty($form['medical_team_transport_cost']) ? str_replace('.','', $form['medical_team_transport_cost']) : 0;
    $invoiceNumber = $form['invoice_number'] ?? null;
		$service = (int) $form['service'] ?? 0;

		$statusDraft = 1;
		$statusOnProccess = 2;
		$status = $buttonType === "NEW" ? (int) $statusOnProccess : (int) $statusDraft;

		$payment_methods = [];
    if (isset($form['payment_method']) && is_array($form['payment_method'])) {
      $payment_methods = array_map('intval', $form['payment_method']);
    }

		$cpt_data = [];
    if (is_array($form2)) {
			foreach ($form2 as $item) {
				$price = str_replace('.','', $item['cpt_price']);
				$cpt_data[] = [
					'cpt_id' => $item['cpt_id'],
					'cpt_code' => $item['cpt_code'],
					'cpt_pax' => $item['cpt_pax'],
					'cpt_desc' => $item['cpt_desc'],
					'cpt_price' => (int) $price,
					'cpt_infusion' => $item['cpt_infusion'] ?? '',
					'cpt_additional' => $item['cpt_additional'] ?? '',
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
			$invoiceId = DB::table('invoice')->insertGetId([
										'user_id' => auth()->user()->id,
										'username' => $username,
										'address' => $address,
										'phone' => $phoneNumber,
										'invoice_number' => $invoiceNumber,
										'complimentary_discount' => (int) $complimentaryDiscount,
										'medical_team_transport_cost' => (int) $medicalTeamTransportCost,
										'payment_method' => $payment_methods_json,
										'diagnosis' => $cpt_data_json,
										'unique_invoice_number' => $uniqueNumbers,
										'status' => $status,
										'service' => $service,
										'created_at' => Carbon::now(),
										'updated_at' => Carbon::now(),
									]);

			DB::commit();

			if ($status == 2) {
				$doctorName = "Erwin";
				$doctorPhoneNumber = "6282110796637";
				$invoice = $this->getInvoiceById($invoiceId);
				$this->qontakService->sendWhatsAppMessageDoctor($doctorPhoneNumber, $doctorName, $invoice->username, $invoice->service, $invoice->created_at, $invoice->id);
			}

			return [
				'success' => true,
				'message' => 'success create new invoice',
				'isDraft' => $status,
			];
		} catch (\Exception $e) {
			DB::rollBack();
			return [
				'success' => false,
				'message' => $e->getMessage(),
			];
		}
	}

	private function getInvoiceById($invoiceId)
	{
		return DB::table('invoice')->where('id', $invoiceId)->first();
	}

	public function updateInvoice($form, $form2, $buttonType)
	{
		$username = $form['username'] ?? null;
    $address 	= $form['address'] ?? null;
    $phoneNumber = $form['phone_number'] ?? null;
    $complimentaryDiscount = !empty($form['complimentary_discount']) ? str_replace('.','', $form['complimentary_discount']) : 0;
    $medicalTeamTransportCost = !empty($form['medical_team_transport_cost']) ? str_replace('.','', $form['medical_team_transport_cost']) : 0;
		$invoiceId = decryptID($form['invoice_id']) ?? null;
		$service = (int) $form['service'] ?? 0;

		$statusDraft = 1;
		$statusOnProccess = 2;
		$status = $buttonType === "NEW" ? $statusOnProccess : $statusDraft;

		$payment_methods = [];
    if (isset($form['payment_method']) && is_array($form['payment_method'])) {
      $payment_methods = array_map('intval', $form['payment_method']);
    }

		$cpt_data = [];
    if (is_array($form2)) {
			foreach ($form2 as $item) {
				$price = str_replace('.','', $item['cpt_price']);
				$cpt_data[] = [
					'cpt_id' => $item['cpt_id'],
					'cpt_code' => $item['cpt_code'],
					'cpt_pax' => $item['cpt_pax'],
					'cpt_desc' => $item['cpt_desc'],
					'cpt_price' => (int) $price,
					'cpt_infusion' => $item['cpt_infusion'] ?? '',
					'cpt_additional' => $item['cpt_additional'] ?? '',
					'cpt_icd' => $item['cpt_icd']
				];
			}
    }

		$payment_methods_json = json_encode($payment_methods);
    $cpt_data_json = json_encode($cpt_data);

		DB::beginTransaction();

		try {
			sleep(1);
			DB::table('invoice')->where('id', $invoiceId)->update([
				'user_id' => auth()->user()->id,
				'username' => $username,
				'address' => $address,
				'phone' => $phoneNumber,
				'complimentary_discount' => (int) $complimentaryDiscount,
				'medical_team_transport_cost' => (int) $medicalTeamTransportCost,
				'payment_method' => $payment_methods_json,
				'diagnosis' => $cpt_data_json,
				'status' => $status,
				'service' => $service,
				'updated_at' => Carbon::now(),
			]);

			DB::commit();

			if ($status == 2) {
				$doctorName = "Erwin";
				$doctorPhoneNumber = "6282110796637";
				$invoice = $this->getInvoiceById($invoiceId);
				$this->qontakService->sendWhatsAppMessageDoctor($doctorPhoneNumber, $doctorName, $invoice->username, $invoice->service, $invoice->created_at, $invoice->id);
			}

			return [
				'success' => true,
				'message' => 'success',
				'isDraft' => $status,
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
			$invoices = DB::table('invoice')->where('user_id', auth()->user()->id)->orderByDesc('created_at')->get();
			foreach ($invoices as $invoice) {
				$invoice->id = encryptId($invoice->id);
			}
			return $invoices;
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}

	public function getInvoice($invoiceId)
	{
		try {
			$invoices = DB::table('invoice')->where('id', $invoiceId)->first();
			return $invoices;
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}

	public function getInfusions()
	{
		try {
			$infusion = DB::table('infusion')->get();
			return $infusion;
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}

	public function DefaultCpt()
	{
		try {
			$cpt = DB::table('cpt')->where('id', 2)->first();
			return $cpt;
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}

	public function Accept($invoiceId)
	{
		try {
			$updateStatus = $this->updateStatusInvoiceToDone($invoiceId);

			if ($updateStatus) {
				$this->sendWhatsappToPatient($invoiceId);
			}

			return true;
		} catch (\Exception $e) {
			return false;
		}
	}

	public function EditInvoice($invoiceId)
	{
		try {
			$updateStatus = $this->updateStatusInvoiceToEditForDoctor($invoiceId);

			if (!$updateStatus) {
				return false;
			}

			return true;
		} catch (\Exception $e) {
			return false;
		}
	}

	private function sendWhatsappToPatient($invoiceId)
	{
		$decryptId = decryptID($invoiceId);
		$invoice = $this->getInvoiceById($decryptId);
		$this->qontakService->sendWhatsAppMessagePatient($invoice->phone, $invoice->username, $invoice->service, $invoice->created_at, $invoice->id);
	}

	private function updateStatusInvoiceToEditForDoctor($invoiceId)
	{
		try {
			DB::beginTransaction();
			DB::table('invoice')->where('id', decryptID($invoiceId))->update(['status' => 4]);
			DB::commit();

			return true;
		} catch (\Exception $e) {
			DB::rollBack();
			return false;
		}
	}

	private function updateStatusInvoiceToDone($invoiceId)
	{
		try {
			DB::beginTransaction();
			DB::table('invoice')->where('id', decryptID($invoiceId))->update(['status' => 3]);
			DB::commit();

			return true;
		} catch (\Exception $e) {
			DB::rollBack();
			return false;
		}
	}

	public function updateInvoiceDoctor($form, $form2)
	{
		$username = $form['username'] ?? null;
    $address 	= $form['address'] ?? null;
    $phoneNumber = $form['phone_number'] ?? null;
    $complimentaryDiscount = !empty($form['complimentary_discount']) ? str_replace('.','', $form['complimentary_discount']) : 0;
    $medicalTeamTransportCost = !empty($form['medical_team_transport_cost']) ? str_replace('.','', $form['medical_team_transport_cost']) : 0;
		$invoiceId = decryptID($form['invoice_id']) ?? null;
		$service = (int) $form['service'] ?? 0;

		$status = 2;

		$payment_methods = [];
    if (isset($form['payment_method']) && is_array($form['payment_method'])) {
      $payment_methods = array_map('intval', $form['payment_method']);
    }

		$cpt_data = [];
    if (is_array($form2)) {
			foreach ($form2 as $item) {
				$price = str_replace('.','', $item['cpt_price']);
				$cpt_data[] = [
					'cpt_id' => $item['cpt_id'],
					'cpt_code' => $item['cpt_code'],
					'cpt_pax' => $item['cpt_pax'],
					'cpt_desc' => $item['cpt_desc'],
					'cpt_price' => (int) $price,
					'cpt_infusion' => $item['cpt_infusion'] ?? '',
					'cpt_additional' => $item['cpt_additional'] ?? '',
					'cpt_icd' => $item['cpt_icd']
				];
			}
    }

		$payment_methods_json = json_encode($payment_methods);
    $cpt_data_json = json_encode($cpt_data);

		DB::beginTransaction();

		try {
			sleep(1);
			DB::table('invoice')->where('id', $invoiceId)->update([
				'username' => $username,
				'address' => $address,
				'phone' => $phoneNumber,
				'complimentary_discount' => (int) $complimentaryDiscount,
				'medical_team_transport_cost' => (int) $medicalTeamTransportCost,
				'payment_method' => $payment_methods_json,
				'diagnosis' => $cpt_data_json,
				'status' => $status,
				'service' => $service,
				'updated_at' => Carbon::now(),
			]);

			DB::commit();

			return [
				'success' => true,
				'message' => 'success',
				'isDraft' => $status,
			];
		} catch (\Exception $e) {
			DB::rollBack();
			return [
				'success' => false,
				'message' => $e->getMessage(),
			];
		}
	}

	public function paymentMethodName($data)
	{
		$data = (array) $data;
    try {
			$paymentMethods = DB::table('payment')
					->whereIn('id', $data)
					->pluck('name', 'id');

			return $paymentMethods->toArray();

    } catch (\Exception $e) {
      return [];
    }
	}
}
