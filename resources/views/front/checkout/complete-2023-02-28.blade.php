@extends('layouts.app')

@section('page', 'Order Complete')

@section('content')

{{-- @if(Session::get('success')) --}}
    <section class="cart-header mb-3 mb-sm-5"></section>
    <section class="cart-wrapper">
        <div class="container">
            <div class="complele-box">
                <figure>
                    <img src="{{asset('img/box.png')}}">
                </figure>
                <figcaption>
                    <h2>Your order is complete</h2>
                    <p>{{Session::get('success')}}</p>
                    <p>You will receive an email confirmation shortly.</p>
                    <a href="{{route('front.user.order')}}">View all orders</a>
                </figcaption>
            </div>
        </div>
    </section>
{{-- @else
    <script>window.location = "{{route('front.home')}}";</script>
@endif --}}

@endsection

@section('script')
    <script>
        @php
            $orderData = \App\Models\Order::where('order_no', $order_no)->first();
            $orderProductsData = \App\Models\OrderProduct::where('order_id', $orderData->id)->get();

            $couponData = [];
            if($orderData->coupon_code_id != 0) {
                $couponData = \App\Models\Coupon::where('id', $orderData->coupon_code_id)->first();
            }
        @endphp

        $(window).on('load', function() {
            gtag("event", "purchase", {
                transaction_id: "",
                value: {{(int) $orderData->final_amount}},
                tax: {{(int) $orderData->tax_amount}},
                shipping: {{(int) $orderData->shipping_charges}},
                currency: "INR",
                coupon: "{{(!empty($couponData)) ? $couponData->coupon_code : ''}}",
                items: [
                    @foreach($orderProductsData as $item)
                    {
                        item_id: "{{$item->sku_code}}",
                        item_name: "{{$item->product_name}}",
                        coupon: "{{(!empty($couponData)) ? $couponData->coupon_code : ''}}",
                        discount: {{$item->price - $item->offer_price}},
                        index: 0,
                        item_brand: "ONN",
                        item_category: "{{$item->productDetails->category->name}}",
                        item_variant: "{{$item->productVariationDetails->colorDetails->name}}",
                        price: {{$item->offer_price}},
                        quantity: {{$item->qty}}
                    }@if(!$loop->last),@endif
                    @endforeach
                ]
            });
        });
    </script>
@endsection