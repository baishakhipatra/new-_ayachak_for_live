
@extends('front.layout.app')
@section('content')

<section class="cart_page_table">
    <div class="container">
        {{-- <form action="{{route('front.checkout.store')}}" method="POST" class="check_out_from" id="paymentForm"> --}}
        <form action="{{route('front.payment.createOrder')}}" method="POST" class="check_out_from" id="paymentForm" >
            <div class="row">
                <div class="col-lg-8">
                    @csrf
                    <div class="row">
                        <div class="check_out col-md-5 col-12">
                            <input class="check_out_input" type="text" placeholder="First Name*" name="fname" value="{{$user->fname ?? ''}}">
                            @error('fname')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                        </div>
                        <div class="check_out col-md-5 col-12">
                            <input class="check_out_input" type="text" placeholder="Last Name*" name="lname" value="{{$user->lname ?? ''}}">
                            @error('lname')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="check_out col-md-5 col-12">
                            <input class="check_out_input" type="email" placeholder="Email*" name="email" id="email" value="{{$user->email ?? ''}}">
                            @error('email')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                        </div>
                        <div class="check_out col-md-5 col-12">
                            <input class="check_out_input" type="tel" placeholder="Contact Number*" name="mobile" id="mobile" value="{{$user->mobile ?? ''}}">
                            @error('mobile')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                        </div>
                    </div>
                    <div class="check_contact mt-4">
                        <h3>Billing Information</h3>
                        <div class="row">
                            <div class="check_out col-md-5 col-12">
                                <input class="check_out_input" type="text" placeholder="Address*" name="billing_address" value="{{$user->billing_address ?? ''}}">
                                @error('billing_address')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                            </div>
                            <div class="check_out col-md-5 col-12">
                                <input class="check_out_input" type="text" placeholder="Land mark" name="billing_landmark" value="{{$user->billing_landmark ?? ''}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="check_out col-md-5 col-12">
                                <input class="check_out_input" type="text" placeholder="Pincode*" id="billing_pin" name="billing_pin" value="{{$user->billing_pin ?? ''}}">
                                @error('billing_pin')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                            </div>
                            <div class="check_out col-md-5 col-12" id="shippingCities">
                                <select class="check_out_input form-control" name="billing_city">
                                    <option value="" selected hidden>--Select City--</option>
                                </select>
                                @error('billing_city')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                            </div>
                 
                        </div>
                        <div class="row">
                            <div class="check_out col-md-5 col-12">
                                <input class="check_out_input" type="text" placeholder="State*" name="billing_state" value="">
                                @error('billing_state')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                            </div>
                            <div class="check_out col-md-5 col-12">
                                <input class="check_out_input" type="text" placeholder="Country*" name="billing_country" value="{{$user->billing_country ?? ''}}">
                                @error('billing_country')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                            </div>
                        </div>
                    </div>
                    <h4>Shipping Information</h4>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group mb-4">
                                <div class="form-check">
                                    <input type="hidden" name="shippingSameAsBilling" value="0">
                                    <input class="form-check-input" name="shippingSameAsBilling" type="checkbox" value="1" id="shippingaddress" checked>
                                    <label class="form-check-label" for="shippingaddress">
                                        Same as Billing Address
                                    </label>
                                </div>
                            </div>
                            @error('shippingSameAsBilling')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                        </div>
                    </div>
                    <div id="shipping_address" class="check_contact d-none">
                        <div class="row">
                            <div class="check_out col-md-5 col-12">
                                <input class="check_out_input" type="text" placeholder="Address*" name="shipping_address" value="{{$user->shipping_address ?? ''}}">
                                @error('shipping_address')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                            </div>
                            <div class="check_out col-md-5 col-12">
                                <input class="check_out_input" type="text" placeholder="Land mark" name="shipping_landmark" value="{{$user->shipping_landmark ?? ''}}">
                                @error('shipping_landmark')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="check_out col-md-5 col-12">
                                <input class="check_out_input" type="text" placeholder="Pincode*" name="shipping_pin" value="{{$user->shipping_pin ?? ''}}">
                                @error('shipping_pin')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                            </div>
                            <div class="check_out col-md-5 col-12" id="loadCities">
                                <select class="check_out_input form-control" name="shipping_city">
                                    <option value="" selected hidden>--Select City--</option>
                                </select>
                                @error('shipping_city')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="check_out col-md-5 col-12">
                                <input class="check_out_input" type="text" placeholder="State*" name="shipping_state" value="{{$user->shipping_state ?? ''}}">
                                @error('shipping_state')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                            </div>
                            <div class="check_out col-md-5 col-12">
                                <input class="check_out_input" type="text" placeholder="Country*" name="shipping_country" value="{{$user->shipping_country ?? ''}}">
                                @error('shipping_country')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="check_out_btn">
                        <input type="hidden" name="shipping_method" value="standard">
                        <input type="hidden" name="payment_method" value="">
                        <input type="hidden" name="razorpay_payment_id" value="">
                        <input type="hidden" name="razorpay_amount" value="">
                        <input type="hidden" name="razorpay_method" value="">
                        <input type="hidden" name="razorpay_callback_url" value="">
                        <button type="submit" class="orderplace" id="orderplace">Place Order</button>
                        <a href="{{route('front.cart.index')}}" class="orderreturn">Return to Cart</a>
                        {{-- <a href="#" class="orderreturn" id="rzp-button1">Online</a> --}}
                    </div>
                </div>
                <div class="col-lg-4">
                    @if($user_checkout)
                        <div class="cart_table_total">
                            <div class="cart_table_shipping">
                                <div class="cart_table_total_text">
                                    <h4>Subtotal <span>Including GST</span></h4>
                                    <h5>₹ {{number_format($user_checkout->sub_total_amount, 2, '.', '')}}</h5>
                                    <input type="hidden" name="amount" value="{{$user_checkout->sub_total_amount}}">
                                </div>
                            </div>
                            {{-- <div class="cart_table_shipping">
                                <div class="cart_table_total_text">
                                    <h4>Discount</h4>
                                    <h5 class="apply_coupon">- ₹ {{number_format($user_checkout->discount_amount, 2, '.', '')}}</h5>
                                </div>
                            </div> --}}
                            {{-- <div class="cart_table_shipping">
                                <div class="cart_table_total_text">
                                    <h4>Shipping Details</h4>
                                    <h5>₹ 130</h5>
                                </div>
                                <span>Add ₹130 more for FREE Shipping.</span>
                            </div> --}}
                            {{-- <div class="cart_table_shipping">
                                <div class="cart_table_total_text">
                                    <h4>GST</h4>
                                    <h5>₹ {{number_format($user_checkout->gst_amount, 2, '.', '')}}</h5> --}}
                                    <input type="hidden" name="tax_amount" value="{{$user_checkout->gst_amount}}">
                                {{-- </div> --}}
                            </div>
                            <div class="cart_table_shipping">
                                <div class="cart_table_total_text">
                                    <h4>Voucher Coupon</h4>
                                    <h5 class="apply_coupon">- ₹ {{number_format($user_checkout->discount_amount, 2, '.', '')}}</h5>
                                    <input type="hidden" name="discount_amount" value="{{$user_checkout->discount_amount}}">
                                </div>
                            </div>
                           
                            {{-- <div class="cart_table_shipping">
                                <div class="cart_table_total_text">
                                    <h4>SGST</h4>
                                    <h5>100</h5>
                                </div>
                            </div> --}}
                            <div class="cart_table_shipping">
                                <div class="cart_table_total_text">
                                    <h4>Total</h4>
                                    <h5>₹ {{number_format($user_checkout->final_amount, 2, '.', '')}}</h5>
                                    <input type="hidden" name="final_amount" id="final_amount" value="{{$user_checkout->final_amount}}">
                                    <input type="hidden" name="checkout_id" value="{{$user_checkout->id}}">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
</section>

<div class="modal fade seeDetailsModal" id="userOfferModal" tabindex="-1" aria-labelledby="userOfferModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row justify-content-center text-center" id="offerContent">
                    <div class="col-12 col-md-10"><h5></h5></div>
                    <div class="col-12 text-center">
                        <img src="" alt="">
                    </div>
                    <div class="col-12">
                        <button class="btn ok-btn" data-bs-dismiss="modal">close</button>   
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    
    <script>
       $(document).ready(function() {
            $('#shippingaddress').change(function() {
                if ($(this).is(':checked')) {
                    $('#shipping_address').addClass('d-none');
                    $('#shipping_address input').val('');
                } else {
                    $('#shipping_address').removeClass('d-none');
                }
            });
            // Define the validation rules and messages
            $('#paymentForm').validate({
                rules: {
                    fname: {
                        required: true
                    },
                    lname: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    mobile: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    billing_address: {
                        required: true
                    },
                    billing_city: {
                        required: true
                    },
                    billing_pin: {
                        required: true,
                        digits: true,
                        minlength: 6,
                        maxlength: 6
                    },
                    billing_state: {
                        required: true
                    },
                    billing_country: {
                        required: true
                    },
                    shipping_address: {
                        required: function() {
                            return !$('#shippingaddress').is(':checked');
                        }
                    },
                    shipping_city: {
                        required: function() {
                            return !$('#shippingaddress').is(':checked');
                        }
                    },
                    shipping_pin: {
                        required: function() {
                            return !$('#shippingaddress').is(':checked');
                        },
                        digits: true,
                        minlength: function() {
                            return !$('#shippingaddress').is(':checked') ? 6 : 0;
                        },
                        maxlength: function() {
                            return !$('#shippingaddress').is(':checked') ? 6 : 0;
                        }
                    },
                    shipping_state: {
                        required: function() {
                            return !$('#shippingaddress').is(':checked');
                        }
                    },
                    shipping_country: {
                        required: function() {
                            return !$('#shippingaddress').is(':checked');
                        }
                    }
                },
                messages: {
                    fname: {
                        required: "Please enter your first name"
                    },
                    lname: {
                        required: "Please enter your last name"
                    },
                    email: {
                        required: "Please enter your email",
                        email: "Please enter a valid email address"
                    },
                    mobile: {
                        required: "Please enter your contact number",
                        digits: "Please enter only digits",
                        minlength: "Contact number should be 10 digits",
                        maxlength: "Contact number should be 10 digits"
                    },
                    billing_address: {
                        required: "Please enter your billing address"
                    },
                    billing_city: {
                        required: "Please enter your billing city"
                    },
                    billing_pin: {
                        required: "Please enter your billing pincode",
                        digits: "Please enter only digits",
                        minlength: "Pincode should be 6 digits",
                        maxlength: "Pincode should be 6 digits"
                    },
                    billing_state: {
                        required: "Please enter your billing state"
                    },
                    billing_country: {
                        required: "Please enter your billing country"
                    },
                    shipping_address: {
                        required: "Please enter your shipping address"
                    },
                    shipping_city: {
                        required: "Please enter your shipping city"
                    },
                    shipping_pin: {
                        required: "Please enter your shipping pincode",
                        digits: "Please enter only digits",
                        minlength: "Pincode should be 6 digits",
                        maxlength: "Pincode should be 6 digits"
                    },
                    shipping_state: {
                        required: "Please enter your shipping state"
                    },
                    shipping_country: {
                        required: "Please enter your shipping country"
                    }
                }
            });
        });
        $(document).on('click', '#orderplace', function(e) {
            e.preventDefault();
            // razorpay payment options
            if ($("#paymentForm").valid()) {
                $('#orderplace').prop('disabled', true);
                $('#orderplace').text('Please wait..');
                var paymentOptions = {
                    // "key": "rzp_test_jIwVtRPfWGhVHO",
                    "key": "{{env('RAZORPAY_KEY')}}",
                    "amount":  "{{intval($final_amount*100)}}",
                    "currency": "INR",
                    "name": "LUX Industries Limited",
                    "description": "Online payment",
                    "image": "{{asset('images/cozi-payment.png')}}",
                    // "order_id": "{{$order_id}}", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
                    "handler": function (response){
                        $('#orderplace').prop('disabled', false);
                        $('#orderplace').text('Place Order');
                        console.log(response);
                        $('input[name="payment_method"]').val('online_payment');
                        $('input[name="razorpay_amount"]').val("{{$final_amount}}");
                        $('input[name="razorpay_payment_id"]').val(response.razorpay_payment_id);
                        $('#paymentForm').submit();
                    },
                    "prefill": {
                        "email": $('#email').val(),
                        "contact": $('#mobile').val()
                    },
                    "notes": {
                        "address": "Razorpay Corporate Office"
                    },
                    "theme": {
                        "color": "#050e9e"
                    }
                };
                var rzp1 = new Razorpay(paymentOptions);

                rzp1.on('payment.failed', function (response){
                    // alert('OOPS ! something happened');
                    $('#orderplace').prop('disabled', false);
                    $('#orderplace').text('Place Order');
                    toastFire('info', 'Something happened');
                });
                rzp1.open();
            }
        });
        function fetchEmail() {
            alert('email>>'+$('#checkoutEmail').val());
            return $('#checkoutEmail').val();
        }
        

        // billing pinode detail fetch
        $('input[name="billing_pin"]').on('keyup', function() {
    var pincode = $(this).val();
    if (pincode.length === 6) {
        $('input[name="billing_pin"]').css('borderColor', '#4caf50').css('boxShadow', '0 0 0 0.2rem #4caf5057');

        $.ajax({
            url: 'https://api.postalpincode.in/pincode/' + pincode,
            method: 'GET',
            success: function(result) {
                if (result[0].Message !== 'No records found') {
                    if (result[0].PostOffice && result[0].PostOffice.length > 0) {
                        let postOffices = result[0].PostOffice;

                        $('input[name="billing_state"]').val(postOffices[0].State);
                        $('input[name="billing_country"]').val(postOffices[0].Country);

                        let content = `
                            <div class="form-group">                       
                                <select class="form-control readonly_select active mt-4" name="billing_city" readonly>
                                    <option value="" selected hidden>--Select City--</option>
                                   `;
                        
                        postOffices.forEach(function(postOffice) {
                            content += `<option value="${postOffice.Name}">${postOffice.Name}</option>`;
                        });

                        content += `</select>
                            </div>`;

                        $('#shippingCities').html(content);
                        // $('.readonly_select.active').select2();

                        // console.log(result);
                    }
                } else {
                    toastFire('warning', 'Enter valid pincode');
                    $('input[name="billing_pin"]').css('borderColor', 'red').css('boxShadow', '0 0 0 0.2rem #dc34345c');
                    $('input[name="billing_state"]').val('');
                    $('input[name="billing_country"]').val('');
                    $('#loadCities').html('<select class="check_out_input" name="billing_city"><option value="">Select City</option></select>');
                }
            },
            error: function() {
                toastFire('error', 'An error occurred while fetching pincode data');
                $('input[name="billing_pin"]').css('borderColor', 'red').css('boxShadow', '0 0 0 0.2rem #dc34345c');
                $('input[name="billing_state"]').val('');
                $('input[name="billing_country"]').val('');
                $('#loadCities').html('<select class="check_out_input" name="billing_city"><option value="">Select City</option></select>');
            }
        });
    } else {
        $('input[name="billing_pin"]').css('borderColor', 'red').css('boxShadow', '0 0 0 0.2rem #dc34345c');
        $('input[name="billing_state"]').val('');
        $('input[name="billing_country"]').val('');
        $('#loadCities').html('<select class="check_out_input" name="billing_city"><option value="">Select City</option></select>');
    }
});

           

        // });

        // shipping pinode detail fetch
        $('input[name="shipping_pin"]').on('keyup', function() {
    var pincode = $(this).val();
    if (pincode.length === 6) {
        $('input[name="shipping_pin"]').css('borderColor', '#4caf50').css('boxShadow', '0 0 0 0.2rem #4caf5057');

        $.ajax({
            url: 'https://api.postalpincode.in/pincode/' + pincode,
            method: 'GET',
            success: function(result) {
                if (result[0].Message !== 'No records found') {
                    if (result[0].PostOffice && result[0].PostOffice.length > 0) {
                        let postOffices = result[0].PostOffice;

                        $('input[name="shipping_state"]').val(postOffices[0].State);
                        $('input[name="shipping_country"]').val(postOffices[0].Country);

                        let content = `
                            <div class="form-group">                       
                                <select class="form-control readonly_select active mt-4" name="shipping_city" readonly>
                                    <option value="" selected hidden>--Select City--</option>
                                   `;
                        
                        postOffices.forEach(function(postOffice) {
                            content += `<option value="${postOffice.Name}">${postOffice.Name}</option>`;
                        });

                        content += `</select>
                            </div>`;

                        $('#loadCities').html(content);
                        // $('.readonly_select.active').select2();

                        console.log(result);
                    }
                } else {
                    toastFire('warning', 'Enter valid pincode');
                    $('input[name="shipping_pin"]').css('borderColor', 'red').css('boxShadow', '0 0 0 0.2rem #dc34345c');
                    $('input[name="shipping_state"]').val('');
                    $('input[name="shipping_country"]').val('');
                    $('#loadCities').html('<select class="check_out_input" name="shipping_city"><option value="">Select City</option></select>');
                }
            },
            error: function() {
                toastFire('error', 'An error occurred while fetching pincode data');
                $('input[name="shipping_pin"]').css('borderColor', 'red').css('boxShadow', '0 0 0 0.2rem #dc34345c');
                $('input[name="shipping_state"]').val('');
                $('input[name="shipping_country"]').val('');
                $('#loadCities').html('<select class="check_out_input" name="shipping_city"><option value="">Select City</option></select>');
            }
        });
    } else {
        $('input[name="shipping_pin"]').css('borderColor', 'red').css('boxShadow', '0 0 0 0.2rem #dc34345c');
        $('input[name="shipping_state"]').val('');
        $('input[name="shipping_country"]').val('');
        $('#loadCities').html('<select class="check_out_input" name="shipping_city"><option value="">Select City</option></select>');
    }
});

    </script>
@endsection
