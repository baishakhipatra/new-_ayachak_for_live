@extends('admin.layouts.app')

@section('page', 'Order detail')

@section('content')
<style>
    .chat_box {
        width: 300px;
        height: 100%;
        position: fixed;
        top: 0;
        right: 0;
        z-index: 999;
        display: flex;
        background: #fff;
        transform: translateX(100%);
        transition: all ease-in-out 0.5s;
    }
    .chat_box.active {
        transform: translateX(0%);
        box-shadow: 10px 10px 100px 10px rgb(0 0 0 / 30%);
    }
    .chat_box .card {
        width: 100%;
        margin: 0;
    }
    .chat_box .card-body {
        overflow: auto;
        margin-bottom: 42px;
        display: flex;
        flex-direction: column-reverse;
    }
    .chat_box .card-footer {
        position: fixed;
        bottom: 0;
    }
    .text-body {
        border-radius: 10px 10px 0 10px;
    }
    .text-body p {
        white-space: normal;
        text-align: right;
        color: #fff;
        line-height: 1.25;
    }
</style>

<section>
    <div class="row">
        <div class="col-sm-5">
            <div class="card shadow-sm">
                <div class="card-header">Ordered Products ({{count($data->orderProducts)}})</div>
                <div class="card-body pt-0">
                    @forelse($data->orderProducts as $productKey => $productValue)
                    <div class="admin__content">
                        <aside>
                            <a href="{{ route('admin.product.edit', $productValue->product_id) }}" target="_blank">
                                <nav>{{$productValue->product_name}}</nav>
                                <img src="{{ asset($productValue->product_image) }}" class="mt-2" style="width: 80%;">
                            </a>
                        </aside>
                        <content>
                            <div class="row align-items-center">
                                <div class="col-5">
                                    <label for="inputPassword6" class="col-form-label text-muted">Style no :</label>
                                </div>
                                <div class="col-auto">
                                    {{$productValue->productDetails->style_no}}
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-5">
                                    <label for="inputPassword6" class="col-form-label text-muted">Qty :</label>
                                </div>
                                <div class="col-auto">
                                    {{$productValue->qty}}
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-5">
                                    <label for="inputPassword6" class="col-form-label text-muted">Price :</label>
                                </div>
                                <div class="col-auto">
                                    &#8377;{{$productValue->price}}
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-5">
                                    <label for="inputPassword6" class="col-form-label text-muted">Offer Price :</label>
                                </div>
                                <div class="col-auto">
                                    &#8377;{{$productValue->offer_price}}
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-5">
                                    <label for="inputPassword6" class="col-form-label text-muted">GST :</label>
                                </div>
                                <div class="col-auto">
                                   {{$productValue->gst}}%
                                </div>
                            </div>
                            @if ($productValue->productVariationDetails)
                            <div class="row align-items-center">
                                <div class="col-5">
                                    <label for="inputPassword6" class="col-form-label text-muted">SKU :</label>
                                </div>
                                <div class="col-auto">
                                    {{$productValue->productVariationDetails->code}}
                                </div>
                            </div>
                            @endif
                        </content>
                    </div>
                    <div class="card card-body mb-0 p-0 pt-3">
                        <h5>Product status</h5>
                        <p class="small text-muted">Update status for this Product only</p>
                        <div class="btn-group" role="group">
                            <a href="javascript: void(0)" onclick="productStatusUpdate({{$productValue->id}}, 1)" type="button" class="status_1 btn btn-outline-primary btn-sm {{($productValue->status == 1) ? 'active' : ''}}">New</a>

                            <a href="javascript: void(0)" onclick="productStatusUpdate({{$productValue->id}}, 2)" type="button" class="status_2 btn btn-outline-primary btn-sm {{($productValue->status == 2) ? 'active' : ''}}">Confirm</a>

                            <a href="javascript: void(0)" onclick="productStatusUpdate({{$productValue->id}}, 3)" type="button" class="status_3 btn btn-outline-primary btn-sm {{($productValue->status == 3) ? 'active' : ''}}">Shipped</a>

                            <a href="javascript: void(0)" onclick="productStatusUpdate({{$productValue->id}}, 4)" type="button" class="status_4 btn btn-outline-success btn-sm {{($productValue->status == 4) ? 'active' : ''}}">Delivered</a>

                            <a href="javascript: void(0)" onclick="productStatusUpdate({{$productValue->id}}, 5)" type="button" class="status_5 btn btn-outline-danger btn-sm {{($productValue->status == 5) ? 'active' : ''}}">Cancelled</a>
                        </div>

                        {{-- return options will be active once customer asks for refund --}}
                        @if ($productValue->status > 5)
                        <br>

                        <div class="btn-group">
                            <a href="javascript: void(0)" type="button" class="status_6 btn btn-outline-danger btn-sm {{($productValue->status == 6) ? 'active' : ''}}">Return request by customer</a>

                            <a href="javascript: void(0)" onclick="productStatusUpdate({{$productValue->id}}, 7)" type="button" class="status_7 btn btn-outline-success btn-sm {{($productValue->status == 7) ? 'active' : ''}}">Return APPROVED</a>

                            <a href="javascript: void(0)" onclick="productStatusUpdate({{$productValue->id}}, 8)" type="button" class="status_8 btn btn-outline-danger btn-sm {{($productValue->status == 8) ? 'active' : ''}}">Return DECLINED</a>
                        </div>

                        <div class="customer-return-details mt-3">
                            <p class="small text-muted mb-2">
                                Reason:
                                <span class="text-dark">{{$productValue->return_reason_type}}</span>
                            </p>
                            <p class="small text-muted mb-0">
                                Comment:
                                <span class="text-dark">{{$productValue->return_reason_comment}}</span>
                            </p>
                        </div>

                        <br>

                        <div class="btn-group">
                            <a href="javascript: void(0)" onclick="productStatusUpdate({{$productValue->id}}, 9)" type="button" class="status_9 btn btn-outline-danger btn-sm {{($productValue->status == 9) ? 'active' : ''}}">Old product returned by customer</a>

                            <a href="javascript: void(0)" onclick="productStatusUpdate({{$productValue->id}}, 10)" type="button" class="status_10 btn btn-outline-success btn-sm {{($productValue->status == 10) ? 'active' : ''}}">Old product received by admin</a>

                            <a href="javascript: void(0)" onclick="productStatusUpdate({{$productValue->id}}, 11)" type="button" class="status_11 btn btn-outline-success btn-sm {{($productValue->status == 11) ? 'active' : ''}}">New product shipped by admin</a>

                            <a href="javascript: void(0)" onclick="productStatusUpdate({{$productValue->id}}, 12)" type="button" class="status_12 btn btn-outline-success btn-sm {{($productValue->status == 12) ? 'active' : ''}}">New product received by customer</a>
                        </div>
                        @else
                        <p class="small text-muted mb-0 mt-3">RETURN options will be active once customer asks for refund</p>
                        @endif
                    </div>
                    @empty
                        <h5 class="display-6 text-danger">Invalid Order</h5>
                        <p class="text-muted">Customer refreshed Order success page</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-sm-7">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Entire Order status</h5>
                    <p class="small text-muted">Update status for entire order from here</p>
                    <div class="btn-group" role="group">
                        <a href="javascript: void(0)" onclick="statusUpdate({{$data->id}}, 1)" type="button" class="status_1 btn btn-outline-primary btn-sm {{($data->status == 1) ? 'active' : ''}}">New</a>
                        <a href="javascript: void(0)" onclick="statusUpdate({{$data->id}}, 2)" type="button" class="status_2 btn btn-outline-primary btn-sm {{($data->status == 2) ? 'active' : ''}}">Confirm</a>
                        <a href="javascript: void(0)" onclick="statusUpdate({{$data->id}}, 3)" type="button" class="status_3 btn btn-outline-primary btn-sm {{($data->status == 3) ? 'active' : ''}}">Shipped</a>
                        <a href="javascript: void(0)" onclick="statusUpdate({{$data->id}}, 4)" type="button" class="status_4 btn btn-outline-success btn-sm {{($data->status == 4) ? 'active' : ''}}">Delivered</a>
                        <a href="javascript: void(0)" onclick="statusUpdate({{$data->id}}, 5)" type="button" class="status_5 btn btn-outline-danger btn-sm {{($data->status == 5) ? 'active' : ''}}">Cancelled</a>
                    </div>

                    @if ($data->status == 5)
                        <div class="w-100 mt-3">
                            Cancelled by: {{ ($data->orderCancelledBy == 0) ? 'ADMIN' : 'CUSTOMER' }}
                        </div>
                        <div class="w-100 mt-1">
                            Cancellation Reason: {!! ($data->orderCancelledReason) ?? 'NA' !!}
                        </div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <a href="javascript: void(0)" class="btn btn-danger chat_btn" title="Order Remark" onclick="remarkModal({{$data->id}}, '{{$data->order_no}}')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                        Order Remark
                    </a>

                    <br>
                    <br>

                    <div class="form-group mb-3">
                        <p class="small">Order Time : {{date('j M Y g:i A', strtotime($data->created_at))}}</p>
                        <h5>#{{$data->order_no}}</h5>
                        <h2>{{ucwords($data->fname.' '.$data->lname)}}</h2>
                        <p class="small text-dark mb-0"> <span class="text-muted">Email : </span> {{$data->email}}</p>
                        <p class="small text-dark mb-0"> <span class="text-muted">Mobile : </span> {{$data->mobile}}</p>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="small text-dark mb-2"> <span class="text-muted">Billing address</span></p>
                            <p class="small text-dark mb-0"> <span class="text-muted">Address : </span> {{$data->billing_address}}</p>
                            <p class="small text-dark mb-0"> <span class="text-muted">Landmark : </span> {{$data->billing_landmark}}</p>
                            <p class="small text-dark mb-0"> {{$data->billing_pin.', '.$data->billing_city.', '.$data->billing_state.', '.$data->billing_country}}</p>
                        </div>

                        <div class="col-md-6 border-start">
                            <p class="small text-dark mb-2"> <span class="text-muted">Shipping address</span></p>
                            @if ($data->shippingSameAsBilling == 1) <p class="small text-dark mb-2"> <span class="text-muted"> <i class="fi fi-br-info"></i> Same as Billing address </span></p>@endif
                            <p class="small text-dark mb-0"> <span class="text-muted">Address : </span> {{$data->shipping_address}}</p>
                            <p class="small text-dark mb-0"> <span class="text-muted">Landmark : </span> {{$data->shipping_landmark}}</p>
                            <p class="small text-dark mb-0"> {{$data->shipping_pin.', '.$data->shipping_city.', '.$data->shipping_state.', '.$data->shipping_country}}</p>
                        </div>
                    </div>

                    <hr>

                    @if ($data->offerDetails)
                    <div class="offer mb-3">
                        <div class="d-flex">
                            <div class="check me-2 text-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            </div>
                            <div class="img me-3">
                                <img src="{{ asset($data->offerDetails->offer_image) }}" height="30">
                            </div>
                            <div class="content">
                                <p>
                                    {{ $data->offerDetails->customer_receive_product_name }}
                                    <strong>&times; {{ $data->offerDetails->customer_receive_product_qty }}</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mb-3 justify-content-end">
                        <div class="col-md-8">
                            @if ($data->coupon_code_id != 0)
                                <p class="small text-muted mb-2">{{ ($data->couponDetails->is_coupon == 0) ? 'Voucher' : 'Coupon' }} used</p>
                                <a href="{{ route('admin.coupon.view', $data->coupon_code_id) }}" class="small text-primary mb-0">{{$data->couponDetails->coupon_code}}</a>
                            @endif
                            <p class="small text-dark mb-0"> <span class="text-muted">Payment method : </span> {{$data->payment_method}}</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <p class="small text-muted mb-2">Pricing</p>
                            <table class="w-100">
                                <tr>
                                    <td><p class="small text-muted mb-0">Amount : </p></td>
                                    <td><p class="small text-dark mb-0 text-end">&#8377;{{$data->amount}}</p></td>
                                </tr>
                                <tr>
                                    <td><p class="small text-muted mb-0">Tax Amount : </p></td>
                                    <td><p class="small text-dark mb-0 text-end">+ &#8377;{{$data->tax_amount}}</p></td>
                                </tr>
                                <tr>
                                    <td><p class="small text-muted mb-0">Discount : </p></td>
                                    @if ($data->coupon_code_id != 0)
                                        <td><p class="small text-dark mb-0 text-end">- {!! $data->coupon_code_discount_type == 'Percentage' ? $data->discount_amount .'%' : '&#8377;' . $data->discount_amount !!}</p></td>
                                    @else
                                        <td><p class="small text-dark mb-0 text-end">- {{$data->discount_amount}}</p></td>
                                    @endif
                                </tr>
                                <tr>
                                    <td><p class="small text-muted mb-0">Shipping : </p></td>
									
                                    @if($data->address_type=='ho')
									<td><p class="small text-dark mb-0 text-end">+ &#8377;0</p></td>
									@elseif($data->address_type=='dankuni')
									<td><p class="small text-dark mb-0 text-end">+ &#8377;0</p></td>
									@else
                                    <td><p class="small text-dark mb-0 text-end">+ &#8377;{{$data->shipping_charges}}</p></td>
									@endif
                                </tr>
                                <tr class="border-top">
                                    <td><p class="small text-muted mb-0">Final Amount : </p></td>
									@if($data->address_type=='ho')
									<td><p class="small text-dark mb-0 text-end">&#8377;{{($data->final_amount)-($data->shipping_charges)}}</p></td>
									@elseif($data->address_type=='dankuni')
									<td><p class="small text-dark mb-0 text-end">&#8377;{{($data->final_amount)-($data->shipping_charges)}}</p></td>
									@else
                                    <td><p class="small text-dark mb-0 text-end">&#8377;{{$data->final_amount}}</p></td>
									@endif
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Order Remark Modal --}}
<div class="chat_box" id="orderRemarkModal">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="modal-title"></h5>
            <button type="button" class="btn-close chat_close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="card-body"></div>
        <div class="card-footer p-0">
            <form action="" method="post" class="w-100 remark-form">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <div class="input-group input-group-lg">
                            <input type="text" class="form-control rounded-0" placeholder="Type something..." aria-label="Type something..." aria-describedby="remark-submit" name="comment">
                            <input type="hidden" name="order_id" value="">
                            <button class="btn btn-secondary rounded-0" type="submit" id="remark-submit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        function statusUpdate(orderId, status) {
            const res = confirm('Customer will receive email about the order. Are you sure ?');

            if (res === true) {
                $('.btn-group .btn').addClass('disabled');
                $.ajax({
                    url: "{{ route('admin.order.status') }}",
                    type: "POST",
                    data: {
                        _token: "{{csrf_token()}}",
                        id: orderId,
                        status: status,
                    },
                    success: function(resp) {
                        if (resp.error === false) {
                            $('.btn-group .btn').removeClass('active').removeClass('disabled');
                            $('.btn-group .status_'+status).addClass('active');
                            toastFire('success', resp.message);
                        } else {
                            toastFire('warning', resp.message);
                        }
                    }
                });
            } else {
                return false;
            }
        }

        function productStatusUpdate(orderId, status) {
            const res = confirm('Customer will receive email about the order. Are you sure ?');

            if (res === true) {
                $('.btn-group .btn').addClass('disabled');
                $.ajax({
                    url: "{{ route('admin.order.product.status') }}",
                    type: "POST",
                    data: {
                        _token: "{{csrf_token()}}",
                        id: orderId,
                        status: status,
                    },
                    success: function(resp) {
                        if (resp.error === false) {
                            $('.btn-group .btn').removeClass('active').removeClass('disabled');
                            $('.btn-group .status_'+status).addClass('active');
                            toastFire('success', resp.message);
                        } else {
                            toastFire('warning', resp.message);
                        }
                    }
                });
            } else {
                return false;
            }
        }

        // order remark
        function remarkModal(orderId, orderNo) {
            $.ajax({
                url: "{{ url('/') }}/admin/order/"+orderId+"/remark",
                type: "GET",
                success: function(resp) {
                    var content = '';

                    // content
                    if (resp.status == 200) {
                        $.each(resp.data, (key, val) => {
                            content += `
                            <div class="row justify-content-end mb-3">
                                <div class="col-10 text-end">
                                    <div class="badge text-body bg-primary">
                                        <p class="">${val.comment}</p>
                                        <p class="small mb-0">${val.created_at}</p>
                                    </div>
                                </div>
                            </div>
                            `;
                        });
                    } else {
                        content = `
                        <div class="row h-100 align-items-end justify-content-center">
                            <div class="col-12 text-center">
                                <p class="text-muted mb-0">No remark added yet</p>
                            </div>
                        </div>
                        `;
                    }

                    $('#orderRemarkModal .card-body').html(content);
                    $('#orderRemarkModal .modal-title').text(orderNo);
                    $('#orderRemarkModal input[name="order_id"]').val(orderId);
                    $('.chat_box').addClass('active');
                    $('.remark-form input').focus();
                }
            });
        }

        $('.chat_close').click(function(){
            $('.chat_box').toggleClass('active');
        });

        $('.remark-form').on('submit', function(e) {
            e.preventDefault();
            let comment = $('input[name="comment"]').val();
            let orderId = $('input[name="order_id"]').val();

            if (comment.length > 0) {
                $.ajax({
                    url: "{{ route('admin.order.remark.add') }}",
                    type: "POST",
                    data: {
                        '_token': '{{csrf_token()}}',
                        'order_id': orderId,
                        'comment': comment,
                    },
                    success: function(resp) {
                        var content = '';

                        // content
                        if (resp.status == 200) {
                            $('input[name="comment"]').val('');
                            remarkModal(orderId);
                        } else {
                            toastFire('warning', resp.message);
                        }
                    }
                });
            }
        });
    </script>
@endsection