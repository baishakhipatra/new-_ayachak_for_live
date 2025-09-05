@extends('front.layout.app')
@section('page-title', 'Account Details')
@section('content')

<section class="main">
    <div class="container">
        <div class="profile-wrapper">
            <div class="row">
                <div class="col-lg-3">
                @include('front/sidebar_profile')
                </div>
                <div class="col-lg-9">
                    <div class="profile-right">
                        <h2>Personal Information</h2>
                        <div class="row">
                            <div class="col-lg-8">
                                <form action="{{ route('front.manage.update') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group"> 
                                                <input type="text" class="form-control input-style" placeholder=" " name="fname" id="fname"
                                                value="{{ucwords(Auth::guard('web')->user()->fname)}}" required>
                                                <label class="placeholder-text">First Name</label>
                                                @error('fname')
                                                    <p class="small text-danger">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group"> 
                                                <input type="text" class="form-control input-style" placeholder=" " name="lname" id="lname"
                                                value="{{ucwords(Auth::guard('web')->user()->lname)}}" required>
                                                <label class="placeholder-text">Last Name</label>
                                                @error('lname')
                                                    <p class="small text-danger">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group"> 
                                        <input type="tel" class="form-control input-style" placeholder="Mobile No" name="mobile" id="mobile"
                                        value="{{Auth::guard('web')->user()->mobile}}" required>
                                        <label class="placeholder-text">Phone Number</label>
                                        @error('mobile')
                                        <p class="small text-danger">{{$message}}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group"> 
                                        <input type="email" class="form-control input-style" placeholder="Email Address" name="email" id="email"
                                        value="{{Auth::guard('web')->user()->email}}" required>
                                        <label class="placeholder-text">Email ID</label>
                                        @error('email')
                                            <p class="small text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <input type="hidden" name="id" value="{{Auth::guard('web')->user()->id}}"/>
                                    <input type="submit" class="bton btn-fill" value="Save Changes">
                                </form>
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

  <script>
    $( function() {
        // const rangeInput = document.querySelectorAll(".range-input input"),
        // priceInput = document.querySelectorAll(".price-input input"),
        // range = document.querySelector(".slider .progress");
        // let priceGap = 1000;

        // priceInput.forEach((input) => {
        // input.addEventListener("input", (e) => {
        //     let minPrice = parseInt(priceInput[0].value),
        //     maxPrice = parseInt(priceInput[1].value);

        //     if (maxPrice - minPrice >= priceGap && maxPrice <= rangeInput[1].max) {
        //     if (e.target.className === "input-min") {
        //         rangeInput[0].value = minPrice;
        //         range.style.left = (minPrice / rangeInput[0].max) * 100 + "%";
        //     } else {
        //         rangeInput[1].value = maxPrice;
        //         range.style.right = 100 - (maxPrice / rangeInput[1].max) * 100 + "%";
        //     }
        //     }
        // });
        // });

        // rangeInput.forEach((input) => {
        // input.addEventListener("input", (e) => {
        //     let minVal = parseInt(rangeInput[0].value),
        //     maxVal = parseInt(rangeInput[1].value);

        //     if (maxVal - minVal < priceGap) {
        //     if (e.target.className === "range-min") {
        //         rangeInput[0].value = maxVal - priceGap;
        //     } else {
        //         rangeInput[1].value = minVal + priceGap;
        //     }
        //     } else {
        //     priceInput[0].value = minVal;
        //     priceInput[1].value = maxVal;
        //     range.style.left = (minVal / rangeInput[0].max) * 100 + "%";
        //     range.style.right = 100 - (maxVal / rangeInput[1].max) * 100 + "%";
        //     }
        // });
        // });


    
    } );

    // quantity jquery
    // document.addEventListener("DOMContentLoaded", () => {
    //     const input = document.getElementById("quantity");
    //     document.querySelector(".increment").addEventListener("click", (e) => {
    //         e.preventDefault(); // Prevent form submission
    //         input.stepUp();
    //     });
    //     document.querySelector(".decrement").addEventListener("click", (e) => {
    //         e.preventDefault(); // Prevent form submission
    //         input.stepDown();
    //     });
    // });


    document.addEventListener("DOMContentLoaded", () => {
        // Handle increment buttons
        document.querySelectorAll(".increment").forEach(button => {
            button.addEventListener("click", (e) => {
                e.preventDefault();
                const input = button.closest('.number-input').querySelector(".quantity");
                input.stepUp();
            });
        });

        // Handle decrement buttons
        document.querySelectorAll(".decrement").forEach(button => {
            button.addEventListener("click", (e) => {
                e.preventDefault();
                const input = button.closest('.number-input').querySelector(".quantity");
                input.stepDown();
            });
        });
    });
    
  </script>

@endsection



























{{-- @extends('front.layout.app')
   @section('content')

    <section class="profile_sec">
        <div class="container">
            <div class="profile_h2">
                <h4>Account Information</h4>
            </div>
            
                    <div class="col-lg-10 col-md-8 col-12">
                        @if (Session::get('success'))
                            <div class="alert alert-success">{{Session::get('success')}}</div>
                        @endif
                        @if (Session::get('failure'))
                            <div class="alert alert-danger">{{Session::get('failure')}}</div>
                        @endif
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
                                <a href="{{route('front.profile')}}">Profile</a>
                            </li>
                            <li>
                                    <a href="{{route('front.order')}}">My Orders</a>
                            </li>
                            <li>
                                    <a href="{{route('front.wishlist.index')}}">My Wishlist</a>
                            </li>
                            <li>
                                <span>Credits</span>
                                <ul class="account-item">
                                    <li><a href="{{route('front.coupon')}}">Coupons</a></li>
                                </ul>
                            </li>
                            <li class="">
                                <span>Account</span>
                                <ul class="account-item">
                                    <li><a href="{{route('front.profile')}}">Profile</a></li>
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
                <div class="col-sm-7">
                    <div class="profile_info">
                        <form action="{{ route('front.manage.update') }}" method="post">
                            @csrf
                            <div class="profile_info_box">
                                <h3>Edit Profile</h3>
                                <div class="row">
                                    <div class="col-sm-12 col-lg-6">
                                        <div class="from_group">
                                            <input type="text" class="form-control from_group_in" placeholder="First Name" name="fname"
                                                value="{{Auth::guard('web')->user()->fname}}" required>
                                            @error('fname')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="col-sm-12 col-lg-6">
                                        <div class="from_group">
                                            <input type="text" class="form-control from_group_in" placeholder="Last Name" name="lname"
                                                value="{{Auth::guard('web')->user()->lname}}" required>
                                                @error('lname')
                                                <p class="small text-danger">{{$message}}</p>
                                                @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-lg-6">
                                        <div class="from_group">
                                            <input type="email" class="form-control from_group_in" placeholder="Email Address" name="email"
                                                value="{{Auth::guard('web')->user()->email}}" required>
                                                @error('email')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-lg-6">
                                        <div class="from_group">
                                            <input type="tel" class="form-control from_group_in" placeholder="Mobile No" name="mobile"
                                                value="{{Auth::guard('web')->user()->mobile}}" required>
                                                @error('mobile')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="id" value="{{Auth::guard('web')->user()->id}}"/>
                                <div class="profile_info_button">
                                    <button type="submit" class="btn checkout-btn">Update Details</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--<div class="profile_info">-->
                    <!--    <form action="{{route('front.password.update')}}" method="post">-->
                    <!--        @csrf-->
                    <!--        <div class="profile_info_box">-->
                    <!--            <h3>Change Password</h3>-->
                    <!--            <div class="row">-->
                    <!--                <div class="col-sm-12 col-lg-6">-->
                    <!--                    <div class="from_group">-->
                    <!--                        <input type="password" class="form-control from_group_in" name="old_password"-->
                    <!--                            placeholder="Old Password" required>-->
                    <!--                        @error('old_password')-->
                    <!--                            <p class="small text-danger">{{$message}}</p>-->
                    <!--                        @enderror-->
                    <!--                    </div>-->
                    <!--                </div>-->
                    <!--            </div>-->
                    <!--            <div class="row">-->
                    <!--                <div class="col-sm-12 col-lg-6">-->
                    <!--                    <div class="from_group">-->
                    <!--                        <input type="password" class="form-control from_group_in" name="new_password"-->
                    <!--                            placeholder="New Password" required>-->
                    <!--                        @error('new_password')-->
                    <!--                            <p class="small text-danger">{{$message}}</p>-->
                    <!--                        @enderror-->
                    <!--                    </div>-->
                    <!--                </div>-->
                    <!--                <div class="col-sm-12 col-lg-6">-->
                    <!--                    <div class="from_group">-->
                    <!--                        <input type="password" class="form-control from_group_in" name="confirm_password"-->
                    <!--                            placeholder="Confirm Password" required>-->
                    <!--                        @error('confirm_password')-->
                    <!--                            <p class="small text-danger">{{$message}}</p>-->
                    <!--                        @enderror-->
                    <!--                    </div>-->
                    <!--                </div>-->
                    <!--            </div>-->
                    <!--            <input type="hidden" name="id" value="{{Auth::guard('web')->user()->id}}">-->
                    <!--            <div class="profile_info_button">-->
                    <!--                <button type="submit" class="btn checkout-btn">Update Password</button>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </form>-->
                    <!--</div>-->
                </div>
            </div>
        </div>
    </section>
    @endsection
   
   @section('script')

   @endsection --}}