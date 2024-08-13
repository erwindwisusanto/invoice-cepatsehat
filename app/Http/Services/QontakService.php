<?php
namespace App\Http\Services;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class QontakService
{
	protected $client;
	protected $API_URL = "https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct";

	public function __construct()
	{
		$this->client = new Client();
	}

	public function send($data)
	{
		try {
			Log::channel('qontak')->info('[REQUEST]'.json_encode($data));

			$response = Http::withHeaders([
				'Authorization' => "Bearer {$data['key']}",
				'Content-Type' => "application/json"
			])->post($this->API_URL, [
				'to_number' => $data['to_number'],
				'to_name' => $data['to_name'],
				'message_template_id' => $data['message_template_id'],
				'channel_integration_id' => $data['channel_integration_id'],
				'language' => [
					'code' => $data['lang_code']
				],
				'parameters' => [
					'body' => $data['body']
				]
			]);

			Log::channel('qontak')->info('[RESPONSE]'.json_encode($response->body()));

			return true;
		} catch (\Throwable $e) {
			Log::channel('qontak')->error('[ERROR] request: ' . json_encode($data) . $e->getMessage());
			return false;
		}
	}

	public function sendWhatsAppMessageDoctor(string $doctorPhoneNumber, string $doctorName, string $patientName, int $service, string $datetime, $invoiceId)
	{
		$data = [
			'key' => config('key.QONTAK_NURSE_TO_DOCTOR_KEY'),
			'to_number' => $doctorPhoneNumber,
			'to_name' => $doctorName,
			'message_template_id' => config('key.QONTAK_TEMPLATE_MESSAGE_DOCTOR'),
			'channel_integration_id' => config('key.QONTAK_CHANEL_INTEGRATION_ID'),
			'lang_code' => 'en',
			'body' => [
				[
					'key' => '1',
					'value' => 'doctor_name',
					'value_text' => $doctorName
				],
				[
					'key' => '2',
					'value' => 'patient_name',
					'value_text' => $patientName
				],
				[
					'key' => '3',
					'value' => 'service_name',
					'value_text' => getServiceName($service)
				],
				[
					'key' => '4',
					'value' => 'date_request',
					'value_text' => Carbon::parse($datetime)->toDateString()
				],
				[
					'key' => '5',
					'value' => 'time_request',
					'value_text' => Carbon::parse($datetime)->format('H:i')
				],
				[
					'key' => '6',
					'value' => 'url_verify',
					'value_text' => request()->getSchemeAndHttpHost() . "/invoice/" . encryptID($invoiceId) . "?view=oYR7Y"
				]
			],
		];

		return $this->send($data);
	}

	public function sendWhatsAppMessagePatient($phonenumber, $name, $service, $datetime, $invoiceId)
	{
		$data = [
			'key' => config('key.QONTAK_DOCTOR_TO_PATIENT_KEY'),
			'to_number' => $phonenumber,
			'to_name' => $name,
			'message_template_id' => config('key.QONTAK_TEMPLATE_MESSAGE_PATIENT'),
			'channel_integration_id' => config('key.QONTAK_CHANEL_INTEGRATION_ID'),
			'lang_code' => 'en',
			'body' => [
				[
					'key' => '1',
					'value' => 'patient_name',
					'value_text' => $name
				],
				[
					'key' => '2',
					'value' => 'service_name',
					'value_text' => getServiceName($service)
				],
				[
					'key' => '3',
					'value' => 'date_request',
					'value_text' => Carbon::parse($datetime)->toDateString()
				],
				[
					'key' => '4',
					'value' => 'time_request',
					'value_text' => Carbon::parse($datetime)->format('H:i')
				],
				[
					'key' => '5',
					'value' => 'url_pdf',
					'value_text' => request()->getSchemeAndHttpHost() . "/pdf/" . encryptID($invoiceId)
				]
			],
		];

		return $this->send($data);
	}

}
