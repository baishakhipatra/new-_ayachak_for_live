@extends('admin.layouts.app')

@section('page', 'Order report')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<section>
    <div class="card card-body">
        <div class="row">
            <div class="col-md-12">
                <form action="" method="GET">
                    <div class="row g-3 align-items-end mb-4">
                        <div class="col-auto">
                            <label for="" class="small text-muted">From</label>
                            <input type="date" name="from_date" id="from_date" class="form-control" value="{{request()->input('from_date') ?? date('Y-m-01') }}">
                        </div>

                        <div class="col-auto">
                            <label for="" class="small text-muted">To</label>
                            <input type="date" name="to_date" id="to_date" class="form-control" value="{{request()->input('to_date') ?? date('Y-m-d') }}">
                        </div>

                        <div class="col-auto">
                            <label for="collection" class="small text-muted">Collection</label>
                            <select name="collection" class="form-control" id="collection">
                                <option value="" disabled>Select</option>
                                <option value="" {{request()->input('category') == 'all' ? 'selected' : ''}}>All</option>
                                @foreach ($data->collections as $collection)
                                    <option value="{{$collection->id}}" {{request()->input('collection') == $collection->id ? 'selected' : ''}}>{{$collection->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- <div class="col-auto">
                            <label for="category" class="small text-muted">Category</label>
                            <select name="category" class="form-control" id="category">
                                <option value="" disabled>Select</option>
                                <option value="all" {{request()->input('category') == 'all' ? 'selected' : ''}}>All</option>
                                @foreach ($data->categories as $category)
                                    <option value="{{$category->id}}" {{request()->input('category') == $category->id ? 'selected' : ''}}>{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div> --}}

                        <div class="col-auto">
                            <label for="product" class="small text-muted">Product</label>
                            <select name="product" class="form-control select2" id="product">
                                <option value="" disabled>Select</option>
                                <option value="" {{request()->input('product') == 'all' ? 'selected' : ''}}>All</option>
                                @foreach ($data->products as $product)
                                    <option value="{{$product->code}}" {{request()->input('product') == $product->code ? 'selected' : ''}}>{{$product->code}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-auto">
                            <label for="" class="small text-muted">Payment type</label>
                            <select name="payment_type" class="form-control" id="payment_type">
                                <option value="">Select</option>
                                <option value="cash_on_delivery" {{request()->input('payment_type') == 'cash_on_delivery' ? 'selected' : ''}}>Cash on delivery</option>
                                <option value="online_payment" {{request()->input('payment_type') == 'online_payment' ? 'selected' : ''}}>Online Payment</option>
                            </select>
                        </div>

                        <div class="col-auto">
                            <label for="" class="small text-muted">Search for Order No</label>
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

    {{-- <div class="row">
        <div class="col-md-3">
            <div class="card card-body">
                Today's Order
                <br>
                &#8377;{{ number_format($data->daily_order->daily_order) }}
                <p class="small text-muted">
                    {{date('Y-m-d')}}
                </p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-body">
                This week's Order
                <br>
                &#8377;{{ number_format($data->weekly_order->weekly_order) }}
                <p class="small text-muted">
                    {{$data->weekStartsFrom}} - {{$data->weekEndsIn}}
                </p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-body">
                Monthly Order
                <br>
                &#8377;{{ number_format($data->monthly_order->monthly_order) }}
                <p class="small text-muted">
                    {{request()->input('from_date') ?? date('Y-m-01') }} - {{request()->input('to_date') ?? date('Y-m-d') }}
                </p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-body">
                Total Order till now
                <br>
                &#8377;{{ number_format($data->total_order->total_order) }}
                <p class="small text-muted">
                    {{ date('Y-m-d', strtotime($data->first_order_date->created_at)) }} - 
                    {{ date('Y-m-d', strtotime($data->last_order_date->created_at)) }}
                </p>
            </div>
        </div>
    </div> --}}

    <div class="row">
        <div class="col-12">
            <table class="table">
                <thead>
                <tr>
                    <th>#SR</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Total Amount</th>
                    <th>Paid Amount</th>
                    <th>Payment Status</th>
                    <th>Payment Type</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                    @php
                        $all_orders_total_amount = 0;
                    @endphp

                    @forelse ($data->all_orders as $index => $item)
                   
                        @php
                            $all_orders_total_amount += ($item->offer_price * $item->qty);
                            $trans=\App\Models\Transaction::where('order_id',$item->order_id)->first();
					        $couponDetails=\App\Models\Coupon::where('coupon_code',$item->coupon_code)->first();
                        @endphp

                        <tr id="row_{{$item->id}}">
                            <td>
                                {{ $index + 1 }}
                            </td>
                            <td>
                                <p class="text-dark mb-1">{{$item->sku_code}}</p>
                                <p class="small text-muted mb-1">{{$item->product_name}}</p>
                            </td>
                            <td>
                                <p class="text-dark mb-1">{{$item->qty}}</p>
                            </td>
                            <td>
                                <p class="small text-dark mb-1">#{{$item->order_no}}</p>
                            </td>
                            <td>
                                <p class="small text-dark mb-1">{{$item->fname.' '.$item->lname}}</p>
                            </td>
                            <td>
                                {{--<p class="small text-dark mb-1">&#8377; {{ number_format($item->final_amount) }}</p>--}}
								@if ($item->coupon_code != null)
                            		@if($couponDetails)
										<div class="">
											<p class="small text-muted mb-1">Total: {{$item->amount ?: ''}}</p>
											 <p class="small mb-0">Discount code: {{$couponDetails->coupon_code ??'' }}</p>
											<p class="small mb-0">Discount: {{$item->coupon_code_discount_type == 'Percentage' ? $couponDetails->amount .'%' : 'Rs. ' .$couponDetails->amount }}</p>
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
                            
                            @if ($trans)
                            @php
                                $getPaymentResp = getPaymentResp($trans->online_payment_id);
                                $paymentStatus = $getPaymentResp->status;
                                $paymentAmount = $getPaymentResp->amount/100;
                            @endphp
                            @endif
                            <td>
                                @if ($item->payment_method == "online_payment")
									@if ($trans)
									<span class="text-dark mb-2 fw-bold">
										<p class="small text-dark mb-1">&#8377; {{ number_format($paymentAmount) }} 
									</span>
									@endif
                                @elseif($item->payment_method == "cash_on_delivery")
									
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
												<p class="small text-dark mb-1">&#8377;{{ number_format($item->final_amount) }}
											</span>
											@endif
						         		
								@else
								<span>Transaction not captured</span>
								@endif
                              
                            </td>
                            <td>
                                @if ($item->payment_method == "online_payment")<span class="btn btn-sm btn-secondary">{{$paymentStatus}}</span>@endif
                            </td>
                            <td>
                                <div class="address">
                                    <p class="small text-muted mb-0">
                                        <span class="text-success mb-2 fw-bold">
                                             
                                             @if ($item->payment_method == '')
                                               Payment Processing
                                             @elseif($item->payment_method == "online_payment")
                                                Online Payment
                                                 
                                             @else
                                               Cash on Delivery
                                             @endif
                                            
                                        </span>
                                      
                                    </p>
                                </div>
                            </td>
                            <td>
                                <div class="order-time">
                                    <p class="small text-muted mb-0">
                                        <span class="text-dark font-weight-bold mb-2">
                                            {{date('j M Y g:i A', strtotime($item->created_at))}}
                                        </span>
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="100%" class="small text-muted">No data found</td></tr>
                    @endforelse
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <p class="small text-dark mb-1 fw-bold">TOTAL</p>
                        </td>
                        <td>
                            <p class="small text-dark mb-1 fw-bold">&#8377; {{ number_format($all_orders_total_amount) }}</p>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
</section>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endsection
