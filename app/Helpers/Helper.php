<?php

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

if (!function_exists('encryptData')) {
	function encryptData($data)
	{
		return Crypt::encryptString($data);
	}
}

if (!function_exists('decryptData')) {
	function decryptData($encryptedData)
	{
		try {
			return Crypt::decryptString($encryptedData);
		} catch (DecryptException $e) {
			return 'Decryption failed';
		}
	}
}
