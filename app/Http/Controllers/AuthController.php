<?php

namespace App\Http\Controllers;

use App\Http\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
	protected $authService;
	public function __construct(AuthService $authService)
	{
		$this->authService = $authService;
	}

	public function signup(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name'	 		=> 'required|string|min:3',
			'phone' 		=> 'required|string|min:10|max:20|unique:users,phone_number',
			'email' 		=> 'required|email|unique:users,email',
			'password' 	=> 'required|string|min:6'
		]);

		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'message' => $validator->errors()
			], 500);
		}

		$result = $this->authService->registerUser($request->all());
		return response()->json($result, $result['error_code']);
	}

	public function signin(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'email' 		=> 'required|email|min:6',
			'password' 	=> 'required|string|min:6'
		]);

		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'message' => $validator->errors()
			], 500);
		}

		$result = $this->authService->auth($request->all());
		return response()->json($result, $result['error_code']);
	}
}
