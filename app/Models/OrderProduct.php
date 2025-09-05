<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $fillable = ['order_id', 'product_id', 'product_name', 'product_image', 'product_slug', 'product_variation_id', 'price', 'offer_price', 'qty',
                            'colour_name','size_name','sku_code','coupon_code','gst','gst_amount','total','status',
                        'return_reason_type', 'return_reason_comment' , 'admin_return_approval_status', 'expected_return_delivery'];

    public function orderDetails() {
        return $this->belongsTo('App\Models\Order', 'order_id', 'id');
    }

    public function productDetails() {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }

    public function productVariationDetails() {
        return $this->belongsTo('App\Models\ProductVariation', 'product_variation_id', 'id');
    }
}

