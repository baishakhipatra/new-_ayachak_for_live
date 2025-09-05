@extends('layouts.app')
@section('page', 'Order Complete')
@section('content')

<section class="main">
    <div class="container">
        <div class="order-complete-stack">
            <figure>
                <img src="./assets/images/check.svg" alt="">
            </figure>

            <h2 class="section-heading">Thank you for your purchase</h2>
            <p>We’ve received your order will ship in 5 - 7 business days. <span>Your Order number is {{$order->order_no}}</span></p>
            <div class="summery-stack">
                <h4>Order Summery</h4>
                <div class="summery-list">
                    <ul class="cart-item-list">
                        @foreach($order->orderProducts as $item)
                            <li>
                                <div class="inner-wrap">
                                    <figure>
                                        <img src="{{ $item->product_image 
                                            ? asset($item->product_image) 
                                            : asset('assets/images/placeholder-product.jpg') }}" 
                                            alt="">
                                    </figure>
                                    <figcaption>
                                        <div class="product-details-cart">
                                            <a href="#">
                                                <h3>{{ ucwords($item->product_name) ?? 'No Name' }}</h3>
                                            </a>
                                            <div class="pro-meta">
                                                <span>Category:</span> {{ $item->productDetails->category->name ?? 'N/A' }}
                                            </div>
                                            @if(!empty($item->productDetails->weight))
                                                <div class="pro-meta">
                                                    <span>Weight:</span> {{ $item->productDetails->weight }}
                                                </div>
                                            @endif
                                        </div>
                                        <span class="cart-price">Qty: {{ $item->qty }}</span>
                                        <span class="cart-price">₹{{ number_format($item->total, 2) }}</span>
                                    </figcaption>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="cart-total">
                    <span>Subtotal</span> ₹{{ number_format($order->amount, 2) }}
                </div>
                <div class="cart-total">
                    <span>Discount</span> ₹{{ number_format($order->discount_amount, 2) }}
                </div>
                <div class="cart-total">
                    <span>Tax</span> ₹{{ number_format($order->tax_amount, 2) }}
                </div>
                <div class="cart-total">
                    <span>Total</span> ₹{{ number_format($order->final_amount, 2) }}
                </div>
            </div>
        </div>
    </div>
</section>


@endsection