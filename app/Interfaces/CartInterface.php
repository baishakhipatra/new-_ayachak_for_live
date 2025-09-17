<?php

namespace App\Interfaces;

interface CartInterface 
{
    public function couponCheck($coupon_code);
    public function couponRemove();
}