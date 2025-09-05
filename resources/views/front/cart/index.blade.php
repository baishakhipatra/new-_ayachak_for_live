@extends('layouts.app')

@section('page', 'Cart')

@section('content')
<style>
section.cart-header {
    position: static;
}
.cart-item.item-qty .qty-box a {
    width: 20px;
    height: 20px;
    border: none;
    background: #101010;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    padding: 3px;
}
.cart-item.item-qty .qty-box a:hover {
    background: #c10909;
}
.cart-item.item-qty .qty-box a svg {
    width: 14px;
    height: 14px;
}
.cart-summary-list li .coupon-block .coupon-text {
    width: 250px
}
.offer__img img {
    height: 35px;
    display: block;
    margin: 15px auto 30px;
}
</style>

@if(count($data) > 0)
<section class="cart-header mt-5 mb-3 mb-sm-5">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <h4>Shopping Cart</h4>
            </div>
            <div class="col-sm-12 mt-4 text-right">
                {{-- <ul class="cart-flow">
                    <li class="active"><a href="javascript: void(0)"><span>Cart</span></a></li>
                    <li><a href="javascript: void(0)"><span>Checkout</span></a></li>
                    <li><a href="javascript: void(0)"><span>Shipping</span></a></li>
                    <li><a href="javascript: void(0)"><span>Payment</span></a></li>
                </ul> --}}

                @if(count($cartOffers) > 0)
                    @foreach($cartOffers as $offerKey => $offer)
                        <div class="offer-holder mb-2 offer-holder-bg">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="offer-left-img">
                                    <img src="{{ asset('uploads/cart-offer/offer-bag-img.png') }}" alt="">
                                </div>
                                <div class="text-holder text-center">
                                    <h3>{!! $offer->offer_name !!}</h3>
                                    <p class="small moreCartAmountNeeded" id="moreCartAmountNeeded{{$offerKey}}"></p>
                                </div>
                                <div class="img-holder">
                                    <a href="javascript: void(0)" onclick="offerDetailModal({{$offerKey}})">
                                        <img src="{{ asset($offer->offer_image) }}" alt="...">
                                    </a>
                                </div>
                            </div>
                        </div>

                        {!! (!$loop->last) ? '<hr>' : '' !!}

                        <div class="modal fade cartOfferModal" id="CartOfferModal{{$offerKey}}" tabindex="-1" aria-labelledby="CartOfferModal{{$offerKey}}Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <div class="row justify-content-center text-center">
                                            <div class="col-12 col-md-10">
                                                <h3 class="offer__img">{!! $offer->offer_name !!}</h3>
                                            </div>
                                            <div class="col-12 text-center">
                                                <img src="{{ asset($offer->offer_image) }}" alt="...">
                                            </div>
                                            <div class="col-12 text-left">
                                                <div class="offerModalList">
                                                    <ul style="list-style: disc;" class="pl-4">
                                                    @foreach (explode(', ', $offer->offer_conditions) as $condition)
                                                        <li>{{$condition}}</li>
                                                    @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <button class="btn ok-btn" data-bs-dismiss="modal">close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
        </div>
    </div>
</section>

<section class="cart-wrapper">
    <div class="container">
        {{-- @if (Session::get('success'))
        <div class="alert alert-success">{{Session::get('success')}}</div>
        @endif
        @if (Session::get('failure'))
        <div class="alert alert-danger">{{Session::get('failure')}}</div>
        @endif --}}

        <div class="cart-holder">
            <div class="cart-row cart-row--header">
                <div class="cart-item item-thumb">Image</div>
                <div class="cart-item item-title">Name and Style</div>
                <div class="cart-item item-attr">Size</div>
                <div class="cart-item item-color">Color</div>
                <div class="cart-item item-price">Price</div>
                <div class="cart-item item-qty">Quantity</div>
                <div class="cart-item item-price">Amount</div>
                <div class="cart-item item-remove">Action</div>
            </div>

            @php
                $subTotal = $grandTotal = $couponCodeDiscount = $shippingCharges = $taxPercent = 0;

                // shipping charge fetch
                $shippingChargeJSON = json_decode($settings[22]->content);
                $minOrderAmount = $shippingChargeJSON->min_order;
                $shippingCharge = $shippingChargeJSON->shipping_charge;
			    $empshippingChargeJSON = json_decode($settings[24]->content);
                $minEmpOrderAmount = $empshippingChargeJSON->min_order;
                $empshippingCharge = $empshippingChargeJSON->shipping_charge;
            @endphp

            @foreach($data as $cartKey => $cartValue)
                <div class="cart-row">
                    <div class="cart-item item-thumb">
                        <figure>
                            <a href="{{ route('front.product.detail', $cartValue->productDetails->slug) }}" target="_blank">
                                <img src="{{asset($cartValue->product_image)}}">
                            </a>
                        </figure>
                    </div>
                    <div class="cart-item item-title">
                        <a href="{{ route('front.product.detail', $cartValue->productDetails->slug) }}" target="_blank">
                            <h4>{{$cartValue->product_name}}</h4>
                        </a>
                        <h6>Style #{{$cartValue->product_style_no}}</h6>
                    </div>
                    @if ($cartValue->cartVariationDetails)
                        <div class="cart-item item-attr">
                            <div class="cart-text">Size</div>
                            <h4>{{$cartValue->cartVariationDetails->sizeDetails->name}}</h4>
                        </div>
                    @endif
                    @if ($cartValue->cartVariationDetails)
                    <div class="cart-item item-color">
                        <div class="cart-text">Colour</div>
                        <h4>{{ucwords($cartValue->cartVariationDetails->colorDetails->name)}}</h4>
                    </div>
                    @endif
                    <div class="cart-item item-price">
                        <div class="cart-text">Price</div>
                        <h4>&#8377;{{$cartValue->offer_price}}</h4>
                    </div>
                    <div class="cart-item item-qty">
                        <div class="cart-text">Quantity</div>
                        <div class="qty-box">
                            <a href="{{ route('front.cart.quantity', [$cartValue->id, 'decr']) }}" class="decrement" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            </a>
                            <input class="counter" type="number" value="{{$cartValue->qty}}" readonly>
                            <a href="{{ route('front.cart.quantity', [$cartValue->id, 'incr']) }}" class="increment" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            </a>
                        </div>
                    </div>
                    <div class="cart-item item-price">
                        <div class="cart-text">Amount</div>
                        <h4>&#8377;{{$cartValue->offer_price * $cartValue->qty}}</h4>
                    </div>
                    <div class="cart-item item-remove">
                        <a href="{{route('front.cart.delete', $cartValue->id)}}"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg><!--<span>Remove</span>--></a>
                    </div>
                </div>

                @php
                    // subtotal calculation
                    $subTotal += (int) $cartValue->offer_price * $cartValue->qty;

                    // coupon code calculation
                    if (!empty($data[0]->coupon_code_id)) {
                        // 1 is coupon, else voucher
                        if (($data[0]->couponDetails->is_coupon == 1)) {
                            if($data[0]->couponDetails->type == 1){
                                $couponCodeDiscount = (int) ($subTotal * ($data[0]->couponDetails->amount / 100));
                            }else{
                                $couponCodeDiscount = (int) $data[0]->couponDetails->amount;
                            }
                        } else {
                            if($data[0]->couponDetails->type == 1){
                                $couponCodeDiscount = (int) ($subTotal * ($data[0]->couponDetails->amount / 100));
                            }else{
                                $couponCodeDiscount = (int) $data[0]->couponDetails->amount;
                            }
                        }
                    }

                    // grand total calculation
                    $grandTotalWithoutCoupon = $subTotal;
                    // $grandTotal = ($subTotal + $shippingCharges) - $couponCodeDiscount;
                    $grandTotal = $subTotal - $couponCodeDiscount;
                    if($grandTotal < 0){
                        $grandTotal = 0;
                    }
                @endphp
            @endforeach
        </div>
    </div>
    <div class="container mt-3 mt-sm-5">
        <div class="cart-summary">
            <div class="row justify-content-between flex-sm-row-reverse">
                <div class="col-md-6 col-lg-5">
                    <div class="w-100">
                        <div class="cart-total">
                            <div class="cart-total-label">
                                Subtotal
                            </div>
                            <div class="cart-total-value">
                                &#8377;<span id="subTotalAmount">{{$subTotal}}</span>
                            </div>
                        </div>

                        <div id="appliedCouponHolder" style="border-bottom: 1px solid #eee;">
                        @if (!empty($data[0]->coupon_code_id))
                            @if ($data[0]->couponDetails)
                                <div class="cart-total">
                                    <div class="cart-total-label">
                                        @php
                                            if (($data[0]->couponDetails->is_coupon == 1)) {
                                                $typeDisplay = 'COUPON';
                                                if ($data[0]->couponDetails->type == 1) {
                                                    $amountDisplay = '- '.$data[0]->couponDetails->amount.'%';
                                                }
                                                else {
                                                    $amountDisplay = '- &#8377 '.$data[0]->couponDetails->amount;
                                                }
                                            } else {
                                                $typeDisplay = 'VOUCHER';
                                                if ($data[0]->couponDetails->type == 1) {
                                                    $amountDisplay = '- '.$data[0]->couponDetails->amount.'%';
                                                }
                                                else {
                                                    $amountDisplay = '- &#8377 '.$data[0]->couponDetails->amount;
                                                }
                                            }
                                        @endphp
                                        {{ $typeDisplay }} APPLIED - <strong>{{$data[0]->couponDetails->coupon_code}}</strong><br/>
                                        <a href="javascript:void(0)" onclick="removeAppliedCoupon()"><small class="text-danger">Remove this {{ ($data[0]->couponDetails->is_coupon == 1) ? 'coupon' : 'voucher' }}</small></a>
                                    </div>
                                    <div class="cart-total-value">{!! $amountDisplay !!}</div>
                                </div>
                            @endif
                        @endif
                        </div>

                        <div class="cart-total">
                            <div class="cart-total-label">
                                Shipping Charges
                                <span id="shippingMore"></span>
                            </div>
                            <div class="cart-total-value">
                                @php
								$firstCode='';
                                 if (!empty($data[0]->coupon_code_id)) {
                                        $couponCode = substr($data[0]->couponDetails->coupon_code, 0, 3);
                                        $firstCode=$couponCode;
                                    }
                                if ($firstCode=='EMP'){
                                        if ((int) $minEmpOrderAmount >= (int) $grandTotal ) {
                                        $shippingCharges = $empshippingCharge;
                                        $grandTotal = $grandTotal + $shippingCharges;
                                       }
                                    
                                }else{
                                    if ((int) $minOrderAmount >= (int) $grandTotal ) {
                                        $shippingCharges = $shippingCharge;
                                        $grandTotal = $grandTotal + $shippingCharges;
                                    }
								}
                                @endphp
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

                        <div class="cart-total">
                            <div class="cart-total-label">
                                Total
                            </div>
                            <div class="cart-total-value">
                                <input type="hidden" value="{{$grandTotalWithoutCoupon}}" name="grandTotalWithoutCoupon">
                                &#8377;<span id="displayGrandTotal">{{$grandTotal}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-5">
                    <ul class="cart-summary-list">
                        <li>
                            <img src="{{asset('img/delivery-truck.png')}}" />
                            {{-- $minOrderAmount = $shippingChargeJSON->min_order;
                            $shippingCharge = $shippingChargeJSON->shipping_charge; --}}
							@if (!empty($data[0]->coupon_code_id))
                              @if ($firstCode=='EMP')
                              <h5><span>FREE</span> Shipping On order above &#8377;{{$minEmpOrderAmount}}</h5>
                              @else
                                <h5><span>FREE</span> Shipping On order above &#8377;{{$minOrderAmount}}</h5>
                              @endif
                            @else
                            <h5><span>FREE</span> Shipping On order above &#8377;{{$minOrderAmount}}</h5>
							@endif
                            {{-- <h5><span>FREE</span> Shipping for all orders</h5> --}}
                            {{-- <h5><span>&#8377;60</span> Apply Below order &#8377;499</h5> --}}
                            <a href="{{route('front.content.shipping')}}">See Shipping charges and policies</a>
                        </li>
                        <li>
                            <img src="{{asset('img/coupon.png')}}" />
                            <div class="coupon-block">
                                <input type="text" class="coupon-text" name="couponText" id="couponText" placeholder="Enter coupon code or voucher here" value="{{ (!empty($data[0]->coupon_code_id)) ? $data[0]->couponDetails->coupon_code : '' }}" {{ (!empty($data[0]->coupon_code_id)) ? 'disabled' : '' }}>
                                @if (!empty($data[0]->coupon_code_id))
                                    <button id="applyCouponBtn" style="background: #c1080a" disabled="true">Applied</button>
                                @else
                                    <button id="applyCouponBtn">Apply</button>
                                @endif
                            </div>
                            @error('lname')<p class="small text-danger mb-0 mt-2">{{$message}}</p>@enderror
                            <div class="w-100">
                                {{-- <a href="{{route('front.user.coupon')}}" class="d-inline-block mt-2">Get latest coupon from here</a> --}}
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row justify-content-between">
                <div class="col-sm-12 text-right mt-4">
                    <form action="{{route('front.checkout.index')}}" method="GET">
                        <button type="submit" class="btn checkout-btn">Proceed to checkout</button>
                    </form>
                </div>
            </div>
        </div>
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

@else
<section class="cart-header mb-3 mb-sm-5"></section>
<section class="cart-wrapper">
    <div class="container">
        <div class="complele-box">
            <figure>
                <img src="{{asset('img/empty-cart.png')}}" height="100">
            </figure>
            <figcaption>
                <h2>Your cart is empty</h2>
                <a href="{{route('front.home')}}">Back to Home</a>
            </figcaption>
        </div>
    </div>
</section>
@endif

@endsection

@section('script')
    <script>
        // cart page coupon
        $('#applyCouponBtn').on('click', (e) => {
            e.preventDefault()
            let couponCode = $('input[name="couponText"]').val();
            if (couponCode.length > 0) {
                $.ajax({
                    url: '{{ route('front.cart.coupon.check') }}',
                    method: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        code: couponCode
                    },
                    beforeSend: function() {
                        $('#applyCouponBtn').text('Checking');
                        // $('#applyCouponBtn').text('Checking').attr('disabled', true);
                    },
                    success: function(result) {
                        // console.log(result);

                        if (result.type == 'success') {
                            // console.log(result);
                            $('#applyCouponBtn').text('APPLIED').css('background', '#c1080a').attr('disabled', true);

                            $('input[name="couponText"]').attr('disabled', true);
                            let beforeCouponValue = parseInt($('#displayGrandTotal').text());
                            let couponDiscount = parseInt(result.amount);
                            // let discountedGrandTotal = beforeCouponValue - couponDiscount;
                            // $('#displayGrandTotal').text(discountedGrandTotal);

                            // coupon/ voucher type
                            let amountToDisplay = '';
                            if (result.is_coupon == 'voucher') {
                                let discountedGrandTotal = 0;
                                if(result.coupon_type == 1){
                                    amountToDisplay = '- '+result.amount+'%';
                                    discountedGrandTotal = Math.ceil(beforeCouponValue * ((100-couponDiscount)/100));
                                }else{
                                    amountToDisplay = '- &#8377; '+result.amount;
                                    discountedGrandTotal = beforeCouponValue - couponDiscount;
                                }
                                
                                if (discountedGrandTotal < 0) {
                                    $('#displayGrandTotal').text(0);
                                }else{
                                    $('#displayGrandTotal').text(discountedGrandTotal);
                                }
                            } else {
                                let discountedGrandTotal = 0;
                                if(result.coupon_type == 1){
                                    amountToDisplay = '- '+result.amount + '%';
                                    discountedGrandTotal = Math.ceil(beforeCouponValue - ((beforeCouponValue * couponDiscount)/100));
                                }else{
                                    amountToDisplay = '- &#8377; '+result.amount;
                                    discountedGrandTotal = beforeCouponValue - couponDiscount;
                                }

                                if (discountedGrandTotal < 0) {
                                    $('#displayGrandTotal').text(0);
                                }else{
                                    $('#displayGrandTotal').text(discountedGrandTotal);
                                }
                            }

                            let couponContent = `
                            <div class="cart-total">
                                <div class="cart-total-label">
                                    ${result.is_coupon} APPLIED - <strong>${couponCode}</strong><br/>
                                    <a href="javascript:void(0)" onclick="removeAppliedCoupon()"><small class="text-danger">Remove this ${result.is_coupon}</small></a>
                                </div>
                                <div class="cart-total-value">${amountToDisplay}</div>
                            </div>
                            `;

                            $('#appliedCouponHolder').html(couponContent);
                            toastFire(result.type, result.message);
                            location.href="{{url()->current()}}";
                        } else {
                            toastFire(result.type, result.message);
                            $('#applyCouponBtn').text('Apply');
                        }
                    }
                });
            }
        });

        // offer detail modal
        function offerDetailModal(id) {
            $('#CartOfferModal'+id).modal('show');
        }

        // elligible offer modal
        function elligibleOfferDetail(image, orderMultiplier, productName, productValue, productQty, cartAmount, minOfferAmount) {
            let receiveQty = productQty;
            let receiveWorth = productValue;
            let productQtys = '';
            $('#offerContent img').prop('src', image);

            // ACTIVE multiplier
            if (orderMultiplier == 1) {
                receiveQty = parseInt(cartAmount / minOfferAmount) * productQty;
                receiveWorth = parseInt(receiveWorth * receiveQty);
                if (receiveQty > 1) { productQtys = 's'; }
            }

            $('#offerContent h5').html('Congrats ! You will get <span>'+receiveQty+' '+productName+productQtys+'</span> worth &#8377;'+receiveWorth+' on this order');
            $('#userOfferModal').modal('show');
        }

        // offer calculation
        @if(count($cartOffers) > 0)
            // checking total cart amount - without coupon
            // const grandTotal = $('#displayGrandTotal').text();
            const grandTotal = $('input[name="grandTotalWithoutCoupon"]').val();
            // success svg
            const checkSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg>';
            const infoSvg = '<svg xmlns="http://www.w3.org/2000/svg" class="mr-1" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>';
            // count elligible offers
            let elligibleOfferCount = 1;

            // all offers
            @foreach($cartOffers as $offerKey => $offer)
                // check if offer is applicable
                if (parseInt({{$offer->min_cart_order}}) > grandTotal) {
                    // OFFER NOT APPLICABLE
                    const moreAmount{{$offerKey}} = parseInt({{$offer->min_cart_order}} - grandTotal);
                    const moreAmountHtml{{$offerKey}} = `Add <strong>&#8377;${moreAmount{{$offerKey}}}</strong> more to cart to avail this offer`;
                    $('#moreCartAmountNeeded'+{{$offerKey}}).html(moreAmountHtml{{$offerKey}});
                } else {
                    // OFFER APPLICABLE
                    if (elligibleOfferCount == 1) {
                        // in case of multiple elligible offers, use the max valued one
                        @if (!empty($data[0]->coupon_code_id))
                            // if coupon applied
                            const moreAmountHtml{{$offerKey}} = `<a href="javascript:void(0)" onclick="removeAppliedCoupon()" class="text-danger">${infoSvg}<strong>Remove coupon to avail this offer !</strong></a>`;
                            $('#moreCartAmountNeeded'+{{$offerKey}}).html(moreAmountHtml{{$offerKey}});
                        @else
                            let receiveQty = {{$offer->offer_product_qty}};
                            let offerWorth = {{$offer->offer_product_value}};
                            let productQtys = '';
                            // ACTIVE multiplier
                            if ({{$offer->min_order_multiplier}} == 1) {
                                receiveQty = parseInt(grandTotal / parseInt({{$offer->min_cart_order}})) * {{$offer->offer_product_qty}};
                                offerWorth = {{$offer->offer_product_value}} * receiveQty;
                                if (receiveQty > 1) { productQtys = 's'; }
                            }

                            // id coupon not applied
                            const moreAmountHtml{{$offerKey}} = `${checkSvg}<strong>Congrats !</strong> You will get ${receiveQty} {{$offer->offer_product_name}}${productQtys} worth &#8377;${offerWorth} on this order. <a href="javascript: void(0)" onclick="elligibleOfferDetail('{{asset($offer->offer_image)}}', {{$offer->min_order_multiplier}}, '{{$offer->offer_product_name}}', {{$offer->offer_product_value}}, {{$offer->offer_product_qty}}, ${grandTotal}, parseInt({{$offer->min_cart_order}}))">See details !</a>`;
                            $('#moreCartAmountNeeded'+{{$offerKey}}).addClass('text-success').html(moreAmountHtml{{$offerKey}});
                        @endif
                    } else {
                        // in case of multiple elligible offers, show min valued one
                        const moreAmountHtml{{$offerKey}} = `You are already elligible for another offer`;
                        $('#moreCartAmountNeeded'+{{$offerKey}}).html(moreAmountHtml{{$offerKey}});
                    }
                    elligibleOfferCount++;
                }
            @endforeach
        @endif

        // more shipping charge
        function shippingChargeShow() {
            
            var cartSubAmount = $('#subTotalAmount').text();

            @if(count($data) > 0)
                @if (!empty($data[0]->coupon_code_id))
                    @php
                    if (($data[0]->couponDetails->is_coupon == 1)) {
                        if($data[0]->couponDetails->type == 1){
                            $couponCodeDiscount = (int) ($subTotal * ($data[0]->couponDetails->amount / 100));
                        }else{
                            $couponCodeDiscount = (int) $data[0]->couponDetails->amount;
                        }
                    } else {
                        if($data[0]->couponDetails->type == 1){
                            $couponCodeDiscount = (int) ($subTotal * ($data[0]->couponDetails->amount / 100));
                        }else{
                            $couponCodeDiscount = (int) $data[0]->couponDetails->amount;
                        }
                    }
					$firstCode='';
                    $grandTotal = $subTotal - $couponCodeDiscount;
			        $couponCode = substr($data[0]->couponDetails->coupon_code, 0, 3);
                    $firstCode=$couponCode;
                    @endphp
					if($firstCode='EMP'){
                            if ( parseInt({{$minEmpOrderAmount}}) > parseInt({{$grandTotal}})) {
                                var amountDiff = parseInt({{$minEmpOrderAmount}}) - parseInt({{$grandTotal}});
                                $('#shippingMore').html('<small class="d-block mb-0">Add &#8377;'+amountDiff+' more for FREE Shipping.</small>');
                            } else {
                                $('#shippingMore').html('<small class="d-block mb-0">You are elligible for FREE Shipping.</small>');
                            }
                    }else{
                    if ( parseInt({{$minOrderAmount}}) > parseInt({{$grandTotal}})) {
                        var amountDiff = parseInt({{$minOrderAmount}}) - parseInt({{$grandTotal}});
                        $('#shippingMore').html('<small class="d-block mb-0">Add &#8377;'+amountDiff+' more for FREE Shipping.</small>');
                    } else {
                        $('#shippingMore').html('<small class="d-block mb-0">You are elligible for FREE Shipping.</small>');
                    }
				}
                // if coupon not applied
                @else
                    if ( parseInt({{$minOrderAmount}}) > parseInt(cartSubAmount)) {
                        var amountDiff = parseInt({{$minOrderAmount}}) - parseInt(cartSubAmount);
                        $('#shippingMore').html('<small class="d-block mb-0">Add &#8377;'+amountDiff+' more for FREE Shipping.</small>');
                    } else {
                        $('#shippingMore').html('<small class="d-block mb-0">You are elligible for FREE Shipping.</small>');
                    }
                @endif
            @endif
        }

        shippingChargeShow()
    </script>
@endsection