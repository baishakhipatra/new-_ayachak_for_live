<?php

namespace App\Interfaces;

interface CartInterface 
{
    public function couponCheck($coupon_code);
    public function couponRemove();
    public function addToCart(array $data);
    public function viewByIp();
    public function viewByUserId($id);
    public function viewBytoken($token);
    public function delete($id);
    public function empty();
    public function qtyUpdate($id, $type);
}