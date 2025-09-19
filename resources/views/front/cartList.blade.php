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
                                    <div class="inner-wrap {{ $item->is_out_of_stock ? 'bg-light border-danger' : '' }}">
                                        <figure>
                                             <a href="{{ route('front.shop.detail', $item->productDetails->slug) }}">
                                                <img src="{{ asset($item->productDetails->image ?? 'assets/images/placeholder-product.jpg') }}" alt="">
                                            </a>
                                            {{-- <img src="{{ asset($item->productDetails->image ?? 'assets/images/placeholder-product.jpg') }}" alt=""> --}}
                                        </figure>
                                        <figcaption>
                                            <div class="product-details-cart">
                                                <a href="{{ route('front.shop.detail', $item->productDetails->slug) }}"><h3>{{ ucwords($item->productDetails->name) }}</h3></a>
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

                                                @if($item->is_out_of_stock)
                                                    <div class="alert alert-danger mt-2 p-1">Out of Stock</div>
                                                @else
                                                    <div class="number-input" data-id="{{ $item->id }}" 
                                                        data-price="{{ $item->offer_price > 0 ? $item->offer_price : $item->price }}"
                                                        data-stock = "{{$item->variation->stock ?? 0}}">
                                                        <button type="button" class="decrement">-</button>
                                                        <input type="number" class="quantity" name="quantity" min="1" max="{{ $item->variation->stock }}" value="{{ $item->qty }}" step="1" readonly>
                                                        <button type="button" class="increment">+</button>
                                                    </div>

                                                    <small class="stock-warning text-danger" style="display: none;">
                                                        Only {{ $item->variation->stock ?? 0 }} products available.
                                                    </small>
                                                @endif

                                                <a href="javascript:void(0);" 
                                                    class="remove-item" 
                                                    data-id="{{ $item->id }}">
                                                    Remove From Cart
                                                </a>
                                                {{-- <a href="{{ route('cart.remove', $item->id) }}" class="remove">Remove</a> --}}
                                            </div>
                                            <span class="cart-price">₹<span class="price-amount">{{ $item->is_out_of_stock ? '0.00' :number_format(($item->offer_price > 0 ? $item->offer_price : $item->price) * $item->qty, 2) }}</span></span>
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
                                <button type="button" id="apply_coupon" class="mt-2 mb-2 btn btn-primary">Apply</button>
                            </div>
                            <small id="coupon_message"></small>
                        </div>
                        <div class="cart-row">
                            <span>Subtotal</span>
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
                            <div class="checkout-warning-container">
                                @if($hasOutOfStock)
                                    <div class="alert alert-warning mb-3">
                                        Some items are out of stock. Please remove them from cart.
                                    </div>
                                @endif
                            </div>
                            <button type="submit" class="bton btn-full mt-5" {{ ($checkoutRestricted || $hasOutOfStock) ? 'disabled' : '' }}>Proceed to Checkout</button>
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

    function recalculateCartTotals() {
        let subtotal = 0;
        $('.price-amount').each(function () {
            subtotal += parseFloat($(this).text().replace(/,/g, '')) || 0;
        });

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
            let stock = parseInt(parent.data('stock')) || 0;
            let itemId = parent.data('id');
            let unitPrice = parseFloat(parent.data('price'));
            let stockWarning = parent.siblings('.stock-warning');
            let type = $(this).hasClass('increment') ? 'increment' : 'decrement';

            // Current quantity
            let currentQty = parseInt(input.val()) || 1;

            // Stock check
            if (type === 'increment' && currentQty >= stock) {
                stockWarning.show().text(`Only ${stock} items available in stock.`);
                return;
            } else {
                stockWarning.hide();
            }

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
                        toastr.warning(res.message || "Could not update quantity");
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
            $('#coupon_message').html('<span class="bg-light p-1 text-danger">Please enter a coupon code</span>');
            return;
        }

        $.ajax({
            url: "{{ route('front.cart.coupon.check') }}",
            type: "POST",
            data: { _token: "{{ csrf_token() }}", code },
            success: function(res){
                if (res && res.type === 'success') {
                    $('#coupon_message').html('<span class="bg-light p-1 text-success">'+res.message+'</span>');

                    
                    $('#applied_coupon_id').val(res.id || '');
                    $('#applied_coupon_type').val((res.coupon_type ?? '').toString());
                    $('#applied_coupon_value').val(res.coupon_value ?? 0);

                    let discount = parseFloat(res.coupon_discount) || 0;
                    $('#applied_coupon_amount').val(discount.toFixed(2));
                    recalculateCartTotals();
                } else {
                    $('#coupon_message').html('<span class="bg-light p-1 text-danger">'+(res?.message || 'Unable to apply coupon')+'</span>');
                }
            },
            error: function(xhr){
                const msg = xhr.status === 419 ? 'Session expired. Refresh and try again.' : 'Something went wrong';
                $('#coupon_message').html('<span class="bg-light p-1 text-danger">'+msg+'</span>');
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
                    $('#coupon_message').html('<span class="bg-light p-1 text-success">'+res.message+'</span>');
                    // clear stored coupon info
                    $('#applied_coupon_id').val('');
                    $('#applied_coupon_type').val('');
                    $('#applied_coupon_value').val('');
                    $('#coupon_code').val('');
                    recalculateCartTotals();
                } else {
                    $('#coupon_message').html('<span class="bg-light p-1 text-danger">'+(res?.message || 'Failed to remove coupon')+'</span>');
                }
            },
            error: function(xhr){
                const msg = xhr.status === 419 ? 'Session expired. Refresh and try again.' : 'Something went wrong';
                $('#coupon_message').html('<span class="bg-light p-1 text-danger">'+msg+'</span>');
            }
        });
    });
     
</script>

@endsection
