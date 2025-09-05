<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['product_id', 'position'];

    public function productDetails() {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }
}
