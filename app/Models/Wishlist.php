<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable = ['ip', 'user_id', 'product_id'];

    public function productDetails() {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }
}
