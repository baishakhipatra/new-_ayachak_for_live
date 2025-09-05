@extends('layouts.app')

@section('page', 'Profile')

@section('content')
<section class="cart-header mb-3 mb-sm-5">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h4>Account Information</h4>
            </div>
        </div>
    </div>
</section>

<section class="cart-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-sm-5 col-lg-3">
                <div class="name-card">
                    <h4>{{Auth::guard('web')->user()->name}}</h4>
                    <h5>{{Auth::guard('web')->user()->email}}</h5>
                    <h5>{{Auth::guard('web')->user()->mobile}}</h5>
                </div>

                <ul class="account-list">
                    <li class="{{request()->is('user/profile*') ? 'active' : '' }}">
                        <a href="{{route('front.user.profile')}}">Overview</a>
                    </li>
                    <li>
                        <span>Orders & returns</span>
                        <ul class="account-item">
                            <li class="{{(request()->is('user/order*')) ? 'active' : '' }}"><a href="{{route('front.user.order')}}">Orders</a></li>
                            {{-- <li class="{{(request()->is('user/return*')) ? 'active' : '' }}"><a href="{{route('front.user.order')}}">Returns</a></li> --}}
                        </ul>
                    </li>
                    <li>
                        <span>Credits</span>
                        <ul class="account-item">
                            <li class="{{request()->is('user/coupon*') ? 'active' : '' }}"><a href="{{route('front.user.coupon')}}">Coupons</a></li>
                        </ul>
                    </li>
                    <li>
                        <span>Account</span>
                        <ul class="account-item">
                            <li class="{{request()->is('user/manage*') ? 'active' : '' }}"><a href="{{route('front.user.manage')}}">Profile</a></li>
                            <li class="{{request()->is('user/wishlist*') ? 'active' : '' }}"><a href="{{route('front.user.wishlist')}}">Wishlist</a></li>
                            <li class="{{request()->is('user/address*') ? 'active' : '' }}"><a href="{{route('front.user.address')}}">Address</a></li>
                            <li><a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a></li>
                        </ul>
                    </li>
                    <li>
                        <span>Legal</span>
                        <ul class="account-item">
                            <li><a href="{{route('front.content.terms')}}">Terms & Conditions</a></li>
                            <li><a href="{{route('front.content.privacy')}}">Privacy Statement</a></li>
                            <li><a href="{{route('front.content.security')}}">Security</a></li>
                            <li><a href="{{route('front.content.disclaimer')}}">Disclaimer</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

            @yield('profile-content')

        </div>
    </div>
</section>
@endsection
