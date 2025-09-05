@extends('admin.layouts.app')

@section('page', 'Order')

@section('content')
<section>
    <div class="card card-body">
        <div class="row align-items-center justify-content-end">
            <div class="col-md-3">
                <p class="small text-muted">Displaying {{$data->firstItem()}} - {{$data->lastItem()}} out of {{$data->total()}} records</p>
            </div>

            <div class="col-md-9">
                <form action="" method="GET">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <label for="from_date" class="small text-muted">From</label>
                            <input type="date" name="from_date" id="from_date" class="form-control" value="{{request()->input('from_date') ?? '' }}">
                        </div>
                        <div class="col-auto">
                            <label for="to_date" class="small text-muted">To</label>
                            <input type="date" name="to_date" id="to_date" class="form-control" value="{{request()->input('to_date') ?? '' }}">
                        </div>
                        <div class="col-auto">
                            <label for="payment_type" class="small text-muted">Payment type</label>
                            <select name="payment_type" class="form-control" id="payment_type">
                                <option value="">Select</option>
                                <option value="cash_on_delivery" {{request()->input('payment_type') == 'cash_on_delivery' ? 'selected' : ''}}>Cash on delivery</option>
                                <option value="online_payment" {{request()->input('payment_type') == 'online_payment' ? 'selected' : ''}}>Online Payment</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <label for="term" class="small text-muted">Search for Order No, Name, Amount</label>
                            <input type="search" name="term" id="term" class="form-control" placeholder="Search here.." value="{{app('request')->input('term')}}" autocomplete="off">
                        </div>
                        <div class="col-auto">
                            {{-- <button type="submit" class="btn btn-outline-danger btn-sm">Search</button> --}}
                            <div class="btn-group">
                                <button type="submit" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-danger" data-bs-original-title="Search"> <i class="fi fi-br-search"></i> </button>

                                <a href="{{ url()->current() }}" class="btn btn-sm btn-light" data-bs-toggle="tooltip" title="" data-bs-original-title="Clear search"> <i class="fi fi-br-x"></i> </a>

                                <a href="{{ route('admin.order.export.all') }}" data-bs-toggle="tooltip" class="btn btn-sm btn-danger" title="" data-bs-original-title="Export"> <i class="fi fi-br-download"></i> </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <table class="table">
        <thead>
        <tr>
            <th>#SR</th>
            <th>Name</th>
            <th>Amount</th>
            <th>Invoice</th>
            <th>Details</th>
        </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $item)
                <tr id="row_{{$item->id}}">
                    <td>
                        {{ $index + $data->firstItem() }}
                    </td>
                    <td>
                        <p class="small text-dark mb-1">#{{$item->order_no}}</p>
                        <p class="small text-dark mb-1">{{$item->fname.' '.$item->lname}}</p>
                        <p class="small text-muted mb-0">{{$item->email.' | '.$item->mobile}}</p>
                        <div class="row__action">
                            <a href="{{ route('admin.order.view', $item->id) }}">View</a>

                            @if ($item->is_live_order == 1)
                                <a href="javascript: void(0)" data-bs-toggle="tooltip" title="This is a LIVE Order. Tap to make this a DUMMY Order" onclick="typeUpdate({{$item->id}}, 0)" class="fw-bold text-success order-type">Live Order</a>
                            @else
                                <a href="javascript: void(0)" data-bs-toggle="tooltip" title="This is a DUMMY Order. Tap to make this a LIVE Order" onclick="typeUpdate({{$item->id}}, 1)" class="fw-bold text-danger order-type">Dummy Order</a>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if ($item->coupon_code_id != 0)
                            @if($item->couponDetails)
                            <div class="">
                                <p class="small text-muted mb-1">Total: {{$item->amount}}</p>
                                <p class="small mb-0">Discount: {{$item->coupon_code_discount_type == 'Percentage' ? $item->couponDetails->amount .'%' : 'Rs. ' .$item->couponDetails->amount }}</p>
                                <p class="small text-muted mb-1">Final: {{$item->final_amount}}</p>
                            </div>
                            @endif
                        @else
                        <p class="small text-dark mb-1">&#8377; {{ number_format($item->final_amount) }}</p>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.order.invoice', $item->id) }}" class="btn btn-sm btn-primary">Invoice</a>
                    </td>
                    <td>
                        @if ($item->offerDetails)
                        <div class="offer mb-3">
                            <div class="d-flex">
                                <div class="check me-2 text-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                </div>
                                <div class="img me-3">
                                    <img src="{{ asset($item->offerDetails->offer_image) }}" height="30">
                                </div>
                                <div class="content">
                                    <p>
                                        {{ $item->offerDetails->customer_receive_product_name }}
                                        <strong>&times; {{ $item->offerDetails->customer_receive_product_qty }}</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="order-time">
                            <p class="text-muted mb-0">Order placed at 
                                <span class="text-dark font-weight-bold mb-2">
                                    {{date('j M Y g:i A', strtotime($item->created_at))}}
                                </span>
                            </p>
                        </div>

                        <div class="address">
                            <p class="small text-muted mb-0">Billing Address</p>
                            <p class="small text-dark mb-2">
                                {!! $item->billing_address ? $item->billing_address.', ' : '<span class="text-danger">NO Street, </span>' !!}
                                {!! $item->billing_landmark ? $item->billing_landmark.', ' : '<span class="text-danger">NO Landmark, </span>' !!}
                                {!! $item->billing_pin ? $item->billing_pin.', ' : '<span class="text-danger">NO Pincode, </span>' !!}
                                {!! $item->billing_city ? $item->billing_city.', ' : '<span class="text-danger">NO City, </span>' !!}
                                {!! $item->billing_state ? $item->billing_state.', ' : '<span class="text-danger">NO State, </span>' !!}
                                {!! $item->billing_country ? $item->billing_country : '<span class="text-danger">NO Country</span>' !!}
                            </p>
                        </div>

                        <div class="address">
                            <p class="small text-muted mb-0">Payment type: 
                                <span class="text-success mb-2 fw-bold">
                                    {!! ($item->payment_method == "online_payment") ? 'Online Payment' : 'Cash on Delivery' !!}
                                </span>
                                @if ($item->transactionDetails)
                                    <span class="text-dark mb-2 fw-bold">
                                        ( &#8377;{{ number_format($item->transactionDetails->amount) }} )
                                    </span>
                                @endif
                            </p>
                        </div>

                        {{-- <div class="btn-group" role="group">
                            <a href="javascript: void(0)" onclick="statusUpdate({{$item->id}}, 1)" type="button" class="status_1 btn btn-outline-primary btn-sm {{($item->status == 1) ? 'active' : ''}}">New</a>
                            <a href="javascript: void(0)" onclick="statusUpdate({{$item->id}}, 2)" type="button" class="status_2 btn btn-outline-primary btn-sm {{($item->status == 2) ? 'active' : ''}}">Confirm</a>
                            <a href="javascript: void(0)" onclick="statusUpdate({{$item->id}}, 3)" type="button" class="status_3 btn btn-outline-primary btn-sm {{($item->status == 3) ? 'active' : ''}}">Shipped</a>
                            <a href="javascript: void(0)" onclick="statusUpdate({{$item->id}}, 4)" type="button" class="status_4 btn btn-outline-success btn-sm {{($item->status == 4) ? 'active' : ''}}">Delivered</a>
                            <a href="javascript: void(0)" onclick="statusUpdate({{$item->id}}, 5)" type="button" class="status_5 btn btn-outline-danger btn-sm {{($item->status == 5) ? 'active' : ''}}">Cancelled</a>
                        </div> --}}
                    </td>
                </tr>
            @empty
                <tr><td colspan="100%" class="small text-muted">No data found</td></tr>
            @endforelse
        </tbody>
    </table>

    @if (count($data) > 0)
        <div class="row">
            <div class="col-12">
                <div class="pagination justify-content-end">
                    {{$data->appends($_GET)->links()}}
                </div>
            </div>
        </div>
    @endif
</section>
@endsection

@section('script')
    <script>
        function statusUpdate(orderId, status) {
            const res = confirm('Customer will receive email about the order. Are you sure ?');

            if (res === true) {
                $('#row_'+orderId+' .btn-group .btn').addClass('disabled');
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
                            $('#row_'+orderId+' .btn-group .btn').removeClass('active').removeClass('disabled');
                            $('#row_'+orderId+' .btn-group .status_'+status).addClass('active');
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

        function typeUpdate(orderId, type) {
            const res = confirm('Are you sure ?');

            if (res === true) {
                $('#row_'+orderId+' .btn-group .btn').addClass('disabled');
                $.ajax({
                    url: "{{ url('') }}/admin/order/"+orderId+"/type/"+type,
                    type: "GET",
                    data: {
                        _token: "{{csrf_token()}}",
                        id: orderId,
                        type: type,
                    },
                    success: function(resp) {
                        if (resp.error === false) {
                            if (type == 1) {
                                $('#row_'+orderId+' .row__action .order-type').removeClass('text-danger').addClass('text-success').text('Live Order');
                            } else {
                                $('#row_'+orderId+' .row__action .order-type').removeClass('text-success').addClass('text-danger').text('Dummy Order');
                            }
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
    </script>
@endsection
