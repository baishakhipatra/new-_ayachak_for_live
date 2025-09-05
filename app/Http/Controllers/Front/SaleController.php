<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Interfaces\SaleInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SaleController extends Controller
{
    public function __construct(SaleInterface $saleRepository) 
    {
        $this->saleRepository = $saleRepository;
    }

    public function index(Request $request)
    {
        $data = $this->saleRepository->listAll();

        if ($data) {
            return view('front.sale.index', compact('data'));
        } else {
            return view('front.404');
        }
    }
}
