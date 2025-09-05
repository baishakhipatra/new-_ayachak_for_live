<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $data = Promotion::leftjoin('coupons as c','c.id','=','promotions.coupon_code_id')
                ->leftjoin('coupon_usages as cu','cu.coupon_code_id','=','promotions.coupon_code_id')
                ->leftjoin('orders as o','o.id','=','cu.order_id')
                ->select('promotions.name','promotions.email','promotions.phone', 'c.coupon_code', 'cu.order_id', 'o.order_no','c.created_at')
                ->get();
        // dd($data);
        return view('admin.promotion.index', compact('data'));
    }
}
