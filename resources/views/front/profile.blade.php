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
                                    <div class="form-group">
                                        <input type="text" class="form-control input-style" name="address" id="address" placeholder=" " value="{{ $user->defaultAddress->address ?? '' }}" required>
                                        <label class="placeholder-text">Address</label>
                                        @error('address') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control input-style" name="landmark" id="landmark" placeholder=" " value="{{ $user->defaultAddress->landmark ?? '' }}">
                                        <label class="placeholder-text">Landmark</label>
                                        @error('landmark') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control input-style" name="city" id="city" placeholder=" " value="{{ $user->defaultAddress->city ?? '' }}">
                                                <label class="placeholder-text">City</label>
                                                @error('city') <p class="small text-danger">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control input-style" name="state" id="state" placeholder=" " value="{{ $user->defaultAddress->state ?? '' }}">
                                                <label class="placeholder-text">State</label>
                                                @error('state') <p class="small text-danger">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control input-style" name="pin" id="pin" placeholder=" " value="{{ $user->defaultAddress->pin ?? '' }}">
                                                <label class="placeholder-text">Pin Code</label>
                                                @error('pin') <p class="small text-danger">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control input-style" name="country" id="country" placeholder=" " value="{{ $user->defaultAddress->country ?? '' }}">
                                                <label class="placeholder-text">Country</label>
                                                @error('country') <p class="small text-danger">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
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
