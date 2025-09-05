<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderRemark extends Model
{
    protected $table = 'order_remarks';

    public function orderDetail() {
        return $this->belongsTo('\App\Models\Order', 'order_id', 'id');
    }
}
