@extends('front.profile.layouts.app')

@section('profile-content')
<div class="col-sm-7 col-lg-7">
    <div class="row">
        <div class="col-sm-12 col-lg-6">
            <a href="{{route('front.user.manage')}}" class="account-card">
                <figure>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </span>
                </figure>
                <figcaption>
                    <h4>Profile Details</h4>
                    <h6>Change your profile details & password</h6>
                </figcaption>
            </a>
        </div>
        <div class="col-sm-12 col-lg-6">
            <a href="{{route('front.user.order')}}" class="account-card">
                <figure>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-package"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                    </span>
                </figure>
                <figcaption>
                    <h4>Orders</h4>
                    <h6>Check your order status</h6>
                </figcaption>
            </a>
        </div>
        <div class="col-sm-12 col-lg-6">
            <a href="{{route('front.user.wishlist')}}" class="account-card">
                <figure>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                    </span>
                </figure>
                <figcaption>
                    <h4>Collections & Wishlist</h4>
                    <h6>All your curated product collections</h6>
                </figcaption>
            </a>
        </div>
        <div class="col-sm-12 col-lg-6">
            <a href="{{route('front.user.address')}}" class="account-card">
                <figure>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon><line x1="8" y1="2" x2="8" y2="18"></line><line x1="16" y1="6" x2="16" y2="22"></line></svg>
                    </span>
                </figure>
                <figcaption>
                    <h4>Address</h4>
                    <h6>Save addresses for a hassle-free checkout</h6>
                </figcaption>
            </a>
        </div>
        <div class="col-sm-12 col-lg-6">
            <a href="{{route('front.user.coupon')}}" class="account-card">
                <figure>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-gift"><polyline points="20 12 20 22 4 22 4 12"></polyline><rect x="2" y="7" width="20" height="5"></rect><line x1="12" y1="22" x2="12" y2="7"></line><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path></svg>
                    </span>
                </figure>
                <figcaption>
                    <h4>Coupons</h4>
                    <h6>Manage coupons for additional discounts</h6>
                </figcaption>
            </a>
        </div>
    </div>
</div>
@endsection