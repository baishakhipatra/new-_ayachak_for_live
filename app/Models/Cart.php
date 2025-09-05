<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use App\Models\{ProductVariation,Product,Coupon,ProductColorSize};

class Cart extends Model
{
    protected $fillable = ['ip','user_id', 'product_id', 'product_name', 'product_image', 'product_slug', 'product_variation_id', 'price', 'offer_price', 'qty'];

    public function cartVariationDetails() {
        return $this->belongsTo('App\Models\ProductColorSize', 'product_variation_id', 'id');
    }

    public function couponDetails() {
        return $this->belongsTo('App\Models\Coupon', 'coupon_code_id', 'id');
    }

    public function productDetails() {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }

    public function variation() {
        return $this->belongsTo('App\Models\ProductVariation', 'product_variation_id');
    }
}
