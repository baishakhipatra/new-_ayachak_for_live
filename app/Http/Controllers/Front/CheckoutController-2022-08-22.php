<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Interfaces\CheckoutInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function __construct(CheckoutInterface $checkoutRepository) 
    {
        $this->checkoutRepository = $checkoutRepository;
    }

    public function index(Request $request)
    {
        $cartData = $this->checkoutRepository->viewCart();
        if (Auth::guard('web')->user()) {
            $addressData = $this->checkoutRepository->addressData();
        } else {
            $addressData = null;
        }

        if ($cartData) {
            return view('front.checkout.index', compact('cartData', 'addressData'));
        } else {
            return redirect()->route('front.cart.index');
        }
    }

    public function coupon(Request $request)
    {
        $couponData = $this->checkoutRepository->couponCheck($request->code);
        return $couponData;
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'email' => 'required|email|max:255',
            'mobile' => 'required|integer|digits:10',
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'billing_country' => 'required|string|max:255',
            'billing_address' => 'required|string|max:255',
            'billing_landmark' => 'nullable|string|max:255',
            'billing_city' => 'required|string|max:255',
            'billing_state' => 'required|string|max:255',
            'billing_pin' => 'required|integer|digits:6',
            'shippingSameAsBilling' => 'nullable|integer|digits:1',
            'shipping_country' => 'nullable|string|max:255',
            'shipping_address' => 'nullable|string|max:255',
            'shipping_landmark' => 'nullable|string|max:255',
            'shipping_city' => 'nullable|string|max:255',
            'shipping_state' => 'nullable|string|max:255',
            'shipping_pin' => 'nullable|integer|digits:6',
            'shipping_method' => 'required|string',
        ], [
            'mobile.*' => 'Please enter valid 10 digit mobile number',
            'billing_pin.*' => 'Please enter valid 6 digit pin',
            'shipping_pin.*' => 'Please enter valid 6 digit pin',
        ]);

        $order_no = $this->checkoutRepository->create($request->except('_token'));

        if ($order_no) {
            return redirect()->route('front.checkout.complete')->with('success', 'Order No: '.$order_no);
        } else {
            $request->shippingSameAsBilling = 0;
            return redirect()->back()->with('failure', 'Something happened. Try again.')->withInput($request->all());
        }
    }
}
