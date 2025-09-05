@extends('admin.layouts.app')

@section('page', 'Order')

@section('content')
<section>
    <div class="card card-body">
        <div class="row align-items-end justify-content-end">
            <div class="col-md-12 mb-3">
                <form action="" method="GET">
                    <div class="row g-3 align-items-end justify-content-end">
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
                            <label for="term" class="small text-muted">Search for Order No, Name, Amount,Coupon code</label>
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

            <div class="col-md-12 text-end">
                <p class="small text-muted mb-0">Displaying {{$data->firstItem()}} - {{$data->lastItem()}} out of {{$data->total()}} records</p>
            </div>
        </div>
    </div>

    <table class="table" id="mytable">
        <thead>
        <tr>
            <th>#SR</th>
            <th>Name</th>
            <th>Amount</th>
            <th>Online Payment Id</th> 
            <th>Paid Amount</th>
            <th>Payment Status</th>
            <th>Invoice</th>
            <th>Details</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $item)
		
             @php
             $trans=\App\Models\Transaction::where('order_id',$item->id)->first();
             //dd($trans);
             @endphp
                <tr id="row_{{$item->id}}">
                    <td>
                        {{ $index + $data->firstItem() }}
                    </td>
                    <td>
                        <p class="small text-dark mb-1">#{{$item->order_no}}</p>
                        <p class="small text-dark mb-1">{{$item->fname.' '.$item->lname}}</p>
                        <p class="small text-muted mb-0">{{$item->email.' | '.$item->mobile}}</p>
                        
                    </td>
                    <td>
                        @if ($item->coupon_code_id != 0)
                            @if($item->couponDetails)
                            <div class="">
                                <p class="small text-muted mb-1">Total: {{$item->amount ?: ''}}</p>
                                <p class="small mb-0">Discount code: {{$item->couponDetails->coupon_code ??'' }}</p>
                                <p class="small mb-0">Discount: {{$item->coupon_code_discount_type == 'Percentage' ? $item->couponDetails->amount .'%' : 'Rs. ' .$item->couponDetails->amount }}</p>
								@if($item->address_type=='ho')
                                <p class="small text-muted mb-1">Final: {{($item->final_amount)-($item->shipping_charges)}}</p>
								@elseif($item->address_type=='dankuni')
								<p class="small text-muted mb-1">Final: {{($item->final_amount)-($item->shipping_charges)}}</p>
								@else
								<p class="small text-muted mb-1">Final: {{$item->final_amount}}</p>
								@endif
                            </div>
                            @endif
                        @else
                        <p class="small text-dark mb-1">&#8377; {{ number_format($item->final_amount) }}</p>
                        @endif
                    </td>
                    @if(!empty($item->transactionDetails))
                    @php
					  
                        $getPaymentResp = getPaymentResp($item->transactionDetails->online_payment_id);
						//dd($getPaymentResp);
                        $paymentStatus = $getPaymentResp->status ?? '';
                        $paymentAmount = $getPaymentResp->amount/100 ?? '';
					    if($item->address_type=='ho'){
					       $final_amount=($item->final_amount)-($item->shipping_charges);
					    }elseif($item->address_type=='dankuni'){
					       $final_amount= ($item->final_amount)-($item->shipping_charges);
					    }else{
					       $final_amount= $item->final_amount;
					     }
					
                    @endphp
                    @endif
                    <td>
                        @if($trans) {{$trans->online_payment_id}}@endif
                      
                    </td>
                   
                    <td>  
                        @if ($item->payment_method == "online_payment")
							@if ($item->transactionDetails)
							<span class="text-dark mb-2 fw-bold">
								<p class="small text-dark mb-1">&#8377; {{ number_format($paymentAmount)?? '' }} 
							</span>
							@else
						   <span></span>
							@endif
                        @elseif($item->payment_method == "cash_on_delivery")
							@if ($item->coupon_code_id != 0)
                            	@if($item->couponDetails)
									@if($item->address_type=='ho')
										<span class="text-dark mb-2 fw-bold">
											<p class="small text-dark mb-1">&#8377;{{ number_format(($item->final_amount)-($item->shipping_charges)) ?? ''}}
										</span>
									@elseif($item->address_type=='dankuni')
										<span class="text-dark mb-2 fw-bold">
											<p class="small text-dark mb-1">&#8377;{{ number_format(($item->final_amount)-($item->shipping_charges)) ?? ''}}
										</span>
									 @else
									<span class="text-dark mb-2 fw-bold">
										<p class="small text-dark mb-1">&#8377;{{ number_format($item->final_amount) ?? ''}}
									</span>
						            @endif
						         @endif
						     @endif
                        @else
                        <span>Transaction not captured</span>
                        @endif
                    </td>
                    <td>
						
						@if(!empty($paymentStatus))
                        @if ($item->payment_method == "online_payment")<span class="btn btn-sm btn-secondary">{{$paymentStatus}}</span>
						@endif
						@endif
                    </td>
                   
                        <td>
                        @if ($item->payment_method == "online_payment")
                          @if($item->transactionDetails)@if($final_amount == $paymentAmount) @if($paymentStatus=='captured') <a href="{{ route('admin.order.invoice', $item->id) }}" class="btn btn-sm btn-primary">Invoice</a> @endif @endif @endif
                       
                        @elseif($item->payment_method == "cash_on_delivery")
                       
                        <a href="{{ route('admin.order.invoice', $item->id) }}" class="btn btn-sm btn-primary">Invoice</a>
                        @endif
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
                                    @if ($item->payment_method == '')
                                               Payment Processing
                                             @elseif($item->payment_method == "online_payment")
                                                Online Payment
                                                 
                                             @else
                                               Cash on Delivery
                                             @endif
                                </span>
                                @if ($item->transactionDetails)
                                    <span class="text-dark mb-2 fw-bold">
                                        ( &#8377;{{ number_format($paymentAmount) ?? '' }} )
                                    </span>
                                @endif
                            </p>
                        </div>

                       
                    </td>

                    <td>
                        <div class="btn-group action-btns">
                            <a href="{{ route('admin.order.view', $item->id) }}" class="btn btn-primary" data-bs-toggle="tooltip" title="Order Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </a>

                            @if ($item->is_live_order == 1)
                                <a href="javascript: void(0)" data-bs-toggle="tooltip" title="This is a LIVE Order. Tap to make this a DUMMY Order" onclick="typeUpdate({{$item->id}}, 0)" class="btn btn-success order-type">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                </a>
                            @else
                                <a href="javascript: void(0)" data-bs-toggle="tooltip" title="This is a DUMMY Order. Tap to make this a LIVE Order" onclick="typeUpdate({{$item->id}}, 1)" class="btn btn-danger order-type">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                </a>
                            @endif
                        </div>
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

        // order type update
        function typeUpdate(orderId, type) {
            const res = confirm('Are you sure ?');
            const liveOrder = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>';
            const dummyOrder = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>';

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
                                $('#row_'+orderId+' .action-btns .order-type').removeClass('btn-danger').addClass('btn-success').html(liveOrder);
                            } else {
                                $('#row_'+orderId+' .action-btns .order-type').removeClass('btn-success').addClass('btn-danger').html(dummyOrder);
                            }
                            $('#row_'+orderId+' .btn-group .btn').removeClass('disabled');
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
