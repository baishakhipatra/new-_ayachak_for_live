<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    protected $fillable = ['coupon_code_id', 'coupon_code', 'discount', 'total_checkout_amount', 'final_amount', 'user_id', 'email', 'ip', 'order_id', 'usage_time'];

    public function userDetails() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function orderDetails() {
        return $this->belongsTo('App\Models\Order', 'order_id', 'id');
    }
}
