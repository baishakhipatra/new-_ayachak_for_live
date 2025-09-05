<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    use HasFactory;
    protected $table = "checkout";

    protected $fillable = [
        'user_id', 'sub_total_amount', 'discount_amount', 'gst_amount', 'final_amount','coupon_id'
    ];

    public function coupon(){
        return $this->belongsTo('App\Models\Coupon', 'coupon_code_id','id');
    }
}
