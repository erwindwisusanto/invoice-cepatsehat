<?php
namespace App\Http\Services;

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
}
