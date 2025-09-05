<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderOffer extends Model
{
    protected $table = 'order_offers';

    public function orderDetail() {
        return $this->belongsTo('\App\Models\Order', 'order_id', 'id');
    }
}
