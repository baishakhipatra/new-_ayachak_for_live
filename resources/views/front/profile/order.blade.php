@extends('front.profile.layouts.app')

@section('profile-content')
<style>
    section.cart-wrapper {
        position: static;
    }
    .fs-14 {
        font-size: 14px;
        line-height: 1;
    }
</style>

<div class="col-sm-7">
    <div class="profile-card">
        <h3>Order History</h3>

        @forelse($data as $orderKey => $orderValue)
            @php
            $orderSTatus = "Unknown";
            switch($orderValue->status) {
                case 1: $orderSTatus = "Processing";break;
                case 2: $orderSTatus = "Confirmed";break;
                case 3: $orderSTatus = "Shipped";break;
                case 4: $orderSTatus = "Delivered";break;
                case 5: $orderSTatus = "Cancelled";break;
                default: $orderSTatus = "Unknown";break;
            }
            @endphp

            <div class="order-card">
                <div class="order-card-header">
                    <figure>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-package"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                    </figure>

                    <figcaption>
                        <h5 class="{{ ($orderSTatus == "Cancelled" ? 'text-danger' : 'text-success') }}">Status : {{$orderSTatus}}</h5>
                        <p>Ordered On {{date('D, j M Y', strtotime($orderValue->created_at))}}</p>
                    </figcaption>

                    @if ($orderValue->status == 1 || $orderValue->status == 2 || $orderValue->status == 3)
                        <a href="javascript: void(0)" class="text-danger mr-3" style="font-weight: bold" onclick="cancelOrder({{$orderValue->id}}, '{{$orderValue->order_no}}', '{{$orderSTatus}}')">Cancel order</a>
                    @endif

                    <a href="{{ route('front.user.invoice', $orderValue->id) }}" class="text-primary" style="font-weight: bold">Invoice</a>
                </div>
                <div class="order-card-body">
                    @foreach($orderValue->orderProducts as $productKey => $productValue)
                        @php
                            $subTotal = $grandTotal = $couponCodeDiscount = $shippingCharges = $taxPercent = 0;
                        @endphp
                        @php
                            $variation = '';
                            if($productValue->productVariationDetails) {
                                $variation = '| Color: <span>'.ucwords($productValue->productVariationDetails->colorDetails->name).'</span> | Size: <span>'.$productValue->productVariationDetails->sizeDetails->name.'</span>';
                            }
                            if (!empty($data[0]->coupon_code_id)) {
                                $couponCodeDiscount = (int) $data[0]->couponDetails->amount;
                            }

                            // grand total calculation
                            $grandTotalWithoutCoupon = $subTotal;
                            $grandTotal = ($subTotal + $shippingCharges) - $couponCodeDiscount;
                        @endphp

                        <div class="order-product-card">
                            <figure>
                                <img src="{{asset($productValue->product_image)}}" />
                            </figure>
                            <figcaption>
                                {{-- <h6>Style # OF {{$productValue->product_style_no}}</h6> --}}
                                <h4>{{$productValue->product_name}}</h4>
                                <h5>Price: <span>&#8377;{{$productValue->offer_price}}</span> {!!$variation!!} | Qty: <span>{{$productValue->qty}}</span></h5>

                                {{-- return product --}}
                                {{-- if order is delivered --}}
                                <div class="mt-2">
                                    @if ($orderValue->status == 4)
                                        {{-- if product is delivered, ask for return --}}
                                        @if ($productValue->status == 4)
                                            <a href="javascript:void(0)" class="text-danger font-weight-bold" onclick="returnOrder({{$productValue->id}}, '{{$productValue->product_name}}')">Return</a>
                                        @elseif ($productValue->status == 6)
                                            <p class="text-danger" style="font-size: 12px;font-weight: 600;">You have requested return for this product. Please wait till admin approves.</p>
                                        @elseif ($productValue->status == 7)
                                            <p class="text-danger" style="font-size: 12px;font-weight: 600;">Return request for this product is approved. You will be receiving exchanged product shortly.</p>
                                        @elseif ($productValue->status == 8)
                                            <p class="text-danger" style="font-size: 12px;font-weight: 600;">Return request for this product is declined. You can view the <a href="{{ route('front.content.return') }}">Return Policy</a> or <a href="{{ route('front.content.service') }}">Talk to Support</a>.</p>
                                        @elseif ($productValue->status == 11)
                                            <p class="text-success" style="font-size: 12px;font-weight: 600;">New products are shipped. You will be receiving them shortly.</p>
                                        @elseif ($productValue->status == 12)
                                            <p class="text-success" style="font-size: 12px;font-weight: 600;">Returned products are delivered</p>
                                        @else
                                            
                                        @endif
                                    @endif
                                </div>
                            </figcaption>
                        </div>
                    @endforeach
                </div>
                <div class="order-card-footer">
                    <div class="row">
                        <div class="col-sm-6">
                            Order #{{$orderValue->order_no}}
                        </div>
                        <div class="col-sm-6 text-sm-right">

                            {{-- @if (!empty($data[0]->coupon_code_id))
                                <div class="">
                                    <div class="cart-total-label small mb-0">
                                        COUPON APPLIED <strong>({{$data[0]->couponDetails->coupon_code}})</strong><br/>
                                    </div>
                                    <p class="small mb-0">Discount: {!! ($data[0]->couponDetails->is_coupon == 1) ? '&#8377 '.$data[0]->couponDetails->amount : $data[0]->couponDetails->amount.'% OFF' !!}</p>
                                </div>
                            @endif --}}

                            @if (!empty($orderValue->coupon_code_id))
								@if($orderValue->couponDetails)
                                <div class="">
                                    <div class="cart-total-label small mb-0">
                                        COUPON APPLIED <strong>({{$orderValue->couponDetails->coupon_code}})</strong><br/>
                                    </div>
                                    {{-- <p class="small mb-0">Discount: {!! ($data[0]->couponDetails->is_coupon == 1) ? '&#8377 '.$data[0]->couponDetails->amount : $data[0]->couponDetails->amount.'% OFF' !!}</p> --}}
                                    <p class="small mb-0">
                                        Discount: 
                                        {!! ($orderValue->coupon_code_discount_type != "Percentage") ? '&#8377 '.$orderValue->discount_amount : $orderValue->discount_amount.'% OFF' !!}
                                    </p>
                                </div>
							@endif
                            @endif
							@if($orderValue->address_type=='ho')
								Total Order Price: &#8377; {{($orderValue->final_amount)-($orderValue->shipping_charges)}}
							@elseif($orderValue->address_type=='dankuni')
								Total Order Price: &#8377; {{($orderValue->final_amount)-($orderValue->shipping_charges)}}
							@else
                            	Total Order Price: &#8377; {{$orderValue->final_amount}}
							@endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>No orders found</p>
        @endforelse
    </div>
</div>

{{-- cancel order modal --}}
<div class="modal fade" id="cancellationModal" tabindex="-1" aria-labelledby="cancellationModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancellationModalLabel">Are you sure?</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('front.user.order.cancel') }}" method="post">@csrf
                    <p class="fs-14 mb-3 text-muted">You want to cancel your order with id <strong>#<span class="text-dark" id="cancellationorderId"></span></strong></p>
                    <p class="fs-14 text-muted">Current staus: <strong><span class="text-dark" id="cancellationorderStatus"></span></strong></p>
                    
                    <input type="hidden" name="orderId" id="cancelOrderId" value="">

                    <textarea name="cancellationReason" class="form-control mb-3" placeholder="Enter cancellation reason" style="min-height: 150px"></textarea>

                    <div class="w-100">
                        <a href="" class="btn btn-secondary" data-bs-dismiss="modal">No</a>
                        <button type="submit" class="btn btn-primary">Yes, I want to cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- return order modal --}}
<div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="returnModalLabel">Are you sure?</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('front.user.order.return') }}" method="post">@csrf
                    <p class="fs-14 mb-3 text-muted">You want to return your order <strong><span class="text-dark" id="returnProductName"></span></strong></p>
                    {{-- <p class="fs-14 text-muted">Current staus: <strong><span class="text-dark" id="cancellationorderStatus"></span></strong></p> --}}

                    <input type="hidden" name="orderProductId" id="orderProductId" value="">

                    <select name="returnReasonType" id="" class="form-control mb-2">
                        <option value="" selected disabled>Select reason *</option>
                        @php
                            $reasons = \DB::table('orderProductReturnReasons')->select('title')->orderBy('title')->get();
                        @endphp

                        @foreach ($reasons as $reason)
                            <option value="{{ $reason->title }}">{{ $reason->title }}</option>
                        @endforeach
                    </select>

                    <textarea name="returnReasonComment" class="form-control mb-3" placeholder="Enter your comment *" style="min-height: 150px"></textarea>

                    <div class="w-100">
                        <a href="" class="btn btn-secondary" data-bs-dismiss="modal">No</a>
                        <button type="submit" class="btn btn-primary">Yes, I want to return</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        function cancelOrder(id, orderId, status) {
            $('textarea[name="cancellationReason"]').val('');
            $('#cancelOrderId').val(id);
            $('#cancellationorderId').text(orderId);
            $('#cancellationorderStatus').text(status);
            $('#cancellationModal').modal('show');
        }

        function returnOrder(orderProductId, productName) {
            $('textarea[name="returnReason"]').val('');
            $('#orderProductId').val(orderProductId);
            $('#returnProductName').text(productName);
            // $('#cancellationorderStatus').text(status);
            $('#returnModal').modal('show');
        }
    </script>
@endsection