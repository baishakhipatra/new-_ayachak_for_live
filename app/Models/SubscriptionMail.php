<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionMail extends Model
{
    protected $fillable = ['email', 'count'];
}
