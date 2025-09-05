<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class StateController extends Controller
{
    public function detail(Request $request, $state)
    {
        $stateDetail = DB::table('states')->where('name', $state)->first();

        if (!empty($stateDetail)) {
            $cityList = DB::table('cities')->select('city_name')->where('state_id', $stateDetail->id)->get();

            if (count($cityList) > 0) {
                return response()->json([
                    'status' => 200,
                    'message' => 'City list found',
                    'data' => $cityList
                ]);
            }
        }

        return response()->json([
            'status' => 400,
            'message' => 'No records found'
        ]);
    }
}
