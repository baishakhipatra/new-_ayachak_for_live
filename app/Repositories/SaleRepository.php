<?php

namespace App\Repositories;

use App\Interfaces\SaleInterface;
use App\Models\Product;
use App\Models\Sale;

class SaleRepository implements SaleInterface 
{
    public function listAll() 
    {
        return Sale::orderBy('position', 'ASC')->orderBy('id', 'DESC')->get();
    }
}