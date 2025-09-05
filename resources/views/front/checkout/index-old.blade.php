@extends('layouts.app')

@section('page', 'Checkout')

@section('content')
<style>
.cart-flow li:before {
    width: calc(1200px / 3);
}
</style>

<section class="cart-header mb-3 mb-sm-5">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <h4>Shopping Checkout</h4>
            </div>
            {{-- <div class="col-sm-9">
                <ul class="cart-flow">
                    <li class="active"><a href="javascript: void(0)"><span>Cart</span></a></li>
                    <li class="active"><a href="javascript: void(0)"><span>Checkout</span></a></li>
                    <li><a href="javascript: void(0)"><span>Shipping</span></a></li>
                    <li><a href="javascript: void(0)"><span>Payment</span></a></li>
                </ul>
            </div> --}}
        </div>
    </div>
</section>

<section class="cart-wrapper">
    <div class="container">
        <form class="checkout-form" method="POST" action="{{route('front.checkout.store')}}">@csrf
            <div class="row justify-content-between flex-sm-row-reverse">
                <div class="col-md-5 col-lg-4 mb-3 mb-sm-0">
                    <h4 class="cart-heading">Cart Summary</h4>
                    <ul class="cart-summary">
                        @php
                            $subTotal = $grandTotal = $couponCodeDiscount = $shippingCharges = $taxPercent = 0;
                        @endphp

                        @foreach ($cartData as $cartKey => $cartValue)
                        <li>
                            <figure>
                                <img src="{{$cartValue->product_image}}" />
                            </figure>
                            <figcaption>
                                <div class="cart-info">
                                    <h4>{{$cartValue->product_name}}</h4>
                                    <h6>Style # OF {{$cartValue->product_style_no}}</h6>
                                    <p>QTY : {{$cartValue->qty}}
                                    @if ($cartValue->cartVariationDetails)
                                        | {{$cartValue->cartVariationDetails->sizeDetails->name.', '.ucwords($cartValue->cartVariationDetails->colorDetails->name)}}</p>
                                    @endif
                                </div>
                                <div class="card-meta">
                                    <h4>&#8377;{{$cartValue->offer_price}}</h4>
                                </div>
                            </figcaption>
                        </li>

                        @php
                            // $subTotal += $cartValue->offer_price * $cartValue->qty;
                            // $grandTotal = $subTotal;

                            // subtotal calculation
                            $subTotal += (int) $cartValue->offer_price * $cartValue->qty;

                            // coupon code calculation
                            if (!empty($cartData[0]->coupon_code_id)) {
                                $couponCodeDiscount = (int) $cartData[0]->couponDetails->amount;
                            }

                            // grand total calculation
                            $grandTotalWithoutCoupon = $subTotal;
                            $grandTotal = ($subTotal + $shippingCharges) - $couponCodeDiscount;
                            // $grandTotal = $subTotal - $couponCodeDiscount;
                        @endphp

                        @endforeach
                    </ul>
                    <div class="w-100">
                        <div class="cart-total">
                            <div class="cart-total-label">
                                Subtotal
                            </div>
                            <div class="cart-total-value">
                                &#8377;{{$subTotal}}
                            </div>
                        </div>
                        <div class="cart-total-label mt-3 mb-3">
                            Shipping Method
                        </div>
                        <ul class="checkout-meta mb-2">
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="shipping_method" id="flexRadioDefault1" value="standard" checked>
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        Standard
                                    </label>
                                </div>
                            </li>
                            {{-- <li>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="shipping_method" id="flexRadioDefault2" value="standard_cod">
                                    <label class="form-check-label" for="flexRadioDefault2">
                                        Standard Shipping (Cash on Delivery)
                                    </label>
                                </div>
                            </li> --}}
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="shipping_method" id="flexRadioDefault3" value="express">
                                    <label class="form-check-label" for="flexRadioDefault3">
                                        Express
                                    </label>
                                </div>
                            </li>
                        </ul>
                        <div class="cart-total">
                            <div class="cart-total-label">
                                Shipping Charges
                            </div>
                            <div class="cart-total-value">
                                &#8377;{{$shippingCharges}}
                            </div>
                        </div>
                        {{-- <div class="cart-total">
                            <div class="cart-total-label">
                                Tax and Others - <strong>{{$taxPercent}}%</strong><br/>
                                <small>(Inclusive of all taxes)</small>
                            </div>
                            <div class="cart-total-value"></div>
                        </div> --}}
                        <div id="appliedCouponHolder">
                        @if (!empty($cartData[0]->coupon_code_id))
                            <div class="cart-total">
                                <div class="cart-total-label">
                                    COUPON APPLIED - <strong>{{$cartData[0]->couponDetails->coupon_code}}</strong><br/>
                                    <a href="javascript:void(0)" onclick="removeAppliedCoupon()"><small>(Remove this coupon)</small></a>
                                </div>
                                <div class="cart-total-value">- {{$cartData[0]->couponDetails->amount}}</div>
                            </div>
                        @endif
                        </div>
                        <div class="cart-total">
                            <div class="cart-total-label">
                                Total
                            </div>
                            {{-- <div class="cart-total-value">
                                <input type="hidden" name="coupon_code_id" value="">
                                <input type="hidden" name="grandTotal" value="{{$grandTotal}}">
                                &#8377;<span id="displayGrandTotal">{{$grandTotal}}</span>
                            </div> --}}
                            <div class="cart-total-value">
                                <input type="hidden" value="{{$grandTotalWithoutCoupon}}" name="grandTotalWithoutCoupon">
                                &#8377;<span id="displayGrandTotal">{{$grandTotal}}</span>
                            </div>
                        </div>
                        {{-- <div class="cart-total-label mt-3 mb-3">
                            Payment Method
                        </div>
                        <ul class="checkout-meta mb-2">
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_method_cod" value="cash_on_delivery" checked>
                                    <label class="form-check-label" for="payment_method_cod">
                                        Cash on Delivery (COD)
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_method_online" value="online_payment">
                                    <label class="form-check-label" for="payment_method_online">
                                        Razorpay (Cards, UPI, NetBanking, Wallets, Paypal)
                                    </label>
                                </div>
                            </li>
                        </ul> --}}

                        {{-- <div class="col-sm-12">
                            <ul class="cart-summary-list">
                                <li>
                                    <img src="img/delivery-truck.png" />
                                    <h5><span>&#8377;60</span> Apply Below order &#8377;499</h5>
                                    <a href="{{route('front.content.shipping')}}">See all Shipping charges and policies</a>
                                </li>
                                <li>
                                    <img src="img/coupon.png" />
                                    <div class="coupon-block">
                                        <input type="text" class="coupon-text" name="couponText" id="couponText" placeholder="Enter coupon code here">
                                        <button id="applyCouponBtn">Apply</button>
                                    </div>
                                    @error('lname')<p class="small text-danger mb-0 mt-2">{{$message}}</p>@enderror
                                    <a href="{{route('front.user.coupon')}}" class="d-inline-block mt-2">Get latest coupon from here</a>
                                </li>
                            </ul>
                        </div> --}}
                    </div>
                </div>
                <div class="col-md-7 col-lg-6">
                    <h4 class="cart-heading">Contact Information</h4>
                    <div class="row mb-5">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input type="text" class="form-control" name="fname" id="checkoutFname" value="@auth{{Auth::guard('web')->user()->fname}}@else{{old('fname')}}@endauth" placeholder="First Name">
                                <label class="floating-label">First Name</label>
                            </div>
                            @error('fname')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input type="text" class="form-control" name="lname" id="checkoutLname" value="@auth{{Auth::guard('web')->user()->lname}}@else{{old('lname')}}@endauth" placeholder="Last Name">
                                <label class="floating-label">Last Name</label>
                            </div>
                            @error('lname')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" id="checkoutEmail" placeholder="Enter Your Email Address" value="@auth{{Auth::guard('web')->user()->email}}@else{{old('email')}}@endauth">
                                <label class="floating-label">Enter Your Email Address</label>
                            </div>
                            @error('email')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input type="tel" class="form-control" name="mobile" id="checkoutMobile" placeholder="Enter Your Contact Number" value="@auth{{Auth::guard('web')->user()->mobile}}@else{{old('mobile')}}@endauth">
                                <label class="floating-label">Enter Your Contact Number</label>
                            </div>
                            @error('mobile')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                        </div>
                    </div>

                    <h4 class="cart-heading">Billing address</h4>

                    @if (isset($addressData))
                    <ul class="checkout-meta mb-2">
                    @foreach ($addressData as $addressKey => $addressValue)
                        <li><div class="form-check">
                            <input class="form-check-input" type="radio" name="existing_billing_address" id="existing_billing_address.{{$addressValue->id}}" value="{{$addressValue->id}}"  billing_address="{{$addressValue->address}}" billing_country="{{$addressValue->country ? $addressValue->country.', ' : ''}}" billing_landmark="{{$addressValue->landmark ? $addressValue->landmark.', ' : ''}}" billing_city="{{$addressValue->city}}" billing_state="{{$addressValue->state}}" billing_pin="{{$addressValue->pin}}" {{$addressKey == 0 ? 'checked' : ''}}>
                            <label class="form-check-label" for="existing_billing_address.{{$addressValue->id}}">
                                <span class="billing_address">{{$addressValue->address}}</span>,
                                <span class="billing_country">{{$addressValue->country ? $addressValue->country.', ' : ''}}</span>
                                <span class="billing_landmark">{{$addressValue->landmark ? $addressValue->landmark.', ' : ''}}</span>,
                                <span class="billing_city">{{$addressValue->city}}</span>,
                                <span class="billing_state">{{$addressValue->state}}</span>,
                                <span class="billing_pin" >{{$addressValue->pin}}</span>
                            </label>
                        </div></li>
                    @endforeach
                    </ul>
                    @endif

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <input type="text" class="form-control" name="billing_country" value="{{old('billing_country')}}" placeholder="Country/Region">
                                <label class="floating-label">Country/Region</label>
                            </div>
                            @error('billing_country')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                        </div>
                        {{-- <div class="col-sm-12">
                            <div class="form-group">
                                <input type="text" class="form-control" name="email"  value="{{old('email')}}" placeholder="Company (Optional)">
                                <label class="floating-label">Company (Optional)</label>
                            </div>
                        </div> --}}
                        <div class="col-sm-12">
                            <div class="form-group">
                                <input type="text" class="form-control" name="billing_address" value="{{old('billing_address')}}" placeholder="Address">
                                <label class="floating-label">Address</label>
                            </div>
                            @error('billing_address')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <input type="text" class="form-control" name="billing_landmark" value="{{old('billing_landmark')}}" placeholder="Landmark">
                                <label class="floating-label">Landmark</label>
                            </div>
                            @error('billing_landmark')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="text" class="form-control" name="billing_city" value="{{old('billing_city')}}" placeholder="City">
                                <label class="floating-label">City</label>
                            </div>
                            @error('billing_city')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="text" class="form-control" name="billing_state" value="{{old('billing_state')}}" placeholder="State">
                                <label class="floating-label">State</label>
                            </div>
                            @error('billing_state')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="text" class="form-control" name="billing_pin" value="{{old('billing_pin')}}" placeholder="Pin Code">
                                <label class="floating-label">Pin Code</label>
                            </div>
                            @error('billing_pin')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                        </div>
                    </div>

                    <h4 class="cart-heading mt-4">Shipping address</h4>

                    {{-- @if (isset($addressData))
                    @foreach ($addressData as $addressKey => $addressValue)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="existing_shipping_address" id="existing_shipping_address.{{$addressValue->id}}" value="{{$addressValue->id}}" {{$addressKey == 0 ? 'checked' : ''}}>
                            <label class="form-check-label" for="existing_shipping_address.{{$addressValue->id}}">
                                <span class="billing_address">{{$addressValue->address}}</span>,
                                <span class="billing_country">{{$addressValue->country ? $addressValue->country.', ' : ''}}</span>
                                <span class="billing_landmark">{{$addressValue->landmark ? $addressValue->landmark.', ' : ''}}</span>,
                                <span class="billing_city">{{$addressValue->city}}</span>,
                                <span class="billing_state">{{$addressValue->state}}</span>,
                                <span class="billing_pin">{{$addressValue->pin}}</span>
                            </label>
                        </div>
                    @endforeach
                    @endif --}}

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" name="shippingSameAsBilling" type="checkbox" value="1" id="shippingaddress" {{ (old('shippingSameAsBilling') == 1) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="shippingaddress" >
                                        Same as Billing Address
                                    </label>
                                </div>
                            </div>
                            @error('shippingSameAsBilling')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                        </div>
                    </div>

                    <div class="row shipping-address d-none">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <input type="text" class="form-control" name="shipping_country" value="{{old('shipping_country')}}" placeholder="Country/Region">
                                <label class="floating-label">Country/Region</label>
                            </div>
                            @error('shipping_country')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <input type="text" class="form-control" name="shipping_address" value="{{old('shipping_address')}}" placeholder="Address">
                                <label class="floating-label">Address</label>
                            </div>
                            @error('shipping_address')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <input type="text" class="form-control" name="shipping_landmark" value="{{old('shipping_landmark')}}" placeholder="Landmark">
                                <label class="floating-label">Landmark</label>
                            </div>
                            @error('shipping_landmark')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="text" class="form-control" name="shipping_city" value="{{old('shipping_city')}}" placeholder="City">
                                <label class="floating-label">City</label>
                            </div>
                            @error('shipping_city')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="text" class="form-control" name="shipping_state" value="{{old('shipping_state')}}" placeholder="State">
                                <label class="floating-label">State</label>
                            </div>
                            @error('shipping_state')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="text" class="form-control" name="shipping_pin" value="{{old('shipping_pin')}}" placeholder="Pin Code">
                                <label class="floating-label">Pin Code</label>
                            </div>
                            @error('shipping_pin')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                        </div>
                    </div>

                    <div class="row align-items-center justify-content-between">
                        <div class="col-sm-auto">
                            <input type="hidden" name="razorpay_payment_id" value="">
                            <input type="hidden" name="razorpay_amount" value="">
                            <input type="hidden" name="razorpay_method" value="">
                            <input type="hidden" name="razorpay_callback_url" value="">

                            <button type="submit" class="btn checkout-btn">Complete Order</button>
                            <strong>OR</strong>
                            <button type="button" id="rzp-button1" class="btn checkout-btn">Pay Online</button>
                        </div>
                        <div class="col-sm-auto mt-3 mt-sm-0">
                            <a href="{{route('front.cart.index')}}">Return to Cart</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('script')
    <script>
        // razorpay payment options
        var paymentOptions = {
            // "key": "rzp_test_jIwVtRPfWGhVHO",
            "key": "rzp_test_FUwg5zjKBrNRVm",
            // "key": "rzp_live_057mrk4Rrmh8f2",
            // "key": "{{env('RAZORPAY_KEY')}}",
            "amount": '{{intval($grandTotal*100)}}',
            // "amount": parseInt(document.querySelector('[name="grandTotal"]').value) * 100,
            "currency": "INR",
            "name": "ONN",
            // "name": "{{env('APP_NAME')}}",
            "description": "Test Transaction",
            "image": "{{asset('img/logo-square.png')}}",
            // "order_id": "order_9A33XWu170gUtm", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
            "customer": {
                // "name": document.getElementById('checkoutEmail').value,
                "contact": document.getElementById('checkoutMobile').value,
                "email": document.getElementById('checkoutEmail').value
            },
            "handler": function (response){
                //console.log(response.request.content.amount);

                $('input[name="razorpay_payment_id"]').val(response.razorpay_payment_id);
                //$('input[name="razorpay_amount"]').val(response.request.content.amount);
                //$('input[name="razorpay_method"]').val(response.request.content.method);
                //$('input[name="razorpay_callback_url"]').val(response.request.content.callback_url);

                $('.checkout-form').submit();

                /* alert(response.razorpay_payment_id);
                alert(response.razorpay_order_id);
                alert(response.razorpay_signature) */
            },
            // "callback_url": "{{route('front.checkout.store')}}",
            "prefill": {
                "name": $('#checkoutFname').val()+' '+$('#checkoutLname').val(),
                // "email": $('#checkoutEmail').val(),
                // "contact": $('#checkoutMobile').val(),
                "email": document.getElementById('checkoutEmail').value,
                "contact": document.getElementById('checkoutMobile').value,
                /* "name": document.querySelector('[name="fname"]').value+' '+document.querySelector('[name="lname"]').value,
                "email": document.querySelector('[name="email"]').value,
                "contact": document.querySelector('[name="mobile"]').value */
            },
            "notes": {
                "address": "Razorpay Corporate Office"
            },
        };
        var rzp1 = new Razorpay(paymentOptions);
        rzp1.on('payment.failed', function (response){
            alert('OOPS ! something happened');

            /* alert(response.error.code);
            alert(response.error.description);
            alert(response.error.source);
            alert(response.error.step);
            alert(response.error.reason);
            alert(response.error.metadata.order_id);
            alert(response.error.metadata.payment_id); */
        });

        // check details before paying online
        function checkoutDetailsExists() {
            const mobilelength = $('input[name="mobile"]').val().length
            console.log(mobilelength)

            if ($('input[name="fname"]').val() == "") {
                $('input[name="fname"]').css('borderColor', '#c1080a').focus()
                toastFire('warning', 'Enter first name')
                return false;
            } else if ($('input[name="lname"]').val() == "") {
                $('input[name="lname"]').css('borderColor', '#c1080a').focus()
                toastFire('warning', 'Enter last name')
                return false;
            } else if ($('input[name="email"]').val() == "") {
                $('input[name="email"]').css('borderColor', '#c1080a').focus()
                toastFire('warning', 'Enter email address')
                return false;
            } else if ($('input[name="mobile"]').val() == "" || $('input[name="mobile"]').val().length !== 10) {
                $('input[name="mobile"]').css('borderColor', '#c1080a').focus()
                toastFire('warning', 'Enter valid 10 digit mobile number')
                return false;
            } else if ($('input[name="billing_country"]').val() == "") {
                $('input[name="billing_country"]').css('borderColor', '#c1080a').focus()
                toastFire('warning', 'Enter billing country')
                return false;
            } else if ($('input[name="billing_address"]').val() == "") {
                $('input[name="billing_address"]').css('borderColor', '#c1080a').focus()
                toastFire('warning', 'Enter billing address')
                return false;
            } else if ($('input[name="billing_city"]').val() == "") {
                $('input[name="billing_city"]').css('borderColor', '#c1080a').focus()
                toastFire('warning', 'Enter billing city')
                return false;
            } else if ($('input[name="billing_state"]').val() == "") {
                $('input[name="billing_state"]').css('borderColor', '#c1080a').focus()
                toastFire('warning', 'Enter billing state')
                return false;
            } else if ($('input[name="billing_pin"]').val() == "" || $('input[name="billing_pin"]').val().length !== 6) {
                $('input[name="billing_pin"]').css('borderColor', '#c1080a').focus()
                toastFire('warning', 'Enter valid 6 digit billing pincode')
                return false;
            } else {
                return true;
            }
        }

        document.getElementById('rzp-button1').onclick = function(e){
            e.preventDefault();
            if (checkoutDetailsExists()) {
                // let chekoutAmount = getCookie('checkoutAmount');
                rzp1.open();
            }
        }
    </script>
@endsection
