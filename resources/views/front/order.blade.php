@extends('front.layout.app')
@section('page-title', 'Order Details')
@section('content')

<section class="main">
    <div class="container">
        <div class="profile-wrapper">
            <div class="row">
                <div class="col-lg-3 mb-4 mb-md-5 mb-lg-0">
                    @include('front/sidebar_profile')
                </div>
                @if($checkout)
                    <div class="col-lg-9">
                        <div class="profile-right">
                            <div class="profile-heading-group">
                                <h2 class="mb-0">Order Summery</h2>
                                <a href="{{ route('front.order.invoice', $checkout->id) }}" class="bton btn-fill">Download Invoice</a>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="summery-list">
                                        <ul class="cart-item-list">
                                                @foreach($checkoutProducts as $item)
                                                    <li>
                                                        <div class="inner-wrap">
                                                            <figure>
                                                                <img src="{{ $item->productDetails->image 
                                                                            ? asset($item->productDetails->image) 
                                                                            : asset('assets/images/placeholder-product.jpg') }}" 
                                                                    alt="{{ $item->productDetails->name }}">
                                                            </figure>
                                                            <figcaption>
                                                                <div class="product-details-cart">
                                                                    <a href="#">
                                                                        <h3>{{ ucwords($item->productDetails->name) }}</h3>
                                                                    </a>
                                                                    <div class="pro-meta">
                                                                        <span>Category:</span> 
                                                                        {{ $item->productDetails->category->name ?? 'N/A' }}
                                                                    </div>

                                                                    <div class="pro-meta">
                                                                        <span>Quantity:</span> 
                                                                        {{ $item->qty ?? 'N/A' }}
                                                                    </div>

                                                                    @if(!empty($item->productDetails->variation->weight))
                                                                        <div class="pro-meta">
                                                                            <span>Weight:</span> 
                                                                            {{ $item->productDetails->variation->weight }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                {{-- <span class="cart-price">
                                                                    ₹{{ number_format($item->offer_price * $item->qty, 2) }}
                                                                </span> --}}
                                                                <span class="cart-price">
                                                                    ₹{{ number_format((!empty($item->offer_price) && $item->offer_price > 0 
                                                                        ? $item->offer_price 
                                                                        : $item->price) * $item->qty, 2) }}
                                                                </span>
                                                            </figcaption>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            {{-- @endif --}}
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            {{-- @if($checkout) --}}
                            <div class="row mb-2">
                                <div class="col-lg-9">
                                    <div class="detail-summery">
                                        <h3 class="mb-5">Billing Details</h3>

                                        <div class="cart-row">
                                            <span>Subtotal</span>
                                            ₹{{ number_format($checkout->amount, 2) }}
                                        </div>

                                        @if($checkout->discount_amount > 0)
                                            <div class="cart-row">
                                                <span>Discount</span>
                                                - ₹{{ number_format($checkout->discount_amount, 2) }}
                                            </div>
                                        @endif

                                        <div class="cart-row">
                                            <span>Shipping</span>
                                            FREE
                                        </div>

                                        <div class="cart-row">
                                            <span>GST</span>
                                            ₹{{ number_format($checkout->tax_amount, 2) }}
                                        </div>

                                        <div class="cart-total">
                                            <span>
                                                Total
                                            </span>
                                            ₹{{ number_format($checkout->final_amount, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- @else
                                <p>No checkout found for this user.</p>
                            @endif --}}

                            <div class="row mb-2">
                                <div class="col-lg-9">
                                    <div class="detail-summery">
                                        <h3 class="mb-5">Order Details</h3>
                                        <div class="cart-row">
                                            <span>Order ID</span>
                                            {{ $checkout->order_no }}
                                        </div>
                                        <div class="cart-row">
                                            <span>Payment</span>
                                            {{ $checkout->payment_method }}
                                        </div>
                                        {{-- <div class="cart-row">
                                            <span>Deliver to</span>
                                            <div class="address"></div>
                                        </div> --}}
                                        <div class="cart-row">
                                            <span>Deliver to</span>
                                            <div class="address">
                                                @if($checkout->shippingSameAsBilling)
                                                    {{-- If shipping = billing --}}
                                                    {{ $checkout->billing_address }},
                                                    {{ $checkout->billing_city }},
                                                    {{ $checkout->billing_state }},
                                                    {{ $checkout->billing_country }}
                                                    {{ $checkout->billing_pin }}
                                                    <br><strong>Phone:</strong> {{ $checkout->mobile }}
                                                @else
                                                    {{-- Show billing address --}}
                                                    <strong>Billing:</strong> 
                                                    {{ $checkout->billing_address }},
                                                    {{ $checkout->billing_city }},
                                                    {{ $checkout->billing_state }},
                                                    {{ $checkout->billing_country }} - {{ $checkout->billing_pin }}
                                                    <br><strong>Phone:</strong> {{ $checkout->mobile }}

                                                    <br><br>
                                                    {{-- Show shipping address --}}
                                                    <strong>Shipping:</strong> 
                                                    {{ $checkout->shipping_address }},
                                                    {{ $checkout->shipping_city }},
                                                    {{ $checkout->shipping_state }},
                                                    {{ $checkout->shipping_country }} - {{ $checkout->shipping_pin }}
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-9">
                                    <div class="detail-summery">
                                        <h3 class="mb-5">Order Tracking</h3>
                                        <div class="tracking-wrap">
                                            <ul>
                                                <li class="active"><span>Processing</span></li>
                                                <li><span>Packing</span></li>
                                                <li><span>Shipping</span></li>
                                                <li><span>Delivered</span></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                @else
                    <div class="d-flex justify-content-center align-items-center">
                        <p class="text-muted fs-5">You don’t have any orders yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection



















































{{-- @extends('front.layout.app')
   @section('content')

   <section class="profile_sec">
        <div class="container">
            <div class="profile_h2">
                <h4>Account Information</h4>
            </div>
            <div class="row">
                <div class="col-sm-5 col-lg-3">
                    <div class="profile_details">
                    <ul class="account-list">
                            <li>
                                <a href="{{route('front.user.profile')}}">Profile</a>
                            </li>
                            <li>
                                    <a href="{{route('front.user.order')}}">My Orders</a>
                            </li>
                            <li>
                                    <a href="{{route('front.wishlist.index')}}">My Wishlist</a>
                            </li>
                            <li>
                                <span>Credits</span>
                                <ul class="account-item">
                                    <li><a href="{{route('front.user.coupon')}}">Coupons</a></li>
                                </ul>
                            </li>
                            <li class="">
                                <span>Account</span>
                                <ul class="account-item">
                                    <li><a href="{{route('front.user.profile')}}">Profile</a></li>
                                    <li><a href="{{route('front.wishlist.index')}}">Wishlist</a></li>
                                    <li><a href="#">Address</a></li>
                                    <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" >Logout</a></li>
                                </ul>
                            </li>
                            <li>
                                <span>Legal</span>
                                <ul class="account-item">
                                    <li><a href="#">Terms &amp; Conditions</a></li>
                                    <li><a href="#">Privacy Statement</a></li>
                                    <li><a href="#">Security</a></li>
                                    <li><a href="#">Disclaimer</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-7 col-lg-9">
                    <div class="profile_info">
                        <div class="profile_info_box">
                            <h3>All Orders</h3>
                            <div class="all_order_parent">
                                <div class="all_order_child">
                                    <ul class="all_order_ul">
                                        <li class="all_order_li_name">
                                            <h5>#SR No</h5>
                                        </li>
                                        <li class="all_order_li_code">
                                            <h5>Order Number</h5>
                                        </li>
                                        <li class="all_order_li_quantity">
                                            <h5>Product Quantity</h5>
                                        </li>
                                        <li class="all_order_li_price">
                                            <h5>Price</h5>
                                        </li>
                                        <li class="all_order_li_viiew">
                                            <h5>View</h5>
                                        </li>
                                    </ul>
                                </div>
                                @if($data)
                                    @foreach($data as $item)
                                    <div class="all_order_child2">
                                        <ul class="all_order_product_ul">
                                            <li class=" all_order_li_name all_order_li_c">
                                                <span class="mobile_product">
                                                    <h5>#SR No:</h5>
                                                </span>
                                                <h5>{{$loop->index+1}}</h5>
                                            </li>
                                            <li class="all_order_li_code all_order_li_c">
                                                <span class="mobile_product">
                                                    <h5>Order Number:</h5>
                                                </span>
                                                <h5>{{$item->order_no}}</h5>
                                            </li>
                                            <li class="all_order_li_quantity all_order_li_c"><span class="mobile_product">
                                                    <h5>Product Quantity:</h5>
                                                </span><span class="order_product_quantity">{{count($item->orderProducts)}}</span>
                                            </li>
                                            <li class="all_order_li_price all_order_li_c"><span class="mobile_product">
                                                    <h5>Price:</h5>
                                                </span><span class="order_product_price">&#8377;{{$item->final_amount}}</span>
                                            </li>
                                            <li class="all_order_li_viiew all_order_li_c"><span class="mobile_product">
                                                    <h5>View:</h5>
                                                </span><a href="{{route('front.user.order.details',$item->id)}}" class="order_product_viwe"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#858585" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a></li>
                                        </ul>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endsection
   
   @section('script')

   @endsection --}}