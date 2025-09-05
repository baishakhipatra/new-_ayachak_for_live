<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['order_no','ip', 'user_id', 'fname', 'lname', 'email', 'mobile', 
    'alt_mobile', 'billing_address_id', 'billing_address', 'billing_landmark', 
    'billing_country', 'billing_state', 'billing_city', 'billing_pin', 'shippingSameAsBilling',
    'shipping_address_id', 'shipping_address', 'shipping_landmark', 'shipping_country', 
    'shipping_state', 'shipping_city', 'shipping_pin', 'amount', 'tax_amount', 'discount_amount', 
    'coupon_code_id','coupon_code_discount_type', 'final_amount', 'gst_no', 'is_paid', 'txn_id','payment_method'];

    public function orderProducts() {
        return $this->hasMany('App\Models\OrderProduct', 'order_id', 'id');
    }

    public function couponDetails() {
        return $this->belongsTo('App\Models\Coupon', 'coupon_code_id', 'id');
    }

    public function transactionDetails() {
        return $this->belongsTo('App\Models\Transaction', 'id', 'order_id');
    }

    public function offerDetails() {
        return $this->belongsTo('App\Models\OrderOffer', 'id', 'order_id');
    }
}
