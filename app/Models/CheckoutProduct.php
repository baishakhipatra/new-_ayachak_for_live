<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckoutProduct extends Model
{
    use HasFactory;
    protected $table = "checkout_products";
    protected $fillable = [
        'checkout_id', 'product_id', 'user_id', 'product_name', 'product_image', 'product_slug', 
        'product_variation_id', 'colour_name', 'size_name', 'sku_code', 'coupon_code', 'price', 
        'offer_price', 'gst', 'qty', 'status', 'return_reason_type', 'return_reason_comment', 
        'admin_return_approval_status', 'expected_return_delivery'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Variation relationship (if needed)
    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'product_variation_id');
    }
}
