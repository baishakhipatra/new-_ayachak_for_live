@extends('front.layout.app')
   @section('content')
   <section class="profile_sec">
        <div class="container">
            <div class="profile_h2">
                <h4>Account Information</h4>
            </div>
            <div class="row">
                <div class="col-sm-5 col-lg-3">
                    <!-- <div class="profile_name">
                        <h4>Lux</h4>
                        <h5>Example@gmail.com</h5>
                        <h5>1234567890</h5>
                    </div> -->
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
                            <h3>Order Details</h3>
                            @if($data)
                                @foreach($data as $item)
                                <div class="all_order_parent_p">
                                    <div class="order_img_a">
                                        <a href="{{route('front.product.details',$item->product_slug)}}">
                                            <h6>{{$item->product_name}}</h6>
                                            <div class="order_img">
                                                <img src="{{asset($item->product_image)}}" width="20%" alt="no-image">
                                            </div>
                                        </a>
                                    </div>
                                    <div class="order_img_text">
                                        <ul class="order_img_text_ul">
                                            <li>Style no:<span>NC 431</span></li>
                                            <li>Qty :<span>{{$item->qty}}</span></li>
                                            @if($item->price != $item->offer_price)
                                            @if($item->offer_price>0)
                                                <li>Price: ₹{{$item->offer_price}}<span><del>₹{{$item->price}}</del></span></li>
                                                @else
                                                <li>Price: ₹{{ $item->price }}</li>
                                                @endif
                                            @else
                                            <li>Price: ₹{{ $item->offer_price }}</li>         
                                            @endif
                                            <li>Color :<span>{{$item->colour_name}}</span></li>
                                            <li>Size :<span>{{$item->size_name}}</span></li>
                                            <li>SKU :<span>{{$item->sku_code}}</span></li>
                                        </ul>
                                    </div>
                                    <div class="order_img_text_btn">
                                     @if($item->status == 1)
                                        <button class="order_img_text_buton">Order placed</button>
                                        @elseif($item->status == 2)
                                        <button class="order_img_text_buton">Order confirmed</button>
                                        @elseif($item->status == 3)
                                        <button class="order_img_text_buton">Order Shipped</button>
                                        @elseif($item->status == 4)
                                        <button class="order_img_text_buton">Order Delivered</button>
                                        @elseif($item->status == 5)
                                        <button class="order_img_text_buton">Order cancelled</button>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            @endif
                               <div class="all_order_child3">
                                <div class="row align-items-center">
                                    <div class="col-lg-8">
                                        <div class="order_summary_sec">
                                            
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="oder_summary">
                                            <div class="oder_summary_text">
                                                <h4>Order Summary</h4>
                                            </div>
                                              <ul class="oder_summary_ul">
                                                <li>
                                                    <h6>Subtotal</h6>
                                                    <h5>&#8377;{{$order[0]->amount}}</h5>
                                                </li>
                                                <li>
                                                    <h6>Discount</h6>
                                                    <h5>&#8377;{{$order[0]->discount_amount}}</h5>
                                                </li>
                                                <li>
                                                    <h6>Tax</h6>
                                                    <h5>&#8377;{{$order[0]->tax_amount}}</h5>
                                                </li>
                                                <li>
                                                    <h6>Total</h6>
                                                    <h5>&#8377;{{$order[0]->final_amount}}</h5>
                                                </li>
                                              </ul>  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

   @endsection
   
   @section('script')

   @endsection