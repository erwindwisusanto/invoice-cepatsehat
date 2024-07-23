<?php
namespace App\Http\Services;

use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService
{
	public function registerUser(array $param): array
	{
		try {
			DB::table('users')->insert([
				['email' => $param['email'],
					'name' => $param['name'],
					'phone_number' => $param['phone'],
					'password' => bcrypt($param['password']),
					'created_at' => Carbon::now(),
					'updated_at' => Carbon::now()
				],
			]);

			return [
				'success' 	=> true,
				'message' 	=> 'success',
				'error_code'=>  201
			];
		} catch (Exception $e) {
			return [
				'success' 	=> false,
				'message' 	=> $e->getMessage(),
				'error_code'=>  500
			];
		}
	}

	public function auth(array $param)
	{
		$user = $this->getUserByEmail($param['email']);
		if ($user && Hash::check($param['password'], $user->password)) {
			Auth::login($user);
			return [
				'success' 	=> true,
				'message' 	=> 'success',
				'error_code'=>  200
			];
		}

		return [
			'success' 	=> false,
			'message' 	=> 'record not found',
			'error_code'=>  422
		];
	}

	public function getUserByEmail(string $email)
	{
		return User::select('id', 'name', 'password', 'phone_number', 'email')->where('email', $email)->first();
	}
}
