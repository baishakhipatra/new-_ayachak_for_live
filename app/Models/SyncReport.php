<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncReport extends Model
{
    protected $fillable = ['rfq_id', 'sku_code', 'inventory', 'status', 'api_resp'];
}
