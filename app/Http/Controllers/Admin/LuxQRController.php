<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class LuxQRController extends Controller
{
 
	public function qr(Request $request)
	{
		return view('admin.lux.qr');
	}


}
