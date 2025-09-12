@extends('layouts.app')
@section('page', 'Checkout')

@section('content')
    <section class="main">
        <div class="container">
            <div class="checkout-wrap">
                <div class="row">
                    <div class="col-lg-6 p-0 order-lg-1 order-2">
                        <div class="cart-form-stack">
                            <form id="checkoutForm" action="{{ route('front.checkout.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="checkout_id" value="{{$checkoutId}}">
                                <div class="login-checkout">
                                    <h3 class="checkout-heading">Contact information</h3>
                                    <p>We'll use this email to send you details and updates about your order.</p>

                                    <div class="form-group"> 
                                        <input type="email" class="form-control input-style" 
                                            value="{{ auth()->user()->email ?? '' }}" 
                                            name="email">
                                        <label class="placeholder-text">Enter Email</label>
                                    </div>
                                </div>

                                <!-- Billing Address -->
                                <div class="billing-place">
                                    <h3 class="checkout-heading mb-4">Billing information</h3>
                                    <div class="form-group">
                                        <select name="billing_country" class="form-select select-style">
                                            <option value="India" selected>India</option>
                                        </select>
                                        <span class="text-danger error-text billing_country_error"></span>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <input type="text" name="first_name" class="form-control" 
                                                    value="{{ explode(' ', auth()->user()->name)[0] ?? '' }}">
                                                <span class="text-danger error-text first_name_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group"> 
                                                <input type="text" name="last_name" class="form-control" 
                                                    value="{{ explode(' ', auth()->user()->name)[1] ?? '' }}">
                                                <span class="text-danger error-text last_name_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group"> 
                                        <input type="text" class="form-control input-style" name="billing_address">
                                        <label class="placeholder-text">Address</label>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"> 
                                                <input type="text" class="form-control input-style" name="billing_city">
                                                <label class="placeholder-text">City</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"> 
                                                <select name="billing_state" class="form-select select-style">
                                                    <option value="">Select State</option>
                                                    <option>Andhra Pradesh</option>
                                                    <option>Arunachal Pradesh</option>
                                                    <option>Assam</option>
                                                    <option>Bihar</option>
                                                    <option>Chhattisgarh</option>
                                                    <option>Goa</option>
                                                    <option>Gujarat</option>
                                                    <option>Haryana</option>
                                                    <option>Himachal Pradesh</option>
                                                    <option>Jharkhand</option>
                                                    <option>Karnataka</option>
                                                    <option>Kerala</option>
                                                    <option>Madhya Pradesh</option>
                                                    <option>Maharashtra</option>
                                                    <option>Manipur</option>
                                                    <option>Meghalaya</option>
                                                    <option>Mizoram</option>
                                                    <option>Nagaland</option>
                                                    <option>Odisha</option>
                                                    <option>Punjab</option>
                                                    <option>Rajasthan</option>
                                                    <option>Sikkim</option>
                                                    <option>Tamil Nadu</option>
                                                    <option>Telangana</option>
                                                    <option>Tripura</option>
                                                    <option>Uttar Pradesh</option>
                                                    <option>Uttarakhand</option>
                                                    <option>West Bengal</option>
                                                    <option>Andaman and Nicobar Islands</option>
                                                    <option>Chandigarh</option>
                                                    <option>Dadra and Nagar Haveli and Daman and Diu</option>
                                                    <option>Delhi</option>
                                                    <option>Lakshadweep</option>
                                                    <option>Puducherry</option>
                                                    <option>Ladakh</option>
                                                    <option>Jammu and Kashmir</option>
                                                </select>
                                            </div>
                                        </div>
                                    
                                        <div class="col-lg-4">
                                            <div class="form-group"> 
                                                <input type="text" class="form-control input-style" name="billing_pin">
                                                <label class="placeholder-text">Pin Code</label>
                                            </div> 
                                        </div>
                                    </div>
                                
                                    <div class="form-group"> 
                                        <input type="text" class="form-control input-style" name="billing_landmark">
                                        <label class="placeholder-text">Billing Landmark</label>
                                    </div>
                                
                                    <div class="form-group"> 
                                        <input type="tel" class="form-control input-style" name="mobile" value="{{ auth()->user()->mobile ?? '' }}">
                                        <label class="placeholder-text">Phone Number</label>
                                    </div>
                                </div>

                                
                                <div class="shipping-place">
                                    <h3 class="checkout-heading mb-4">Shipping method</h3>

                                    <div class="ship-stack">
                                        <span>Standard</span>
                                        <strong>Free</strong>
                                    </div>
                                </div>

                                <div class="payment-place">
                                    <h3 class="checkout-heading">Shipping method</h3>
                                    <p>All transactions are secure and encrypted.</p>
                                </div>

                                <!-- Radio Buttons -->
                                <div class="address-choice my-4">
                                    <h3 class="checkout-heading mb-3">Shipping Address</h3>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="address_option" id="sameAddress" value="same" checked>
                                        <label class="form-check-label" for="sameAddress">
                                            Same as billing address
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="address_option" id="differentAddress" value="different">
                                        <label class="form-check-label" for="differentAddress">
                                            Use a different shipping address
                                        </label>
                                    </div>
                                </div>

                                <!-- Shipping Form (Hidden by Default) -->
                                <div id="shippingAddressForm" style="display:none;">
                                    <h3 class="checkout-heading mb-4">Shipping information</h3>

                                    <div class="form-group">
                                        <select name="shipping_country" class="form-select select-style">
                                            <option value="India" selected>India</option>
                                        </select>
                                        <span class="text-danger error-text shipping_country_error"></span>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <input type="text" name="shipping_first_name" class="form-control" placeholder="First Name">
                                                <span class="text-danger error-text shipping_first_name_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group"> 
                                                <input type="text" name="shipping_last_name" class="form-control" placeholder="Last Name">
                                                <span class="text-danger error-text shipping_last_name_error"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group"> 
                                        <input type="text" class="form-control input-style" name="shipping_address">
                                        <label class="placeholder-text">Address</label>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"> 
                                                <input type="text" class="form-control input-style" name="shipping_city">
                                                <label class="placeholder-text">City</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"> 
                                                <select name="shipping_state" class="form-select select-style">
                                                    <option value="">Select State</option>
                                                    <option>Andhra Pradesh</option>
                                                    <option>Arunachal Pradesh</option>
                                                    <option>Assam</option>
                                                    <option>Bihar</option>
                                                    <option>Chhattisgarh</option>
                                                    <option>Goa</option>
                                                    <option>Gujarat</option>
                                                    <option>Haryana</option>
                                                    <option>Himachal Pradesh</option>
                                                    <option>Jharkhand</option>
                                                    <option>Karnataka</option>
                                                    <option>Kerala</option>
                                                    <option>Madhya Pradesh</option>
                                                    <option>Maharashtra</option>
                                                    <option>Manipur</option>
                                                    <option>Meghalaya</option>
                                                    <option>Mizoram</option>
                                                    <option>Nagaland</option>
                                                    <option>Odisha</option>
                                                    <option>Punjab</option>
                                                    <option>Rajasthan</option>
                                                    <option>Sikkim</option>
                                                    <option>Tamil Nadu</option>
                                                    <option>Telangana</option>
                                                    <option>Tripura</option>
                                                    <option>Uttar Pradesh</option>
                                                    <option>Uttarakhand</option>
                                                    <option>West Bengal</option>
                                                    <option>Andaman and Nicobar Islands</option>
                                                    <option>Chandigarh</option>
                                                    <option>Dadra and Nagar Haveli and Daman and Diu</option>
                                                    <option>Delhi</option>
                                                    <option>Lakshadweep</option>
                                                    <option>Puducherry</option>
                                                    <option>Ladakh</option>
                                                    <option>Jammu and Kashmir</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"> 
                                                <input type="text" class="form-control input-style" name="shipping_pin">
                                                <label class="placeholder-text">Pin Code</label>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="form-group"> 
                                        <input type="text" class="form-control input-style" name="shipping_landmark">
                                        <label class="placeholder-text">Shipping Landmark</label>
                                    </div>

                                    <div class="form-group"> 
                                        <input type="tel" class="form-control input-style" name="alt_mobile">
                                        <label class="placeholder-text">Alternative Phone Number</label>
                                    </div>
                                </div>

                                <!-- Submit -->
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    {{-- <div class="col-lg-6 p-0 order-lg-2 order-1">
                        <div class="cart-right-stack">
                            <div class="checkut-product-show">
                                <ul class="cart-item-list">
                                    @foreach($cartItems as $item)
                                        <li>
                                            <div class="inner-wrap">
                                                <figure>
                                                    <img src="{{ asset($item->productDetails->image ?? 'assets/images/placeholder-product.jpg') }}" alt="">
                                                </figure>
                                                <figcaption>
                                                    <div class="product-details-cart">
                                                        <a href="#"><h3>{{ $item->productDetails->name }}</h3></a>
                                                        <div class="pro-meta">
                                                            <span>Category:</span> {{ $item->productDetails->category->name ?? '-' }}
                                                        </div>
                                                        <div class="pro-meta">
                                                            <span>Weight:</span> {{ $item->variation->weight ?? '-' }}
                                                        </div>
                                                        <div class="pro-meta">
                                                            <span>Quantity:</span> {{ $item->qty ?? '-' }}
                                                        </div>

                                                        <div class="pro-meta">
                                                            <span>GST:</span> {{ $tax }}%
                                                        </div>
                                                    </div>
                                                    <span class="cart-price">₹{{ number_format($itemTotal, 2) }}</span>
                                                </figcaption>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="checkut-meta">
                                <div class="cart-row">
                                    <span>Subtotal</span>
                                    ₹{{ number_format($subtotal, 2) }}
                                </div>
                                <div class="cart-row">
                                    <span>Shipping</span>
                                    FREE
                                </div>
                                @if($coupon)
                                    @php $couponType = $coupon->type ?? $coupon->type ?? null; @endphp

                                    <div class="cart-row">
                                        <span>Coupon Applied</span>
                                        {{ $coupon->code ?? '' }}
                                    </div>

                                    <div class="cart-row">
                                        <span>Coupon Type</span>
                                        {{ $couponType == 1 ? 'Percentage' : 'Discount' }}
                                    </div>

                                    <div class="cart-row">
                                        <span>Coupon Discount</span>
                                        @if($couponType == 2)
                                            - ₹{{ number_format($discount, 2) }}
                                        @else
                                            - {{ number_format($coupon->amount, 1) }}% (₹{{ number_format($discount, 2) }})
                                        @endif
                                    </div>
                                @endif
                                <div class="cart-row">
                                    <span>GST</span>
                                    ₹{{ number_format($tax, 2) }}
                                </div>

                                <div class="cart-total">
                                    <span>Total</span>
                                    ₹{{ number_format($total, 2) }}
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-lg-6 p-0 order-lg-2 order-1">
                        <div class="cart-right-stack">
                            <div class="checkut-product-show">
                                <ul class="cart-item-list">
                                    @foreach($cartItems as $item)
                                        <li>
                                            <div class="inner-wrap">
                                                <figure>
                                                    <img src="{{ asset($item->productDetails->image ?? 'assets/images/placeholder-product.jpg') }}" alt="">
                                                </figure>
                                                <figcaption>
                                                    <div class="product-details-cart">
                                                        <a href="#"><h3>{{ $item->productDetails->name }}</h3></a>
                                                        <div class="pro-meta">
                                                            <span>Category:</span> {{ $item->productDetails->category->name ?? '-' }}
                                                        </div>
                                                        <div class="pro-meta">
                                                            <span>Weight:</span> {{ $item->variation->weight ?? '-' }}
                                                        </div>
                                                        <div class="pro-meta">
                                                            <span>Quantity:</span> {{ $item->qty ?? '-' }}
                                                        </div>
                                                    </div>
                                                    <span class="cart-price">
                                                        ₹{{ number_format($item->calc['lineTotal'], 2) }}
                                                    </span>
                                                </figcaption>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="checkut-meta">
                                <div class="cart-row">
                                    <span>Subtotal</span>
                                    ₹{{ number_format($subtotal, 2) }}
                                </div>
                                <div class="cart-row">
                                    <span>Shipping</span>
                                    FREE
                                </div>

                                @if($coupon)
                                    @php $couponType = $coupon->coupon_type ?? $coupon->type ?? null; @endphp

                                    <div class="cart-row">
                                        <span>Coupon Applied</span>
                                        {{ $coupon->coupon_code ?? '' }}
                                    </div>

                                    <div class="cart-row">
                                        <span>Coupon Discount</span>
                                        @if($couponType == 2)
                                            - ₹{{ number_format($discount, 2) }}
                                        @else
                                            - {{ number_format($coupon->amount, 1) }}% (₹{{ number_format($discount, 2) }})
                                        @endif
                                    </div>
                                @endif

                                <div class="cart-total">
                                    <span>Total(Inclusive of all taxes)</span>
                                    ₹{{ number_format($total, 2) }}
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
    $(document).ready(function () {

        $('#checkoutForm').on('submit', function (e) {
            e.preventDefault();

            let isValid = true;
            let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            // Remove previous errors
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.error-message').remove();

            // ---------------- BILLING VALIDATION ----------------
            
            // Email
            let email = $('input[name="email"]').val().trim();
            if (!email || !emailPattern.test(email)) {
                $('input[name="email"]').addClass('is-invalid')
                    .after('<span class="error-message text-danger">Please enter a valid email.</span>');
                isValid = false;
            }

            // First Name
            let fname = $('input[name="first_name"]').val().trim();
            if (!fname) {
                $('input[name="first_name"]').addClass('is-invalid')
                    .after('<span class="error-message text-danger">First name is required.</span>');
                isValid = false;
            }

            // Last Name
            let lname = $('input[name="last_name"]').val().trim();
            if (!lname) {
                $('input[name="last_name"]').addClass('is-invalid')
                    .after('<span class="error-message text-danger">Last name is required.</span>');
                isValid = false;
            }

            // Address
            let address = $('input[name="billing_address"]').val().trim();
            if (!address) {
                $('input[name="billing_address"]').addClass('is-invalid')
                    .after('<span class="error-message text-danger">Address is required.</span>');
                isValid = false;
            }

            // City
            let city = $('input[name="billing_city"]').val().trim();
            if (!city) {
                $('input[name="billing_city"]').addClass('is-invalid')
                    .after('<span class="error-message text-danger">City is required.</span>');
                isValid = false;
            }

            // State
            let state = $('select[name="billing_state"]').val();
            if (!state || state === 'Select State') {
                $('select[name="billing_state"]').addClass('is-invalid')
                    .after('<span class="error-message text-danger">Please select a state.</span>');
                isValid = false;
            }

            // Pin Code
            let pin = $('input[name="billing_pin"]').val().trim();
            if (!pin || !/^\d{6}$/.test(pin)) {
                $('input[name="billing_pin"]').addClass('is-invalid')
                    .after('<span class="error-message text-danger">Please enter a valid 6-digit pin code.</span>');
                isValid = false;
            }

            // Mobile
            let mobile = $('input[name="mobile"]').val().trim();
            if (!mobile || !/^\d{10}$/.test(mobile)) {
                $('input[name="mobile"]').addClass('is-invalid')
                    .after('<span class="error-message text-danger">Please enter a valid 10-digit phone number.</span>');
                isValid = false;
            }

            // ---------------- SHIPPING VALIDATION ----------------
            if ($('input[name="address_option"]:checked').val() === 'different') {
                
                let sFname = $('input[name="shipping_first_name"]').val().trim();
                let sLname = $('input[name="shipping_last_name"]').val().trim();
                let sAddress = $('input[name="shipping_address"]').val().trim();
                let sCity = $('input[name="shipping_city"]').val().trim();
                let sState = $('select[name="shipping_state"]').val();
                let sPin = $('input[name="shipping_pin"]').val().trim();

                if (!sFname) {
                    $('input[name="shipping_first_name"]').addClass('is-invalid')
                        .after('<span class="error-message text-danger">First name is required.</span>');
                    isValid = false;
                }
                if (!sLname) {
                    $('input[name="shipping_last_name"]').addClass('is-invalid')
                        .after('<span class="error-message text-danger">Last name is required.</span>');
                    isValid = false;
                }
                if (!sAddress) {
                    $('input[name="shipping_address"]').addClass('is-invalid')
                        .after('<span class="error-message text-danger">Address is required.</span>');
                    isValid = false;
                }
                if (!sCity) {
                    $('input[name="shipping_city"]').addClass('is-invalid')
                        .after('<span class="error-message text-danger">City is required.</span>');
                    isValid = false;
                }
                if (!sState || sState === 'Select State') {
                    $('select[name="shipping_state"]').addClass('is-invalid')
                        .after('<span class="error-message text-danger">Please select a state.</span>');
                    isValid = false;
                }
                if (!sPin || !/^\d{6}$/.test(sPin)) {
                    $('input[name="shipping_pin"]').addClass('is-invalid')
                        .after('<span class="error-message text-danger">Please enter a valid 6-digit pin code.</span>');
                    isValid = false;
                }
            }

            // ---------------- FINAL SUBMIT ----------------
            if (isValid) {
                $.ajax({
                    url: "{{ route('front.checkout.store') }}",
                    method: "POST",
                    data: $('#checkoutForm').serialize(),
                    success: function (response) {
                        if (response.success) {
                            window.location.href = response.redirect_url;
                        } else {
                            alert(response.message || "Something went wrong, please try again.");
                        }
                    }
                });
            }
        });

    });

    $(document).ready(function(){
        $("input[name='address_option']").change(function(){
            if($(this).val() === "different"){
                $("#shippingAddressForm").slideDown();
            } else {
                $("#shippingAddressForm").slideUp();
            }
        });
    });




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


  </script>

@endsection