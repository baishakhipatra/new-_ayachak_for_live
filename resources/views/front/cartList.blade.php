@extends('front.layout.app')
@section('page-title', 'cart list')
@section('content')

<section class="main">
    <div class="container">
        <div class="cart-wrap">
            <h2 class="section-heading">Shopping Cart</h2>
            <ul class="breadcrumb breadcrumb-white mt-4">
                <li><a href="{{route('front.home')}}">Home</a></li>
                <li>Cart</li>
            </ul>
        </div>
        <div class="cart-form-wrap">
            <div class="row justify-content-between">
                <div class="col-lg-8 mb-4 mb-md-5 mb-lg-0">
                    <div class="cart-item-wrap">
                        <ul class="cart-item-list">
                            @forelse($cartItems as $item)
                                <li>
                                    <div class="inner-wrap">
                                        <figure>
                                            <img src="{{ asset($item->productDetails->image ?? 'assets/images/placeholder-product.jpg') }}" alt="">
                                        </figure>
                                        <figcaption>
                                            <div class="product-details-cart">
                                                <a href="#"><h3>{{ ucwords($item->productDetails->name) }}</h3></a>
                                                <div class="pro-meta">
                                                    <span>Category:</span> {{ $item->productDetails->category->name ?? '-' }}
                                                </div>
                                                <div class="pro-meta">
                                                    <span>Weight:</span> {{ $item->variation->weight ?? '-' }}
                                                </div>
                                                @if($item->productDetails->gst)
                                                <div class="pro-meta">
                                                    <span>GST:</span> {{ $item->productDetails->gst ?? 0 }}%
                                                </div>
                                                @endif
                                                <div class="number-input" data-id="{{ $item->id }}" data-price="{{ $item->offer_price > 0 ? $item->offer_price : $item->price }}">
                                                    <button type="button" class="decrement">-</button>
                                                    <input type="number" class="quantity" name="quantity" min="1" max="10" value="{{ $item->qty }}" step="1" readonly>
                                                    <button type="button" class="increment">+</button>
                                                </div>
                                                <a href="javascript:void(0);" 
                                                    class="remove-item" 
                                                    data-id="{{ $item->id }}">
                                                    Remove From Cart
                                                </a>
                                                {{-- <a href="{{ route('cart.remove', $item->id) }}" class="remove">Remove</a> --}}
                                            </div>
                                            <span class="cart-price">₹<span class="price-amount">{{ number_format(($item->offer_price > 0 ? $item->offer_price : $item->price) * $item->qty, 2) }}</span></span>
                                        </figcaption>
                                    </div>
                                </li>
                            @empty
                                <li><p>Your cart is empty.</p></li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="cart-value-wrap">
                        <h3>Cart Total</h3>
                        <div class="coupon-area">
                            <label>Add a Coupon</label>
                            <div class="input-group">
                                <input type="text" id="coupon_code" class="form-control" placeholder="Enter coupon">
                                <button type="button" id="apply_coupon" class="btn btn-primary">Apply</button>
                            </div>
                            <small id="coupon_message" class="text-success"></small>
                        </div>
                        <div class="cart-row">
                            <span>Subtotal</span>
                            @php
                               // $subtotal = $cartItems->sum(fn($item) => $item->qty * $item->offer_price);
                                $subtotal = $cartItems->sum(function($item) {
                                    $price = $item->offer_price > 0 ? $item->offer_price : $item->price;
                                    return $item->qty * $price;
                                });
                            @endphp
                            <span class="subtotal-amount">₹{{ number_format($subtotal, 2) }}</span>
                        </div>

                        <div class="cart-row">
                            <span>Shipping</span>
                            FREE
                        </div>

                        <div class="cart-row" id="discount_row" style="{{ isset($coupon) ? '' : 'display: none;' }}">
                            <span>
                                Discount
                                <a href="javascript:void(0)" id="remove_coupon" class="text-success" style="font-size: 12px; margin-left: 8px;">
                                    Remove
                                </a>
                            </span>
                            <span class="discount-amount">
                                @if(isset($coupon) && $coupon)
                                    @if($coupon->type == 1)
                                        - {{ $coupon->amount }}% (₹{{ number_format($totalDiscount, 2) }})
                                    @elseif($coupon->type == 2)
                                        - ₹{{ number_format($totalDiscount, 2) }} (Flat)
                                    @endif
                                @else
                                    - ₹0.00
                                @endif
                            </span>
                        </div>

                        <div class="cart-total">
                            <span>Total</span>
                            @php
                                $grandTotal = $subtotal - ($totalDiscount ?? 0);
                                if ($grandTotal < 0) $grandTotal = 0;
                            @endphp
                            <span class="total-amount">₹{{ number_format($grandTotal, 2) }}</span>
                        </div>


                       
                        <form action="{{ route('front.cart.add_to_checkoout') }}" method="POST">
                            @csrf 
                            <input type="hidden" name="coupon_amount" id="applied_coupon_amount" value="">
                            <input type="hidden" name="coupon_id" id="applied_coupon_id" value="">
                            <input type="hidden" id="applied_coupon_type" value="">
                            <input type="hidden" id="applied_coupon_value" value="">
                            <div class="checkout-warning-container">
                                @if($checkoutRestricted)
                                    <div class="alert alert-danger checkout-warning mt-3">
                                        You cannot order Books, Medicines, and Waters together. Please order them separately.
                                    </div>
                                @endif
                            </div>
                            <button type="submit" class="bton btn-full mt-5" {{ $checkoutRestricted ? 'disabled' : '' }}>Proceed to Checkout</button>
                        </form>
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


    
    });

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

        
        document.querySelectorAll(".decrement").forEach(button => {
            button.addEventListener("click", (e) => {
                e.preventDefault();
                const input = button.closest('.number-input').querySelector(".quantity");
                input.stepDown();
            });
        });
    });


    // function recalculateCartTotals() {
    //     let subtotal = 0;
    //     $('.price-amount').each(function () {
    //         subtotal += parseFloat($(this).text().replace(/,/g, ''));
    //     });

    //     $('.subtotal-amount').text('₹' + subtotal.toFixed(2));
    //     $('.total-amount').text('₹' + subtotal.toFixed(2));
    // }

    function recalculateCartTotals() {
        let subtotal = 0;
        $('.price-amount').each(function () {
            subtotal += parseFloat($(this).text().replace(/,/g, '')) || 0;
        });

        // read applied coupon info
        const cType  = ($('#applied_coupon_type').val() || '').toString(); // "1" or "2"
        const cValue = parseFloat($('#applied_coupon_value').val()) || 0;

        let discount = 0;

        if (cType === '1') {
            // Percentage
            discount = (subtotal * cValue) / 100;
            $('#discount_row').show();
            $('.discount-amount').html(`- ${cValue}% (₹${discount.toFixed(2)})`);
        } else if (cType === '2') {
            // Flat
            discount = cValue;
            $('#discount_row').show();
            $('.discount-amount').html(`- ₹${discount.toFixed(2)} (Flat)`);
        } else {
            // No coupon
            $('#discount_row').hide();
            $('.discount-amount').text('- ₹0.00');
        }

        $('.subtotal-amount').text('₹' + subtotal.toFixed(2));
        const total = Math.max(subtotal - discount, 0);
        $('.total-amount').text('₹' + total.toFixed(2));
    }

    function toggleCheckoutWarning(isRestricted) {
        let warningBox = $(".checkout-warning");
        let checkoutBtn = $(".bton.btn-full");

        if (isRestricted) {
            if (warningBox.length === 0) {
                $(".cart-value-wrap form").prepend(`
                    <div class="alert alert-danger checkout-warning mt-3">
                        You cannot order Books, Medicines, and Waters together. Please order them separately.
                    </div>
                `);
            }
            checkoutBtn.prop("disabled", true);
        } else {
            warningBox.remove();
            checkoutBtn.prop("disabled", false);
        }
    }



    $(document).ready(function () {
        $(document).on('click', '.increment, .decrement', function () {
            let parent = $(this).closest('.number-input');
            let input = parent.find('.quantity');
            let itemId = parent.data('id');
            let unitPrice = parseFloat(parent.data('price'));

            let type = $(this).hasClass('increment') ? 'increment' : 'decrement';

            $.ajax({
                url: "{{ route('front.cart.update-quantity') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    cart_id: itemId,
                    type: type
                },
                success: function (res) {
                    if (res.success) {
                        input.val(res.updated_qty);
                        let newTotal = unitPrice * res.updated_qty;
                        parent.closest('figcaption').find('.price-amount').text(newTotal.toFixed(2));
                        recalculateCartTotals();
                    } else {
                        toastr.warning("Could not update quantity");
                    }
                },
                error: function () {
                    toastr.error("Something went wrong while updating quantity.");
                }
            });
        });

        $(document).on('click', '.remove-item', function (e) {
            e.preventDefault();

            let cartId = $(this).data('id');
            let $row = $(this).closest('li');

            $.ajax({
                url: "{{ route('front.cart.remove-quantity') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    cart_id: cartId
                },
                success: function (res) {
                    if (res.success) {
                        $row.remove();
                        toastr.success("Item removed from cart");
                        recalculateCartTotals();
                        toggleCheckoutWarning(res.checkout_restricted);
                    } else {
                        toastr.warning(res.message || "Failed to remove item");
                    }
                },
                error: function () {
                    toastr.error("Something went wrong while removing the item");
                }
            });
        });
    });



    $(document).on('click', '#apply_coupon', function () {
        let code = $('#coupon_code').val().trim();
        if (!code) {
            $('#coupon_message').html('<span class="text-danger">Please enter a coupon code</span>');
            return;
        }

        $.ajax({
            url: "{{ route('front.cart.coupon.check') }}",
            type: "POST",
            data: { _token: "{{ csrf_token() }}", code },
            success: function(res){
                if (res && res.type === 'success') {
                    $('#coupon_message').html('<span class="text-success">'+res.message+'</span>');

                    // set id, type, value
                    $('#applied_coupon_id').val(res.id || '');
                    $('#applied_coupon_type').val((res.coupon_type ?? '').toString());
                    $('#applied_coupon_value').val(res.coupon_value ?? 0);

                    // show the row and recalc from live subtotal
                   // $('#discount_row').show();
                        let discount = parseFloat(res.coupon_discount) || 0;
                        $('#applied_coupon_amount').val(discount.toFixed(2));
                    recalculateCartTotals();
                } else {
                    $('#coupon_message').html('<span class="text-danger">'+(res?.message || 'Unable to apply coupon')+'</span>');
                }
            },
            error: function(xhr){
                const msg = xhr.status === 419 ? 'Session expired. Refresh and try again.' : 'Something went wrong';
                $('#coupon_message').html('<span class="text-danger">'+msg+'</span>');
            }
        });
    });


    $(document).on('click', '#remove_coupon', function () {
        $.ajax({
            url: "{{ route('front.cart.coupon.remove') }}",
            type: "POST",
            data: { _token: "{{ csrf_token() }}" },
            success: function(res){
                if (res && res.type === 'success') {
                    $('#coupon_message').html('<span class="text-success">'+res.message+'</span>');
                    // clear stored coupon info
                    $('#applied_coupon_id').val('');
                    $('#applied_coupon_type').val('');
                    $('#applied_coupon_value').val('');
                    $('#coupon_code').val('');
                    recalculateCartTotals();
                } else {
                    $('#coupon_message').html('<span class="text-danger">'+(res?.message || 'Failed to remove coupon')+'</span>');
                }
            },
            error: function(xhr){
                const msg = xhr.status === 419 ? 'Session expired. Refresh and try again.' : 'Something went wrong';
                $('#coupon_message').html('<span class="text-danger">'+msg+'</span>');
            }
        });
    });
     
</script>

@endsection

























{{-- @extends('front.layout.app')
   @section('content')
   @php
       $total_gst_amount = 0;
   @endphp
    <section class="cart_page_table">
        <div class="container">
            <form action="{{route('front.cart.add_to_checkoout')}}" method="POST" id="checkout_form">
                @csrf
                <div class="row">
                    <div class="col-lg-8">
                        <div class="cart_table">
                            <div class="table-responsive">
                                <table class="table  cart_table_sec">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Product Name</th>
                                            <th>Qty</th>
                                            <th>Size</th>
                                            <th>Color</th>
                                            <th>Price</th>
                                            <th>Voucher Coupon</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($cartProductDetails)
                                            @forelse($cartProductDetails as $key=>$item)
                                                <tr>
                                                    <td>
                                                        <div class="table_img">
                                                            <img src="{{asset($item->product_image)}}" alt="" width="30%">
                                                            <input type="hidden" name="images[]" value="{{asset($item->product_image)}}">
                                                        </div>
                                                    </td>
                                                    <td>{{$item->productDetails?$item->productDetails->name:""}}</td>
                                                    <td>{{$item->qty}}</td>
                                                    <td>{{$item->cartVariationDetails?$item->cartVariationDetails->size_name:""}}</td>
                                                    <td>{{$item->cartVariationDetails?$item->cartVariationDetails->color_name:""}}</td>
                                                    <td class="amount_b">
                                                        ₹{{$item->offer_price?$item->offer_price:$item->price}}
                                                        @php
                                                            $price = $item->offer_price?$item->offer_price:$item->price;
                                                        @endphp
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="variation[]" value="{{$item->product_variation_id}}">
                                                        <select class="coupon_code" name="coupons[]">
                                                            <option value="" data-amount="0" data-id="" selected>Select coupon</option>
                                                            @foreach($couponData as $coupon)
                                                                <option value="{{$coupon->coupon_code}}" data-amount="{{$coupon->amount}}" data-id="{{$coupon->id}}">{{$coupon->coupon_code}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <a href="javascript:void(0);" class="delete-btn" data-id="{{ $item->id }}" data-toggle="tooltip" title="Delete"><svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M3 6H5H21" stroke="#EA0029" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                                <path
                                                                    d="M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6"
                                                                    stroke="#EA0029" stroke-width="2" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                                <path d="M10 11V17" stroke="#EA0029" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                                <path d="M14 11V17" stroke="#EA0029" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="7" class="text-center"><strong>Your cart is empty</strong></td>
                                                </tr>
                                            @endforelse
                                        @endif   
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <a href="{{asset('/')}}" class="orderplace">Return to Shop</a>
                    </div>
                    <div class="col-lg-4">
                        <div class="cart_table_total">
                            <div class="cart_table_shipping">
                                <div class="cart_table_total_text">
                                    <h4>Subtotal <span>Including GST</span></h4>
                                    <h5>₹ <span id="sub_total">{{number_format($total_price_excluding, 2, '.', '')}}</span></h5>
                                    <input type="hidden" name="final_sub_total" id="final_sub_total" value="0">
                                </div>
                            </div>
                            <input type="hidden" name="final_gst_amount" id="final_gst_amount" value="0">
                            <div class="cart_table_shipping">
                                <div class="cart_table_total_text">
                                    <h4>Voucher Coupon</h4>
                                    <h5 class="apply_coupon">- ₹ <span id="coupon_amount">0.00</span></h5>
                                    <input type="hidden" name="final_coupon_amount" id="final_coupon_amount" value="0">
                                </div>
                            </div>
                            <div class="cart_table_shipping">
                                <div class="cart_table_total_text">
                                    <h4>Total</h4>
                                    <h5>₹ <span id="total_amount">{{number_format($total_price_excluding, 2, '.', '')}}</span></h5>
                                    <input type="hidden" name="final_total_amount" id="final_total_amount" value="0">
                                </div>
                            </div>
                        </div>
                        
                        @if(count($cartProductDetails)>0)
                            <div class="proceed_tocheck">
                                <button type="submit" class="proceed_tocheck_btn">Proceed to Checkout</button>
                            </div>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </section>
 
    @endsection
   
   @section('script')
   <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

   <script>
     function CouponApply() {
            var selectedValue = $(this).val();
            var selectedAmount = $(this).find(':selected').attr('data-amount');
            selectedAmount = parseFloat(selectedAmount);
            if (!selectedValue) {
                return;
            }
            
            var alreadySelected = false;


            $('.coupon_code').not(this).each(function() {
                if ($(this).val() === selectedValue) {
                    alreadySelected = true;
                    return false; 
                }
            });

            if (alreadySelected) {
                Swal.fire({
                    title: "This coupon is already used in another product, please select a different coupon.",
                    showClass: {
                        popup: `
                        animate__animated
                        animate__fadeInUp
                        animate__faster
                        `
                    },
                    hideClass: {
                        popup: `
                        animate__animated
                        animate__fadeOutDown
                        animate__faster
                        `
                    }
                    });
                $(this).val(''); 
            } else {
                $('.coupon_code').each(function() {
                    $(this).find('option').each(function() {
                        if ($(this).val() === selectedValue) {
                        } else {
                        }
                    });
                });
            }
        }

        function updateTotalDiscount() {
            var totalDiscount = 0.00;
            var coupon_amount = 0.00;
            $('.coupon_code').each(function() {
                var selectedAmount = $(this).find(':selected').attr('data-amount');
                if (selectedAmount) {
                    totalDiscount += parseFloat(selectedAmount);
                }
            });
            $('#coupon_amount').text(totalDiscount.toFixed(2)); 
            var coupon_amount = $('#coupon_amount').text(); 
            var gst_amount = $('#gst_amount').text(); 
            var sub_total = $('#sub_total').text(); 
            var total_amount = parseFloat(sub_total);
            var total_amount = parseFloat(sub_total)-parseFloat(coupon_amount);
            var final_amount = parseFloat(total_amount);
            $('#total_amount').text(final_amount.toFixed(2)); 
            $('#final_sub_total').val(sub_total); 
            $('#final_coupon_amount').val(coupon_amount); 
            $('#final_total_amount').val(final_amount); 
        }
        $(document).ready(function() {
            $('.coupon_code').on('change', CouponApply);
            setInterval(updateTotalDiscount, 1000);
        });
       $(document).ready(function() {
           $('.delete-btn').click(function() {
               var itemId = $(this).data('id');
               Swal.fire({
                   title: 'Are you sure?',
                   text: "You won't be able to revert this!",
                   icon: 'warning',
                   showCancelButton: true,
                   confirmButtonColor: '#d33',
                   cancelButtonColor: '#3085d6',
                   confirmButtonText: 'Yes, delete it!'
               }).then((result) => {
                   if (result.isConfirmed) {
                       window.location.href = '../cart/delete/' + itemId; 
                   }
               });
           });
       });
   </script>
   @endsection --}}