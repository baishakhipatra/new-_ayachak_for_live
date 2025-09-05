<?php

namespace App\Interfaces;

interface CheckoutInterface 
{
    public function viewCart();
    public function addressData();
    public function create(array $data);
}