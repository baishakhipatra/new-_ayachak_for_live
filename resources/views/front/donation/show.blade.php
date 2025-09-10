@extends('front.layout.app')
@section('page-title', 'Donation')
@section('content')
<section class="main">
    <div class="container">
        <div class="profile-wrapper">
            <div class="row">
                <div class="col-lg-3 mb-4 mb-md-5 mb-lg-0">
                   @include('front/sidebar_profile')
                </div>
                <div class="col-lg-9">
                    <div class="profile-right">
                        <div class="profile-heading-group">
                            <h2 class="mb-0">Donation Summery</h2>
                            <a href="#" class="bton btn-fill">Download Invoice</a>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-9">
                                <div class="detail-summery">
                                    <h3 class="mb-5">Donation Details</h3>
                                    <div class="cart-row">
                                        <span>Name</span>
                                        {{ucwords($donation->full_name)}}
                                    </div>
                                    <div class="cart-row">
                                        <span>Email ID</span>
                                        {{$donation->email}}
                                    </div>
                                    <div class="cart-row">
                                        <span>Phone Number</span>
                                        {{$donation->phone_number}}
                                    </div>
                                    <div class="cart-row">
                                        <span>Pan Number</span>
                                        {{$donation->pan_number}}
                                    </div>
                                    <div class="cart-row">
                                        <span>Address</span>
                                        {{ucwords($donation->address)}}
                                    </div>
                                    <div class="cart-row">
                                        <span>Town/Village</span>
                                        {{ucwords($donation->city_village)}}
                                    </div>
                                    <div class="cart-row">
                                        <span>District</span>
                                        {{ucwords($donation->district)}}
                                    </div>
                                    <div class="cart-row">
                                        <span>State</span>
                                       {{ucwords($donation->state)}}
                                    </div>
                                    <div class="cart-row">
                                        <span>Pin Code</span>
                                        {{$donation->zipcode}}
                                    </div>
                                    <div class="cart-row">
                                        <span>Country</span>
                                        {{ucwords($donation->country)}}
                                    </div>
                                    <div class="cart-row">
                                        <span>Donation Amount Paid</span>
                                        {{$donation->amount}}
                                    </div>
                                    <div class="cart-row">
                                        <span>Donation Date</span>
                                        {{$donation->created_at}}
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