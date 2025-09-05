<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncRfq extends Model
{
    protected $fillable = ['ip', 'start_time', 'end_time'];
}
