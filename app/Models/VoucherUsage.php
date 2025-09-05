<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherUsage extends Model
{
    public function userDetails() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function orderDetails() {
        return $this->belongsTo('App\Models\Order', 'order_id', 'id');
    }
}
