<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('encryptID')) {
	function encryptID($string)
	{
		$output = false;

		$security = parse_ini_file("security.ini");
		$secret_key = $security["encryption_key"];
		$secret_iv = $security["iv"];
		$encrypt_method = $security["encryption_mechanism"];

		$key = hash("sha256", $secret_key);
		$iv = substr(hash("sha256", $secret_iv), 0, 16);

		$result = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
		$output = base64_encode($result);
		return $output;
	}
}

if (!function_exists('decryptID')) {
	function decryptID($string)
	{
		$output = false;

		$security = parse_ini_file("security.ini");
		$secret_key = $security["encryption_key"];
		$secret_iv = $security["iv"];
		$encrypt_method = $security["encryption_mechanism"];

		$key = hash("sha256", $secret_key);
		$iv = substr(hash("sha256", $secret_iv), 0, 16);

		$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
		return $output;
	}
}

if (!function_exists('numberToWords')) {
	function numberToWords($number) {
    $words = array(
			0 => 'Zero',
			1 => 'One',
			2 => 'Two',
			3 => 'Three',
			4 => 'Four',
			5 => 'Five',
			6 => 'Six',
			7 => 'Seven',
			8 => 'Eight',
			9 => 'Nine',
			10 => 'Ten',
			11 => 'Eleven',
			12 => 'Twelve',
			13 => 'Thirteen',
			14 => 'Fourteen',
			15 => 'Fifteen',
			16 => 'Sixteen',
			17 => 'Seventeen',
			18 => 'Eighteen',
			19 => 'Nineteen',
			20 => 'Twenty',
			30 => 'Thirty',
			40 => 'Forty',
			50 => 'Fifty',
			60 => 'Sixty',
			70 => 'Seventy',
			80 => 'Eighty',
			90 => 'Ninety',
			100 => 'Hundred',
			1000 => 'Thousand',
			1000000 => 'Million',
			1000000000 => 'Billion'
		);

		if ($number == 0) return $words[0];

		$result = '';

		if ($number >= 1000000000) {
				$result .= numberToWords(floor($number / 1000000000)) . ' ' . $words[1000000000];
				$number %= 1000000000;
		}
		if ($number >= 1000000) {
				$result .= (empty($result) ? '' : ' ') . numberToWords(floor($number / 1000000)) . ' ' . $words[1000000];
				$number %= 1000000;
		}
		if ($number >= 1000) {
				$result .= (empty($result) ? '' : ' ') . numberToWords(floor($number / 1000)) . ' ' . $words[1000];
				$number %= 1000;
		}
		if ($number >= 100) {
				$result .= (empty($result) ? '' : ' ') . numberToWords(floor($number / 100)) . ' ' . $words[100];
				$number %= 100;
		}
		if ($number >= 20) {
				$result .= (empty($result) ? '' : ' ') . $words[floor($number / 10) * 10];
				$number %= 10;
		}
		if ($number > 0) {
				$result .= (empty($result) ? '' : ' ') . $words[$number];
		}

		return $result;
	}

	if (!function_exists('getServiceName')) {
		function getServiceName($serviceId)
		{
			$service = DB::table('service')->select('name')->where('id', $serviceId)->first();
			return $service->name;
		}
	}

	if (!function_exists('formatCurrency')) {
		function formatCurrency($input) {
			$value = preg_replace('/[^0-9]/', '', $input);
			$formattedValue = number_format($value, 0, ',', '.');
			return $formattedValue;
		}
	}
}
