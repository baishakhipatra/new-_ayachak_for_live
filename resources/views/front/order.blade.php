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
                                <a href="{{ route('front.order.invoice', $checkout->order_no) }}" class="bton btn-fill" target="_blank">Download Invoice</a>
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
                                                                <div class="pro-meta">
                                                                    <span>Status:</span> 
                                                                    @if ($item->status == 1) <span class="text-primary">Processing</span>
                                                                    @elseif ($item->status == 2) <span class="text-primary">Confirmed</span>
                                                                    @elseif ($item->status == 3) <span class="text-primary">Shipped</span>
                                                                    @elseif ($item->status == 4) <span class="text-success">Delivered</span>
                                                                    @elseif ($item->status == 5) <span class="text-danger">Cancelled</span>
                                                                    @endif
                                                                </div>

                                                                @if(!empty($item->productDetails->variation->weight))
                                                                    <div class="pro-meta">
                                                                        <span>Weight:</span> 
                                                                        {{ $item->productDetails->variation->weight }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <span class="cart-price">
                                                                ₹{{ number_format((!empty($item->offer_price) && $item->offer_price > 0 
                                                                    ? $item->offer_price 
                                                                    : $item->price) * $item->qty, 2) }}
                                                            </span>
                                                        </figcaption>
                                                    </div>

                                                    {{-- Cancel button for each product --}}
                                                    @if($checkoutProducts->count() > 1 && !in_array($item->status, [3,4,5]))
                                                        <button type="button" 
                                                                class="btn btn-warning btn-sm mt-2" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#cancelProductModal-{{ $item->id }}">
                                                            Cancel This Product
                                                        </button>
                                                    @endif
                                                </li>

                                                {{-- Modal for product cancel --}}
                                                <div class="modal fade" id="cancelProductModal-{{ $item->id }}" tabindex="-1" aria-labelledby="cancelProductModalLabel-{{ $item->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="cancelProductModalLabel-{{ $item->id }}">Cancel Product: {{ ucwords($item->productDetails->name) }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ route('front.product.cancel') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="productId" value="{{ $item->id }}">
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label for="cancellationReason" class="form-label">Cancellation Reason</label>
                                                                        <textarea name="cancellationReason" class="form-control" rows="3" required></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-danger">Confirm Cancel</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </ul>
                                        @php
                                            $allCancelled = $checkoutProducts->every(fn($item) => $item->status == 5);
                                            $allShippedOrDelivered = $checkoutProducts->every(fn($item) => in_array($item->status, [3,4]));
                                        @endphp

                                        @if(!$allCancelled && !$allShippedOrDelivered)
                                            <button type="button" class="btn btn-danger mb-3" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                                                Cancel Entire Order
                                            </button>
                                        @elseif($allCancelled)
                                            <p class="text-danger mt-3">This order has been cancelled.</p>
                                        @else
                                            <p class="text-success mt-3">This order cannot be cancelled (already shipped).</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Whole Order Cancel Modal --}}
                            <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="cancelOrderModalLabel">Cancel Entire Order</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('front.order.cancel') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="orderId" value="{{ $checkoutProducts->first()->order_id ?? '' }}">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="cancellationReason" class="form-label">Cancellation Reason</label>
                                                    <textarea name="cancellationReason" class="form-control" rows="3" required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-danger">Confirm Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>


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

                                        <div class="cart-total">
                                            <span>
                                                Total
                                            </span>
                                            ₹{{ number_format($checkout->final_amount, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                        <div class="cart-row">
                                            <span>Deliver to</span>
                                            <div class="address">
                                                @if($checkout->shippingSameAsBilling)
                                                    {{-- If shipping = billing --}}
                                                    {{ ucwords($checkout->billing_address) }},
                                                    {{ ucwords($checkout->billing_city) }},
                                                    {{ ucwords($checkout->billing_state) }},
                                                    {{ ucwords($checkout->billing_country) }}
                                                    {{ $checkout->billing_pin }}
                                                    <br><strong>Landmark:</strong> {{ ucwords($checkout->billing_landmark) }}
                                                    <br><strong>Phone:</strong> {{ $checkout->mobile }}
                                                @else
                                                    {{-- Show billing address --}}
                                                    <strong>Billing:</strong> 
                                                    {{ ucwords($checkout->billing_address) }},
                                                    {{ ucwords($checkout->billing_city) }},
                                                    {{ ucwords($checkout->billing_state) }},
                                                    {{ ucwords($checkout->billing_country) }} - {{ $checkout->billing_pin }}
                                                    <br><strong>Landmark:</strong> {{ ucwords($checkout->billing_landmark) }}
                                                    <br><strong>Phone:</strong> {{ $checkout->mobile }}

                                                    <br><br>
                                                    {{-- Show shipping address --}}
                                                    <strong>Shipping:</strong> 
                                                    {{ ucwords($checkout->shipping_address) }},
                                                    {{ ucwords($checkout->shipping_city) }},
                                                    {{ ucwords($checkout->shipping_state) }},
                                                    {{ ucwords($checkout->shipping_country) }} - {{ $checkout->shipping_pin }}
                                                    <br><strong>Landmark:</strong>
                                                    {{ $checkout->shipping_landmark ? ucwords($checkout->shipping_landmark) : ucwords($checkout->billing_landmark) }}
                                                    @if($checkout->alt_mobile)
                                                        <br><strong>Alternative Phone:</strong> {{ $checkout->alt_mobile }}
                                                    @endif
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            @php
                                $progressWidth = match(true) {
                                    $checkout->status == 1 => '0%',
                                    $checkout->status == 2 => '25%',
                                    $checkout->status == 3 => '55%',
                                    $checkout->status == 4 => '85%',
                                    $checkout->status == 5 => '85%',
                                    default => '0%',
                                };
                            @endphp
                            <div class="row mb-2">
                                <div class="col-lg-9">
                                    <div class="detail-summery">
                                        <h3 class="mb-5">Order Tracking</h3>
                                        <div class="tracking-wrap">
                                            <ul>
                                                <div class="progress-active" style="width: {{ $progressWidth }};"></div>
                                                <li class="{{ $checkout->status >= 1 ? 'active' : '' }} active"><span>Processing</span></li>
                                                @if($checkout->status != 5)
                                                <li class="{{ $checkout->status >= 2 ? 'active' : '' }} "><span>Packing</span></li>
                                                <li class="{{ $checkout->status >= 3 ? 'active' : '' }} "><span>Shipping</span></li>
                                                <li class="{{ $checkout->status == 4 ? 'active' : '' }} "><span>Delivered</span></li>
                                                @else
                                                <li class="{{ $checkout->status == 5 ? 'danger' : '' }} "><span>Cancelled</span></li>
                                                @endif
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