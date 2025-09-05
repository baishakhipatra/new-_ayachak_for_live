<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Interfaces\FaqInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
// use App\Models\Faq;

class FaqController extends Controller
{
    public function __construct(FaqInterface $faqRepository) 
    {
        $this->faqRepository = $faqRepository;
    }

    public function index(Request $request)
    {
        $data = $this->faqRepository->listAll();

        if ($data) {
            return view('front.faq.index', compact('data'));
        } else {
            return view('front.404');
        }
    }
}
