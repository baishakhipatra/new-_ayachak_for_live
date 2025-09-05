@extends('admin.layouts.app')

@section('page', 'Voucher detail')

@section('content')
<section>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">    
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="text-muted">{{ $data->name }}</h3>
                            {{-- <h6>{{ $data->name }}</h6> --}}
                        </div>
                        <div class="col-md-4 text-end">
                            @if ($data->end_date < \Carbon\Carbon::now() )
                            <h3 class="text-danger mt-3 fw-bold">EXPIRED</h3>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <p class="small text-muted mt-4 mb-2">Details</p>
                            <table class="">
                                <tr>
                                    <td class="text-muted">No of vouchers: </td>
                                    <td>{{count($coupons)}}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Discount Type: </td>
                                    <td>{{$data->type == 1 ? 'Percentage discount' : 'Flat discount'}}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Amount: </td>
                                    <td>{{$data->type == 1 ? $data->amount.' %' : 'Rs. '. $data->amount}}</td>
                                </tr>
                                {{-- <tr>
                                    <td class="text-muted">Max time usage</td>
                                    <td>{{$data->max_time_of_use}}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Max time usage for single user</td>
                                    <td>{{$data->max_time_one_can_use}}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">No of usage</td>
                                    <td>{{$data->no_of_usage}}</td>
                                </tr> --}}
                                <tr>
                                    <td class="text-muted">Start date: </td>
                                    <td>{{ date('j M Y', strtotime($data->start_date)) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">End date: </td>
                                    <td>{{ date('j M Y', strtotime($data->end_date)) }}</td>
                                </tr>
                            </table>

                            <hr>

                            <p class="small text-muted mt-4 mb-2">Vouchers</p>

                            <table class="table table-sm">
                                <tr>
                                    <th>#SR</th>
                                    <th>Voucher</th>
                                    <th>Usage</th>
                                </tr>
                                @foreach ($coupons as $couponKey => $coupon)
                                <tr>
                                    <td>{{$couponKey+1}}</td>
                                    <td>{{$coupon->coupon_code}}</td>
                                    <td>
                                        @php
                                            $usage = App\Models\CouponUsage::where('coupon_code_id', $coupon->id)->first();
                                        @endphp

                                        @if($usage)
                                            @if($usage->orderDetails)
                                                @if($usage->order_id != 0)
                                                    <a href="{{ route('admin.order.view', $usage->order_id) }}">{{$usage->orderDetails->order_no}}</a>
                                                @endif
                                            @endif
                                        @else
                                            <p class="small text-danger">Not used yet</p>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </table>

                            {{-- <table class="table table-sm">
                                <tr>
                                    <th>#SR</th>
                                    <th>Total amount</th>
                                    <th>Discount</th>
                                    <th>Final amount</th>
                                    <th>User details</th>
                                    <th>Order details</th>
                                    <th>Time</th>
                                </tr>
                                @foreach ($usage as $usageKey => $usageValue)
                                <tr>
                                    <td>{{$usageKey+1}}</td>
                                    <td>&#8377;{{$usageValue->total_checkout_amount}}</td>
                                    <td>&#8377;{{$usageValue->discount}}</td>
                                    <td>&#8377;{{$usageValue->final_amount}}</td>
                                    <td>
                                        @if($usageValue->user_id != 0)
                                            {{$usageValue->userDetails->name}}
                                        @endif
                                        <p class="small mb-0">{{$usageValue->email}} </p>
                                        <p class="small">{{$usageValue->ip}} </p>
                                    </td>
                                    <td>
                                        @if($usageValue->orderDetails)
                                            @if($usageValue->order_id != 0)
                                                <a href="{{ route('admin.order.view', $usageValue->order_id) }}">{{$usageValue->orderDetails->order_no}}</a>
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{ date('j M Y H:i a', strtotime($usageValue->usage_time)) }}</td>
                                </tr>
                                @endforeach
                            </table> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.voucher.update', $data->id) }}" enctype="multipart/form-data">
                    @csrf
                        <h4 class="page__subtitle">Edit</h4>
                        <div class="form-group mb-3">
                            <label class="label-control">Name <span class="text-danger">*</span> </label>
                            <input type="text" name="name" placeholder="" class="form-control" value="{{ $data->name }}">
                            @error('name') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Voucher code <span class="text-danger">*</span> </label>
                            <input type="text" name="coupon_code" placeholder="" class="form-control" value="{{ $data->coupon_code }}">
                            @error('coupon_code') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Type <span class="text-danger">*</span> </label>
                            <input type="text" name="type" placeholder="" class="form-control" value="{{ $data->type }}">
                            @error('type') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Amount <span class="text-danger">*</span> </label>
                            <input type="number" name="amount" placeholder="" class="form-control" value="{{ $data->amount }}">
                            @error('amount') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Max time of use <span class="text-danger">*</span> </label>
                            <input type="number" name="max_time_of_use" placeholder="" class="form-control" value="{{ $data->max_time_of_use }}">
                            @error('max_time_of_use') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Max time one can use <span class="text-danger">*</span> </label>
                            <input type="number" name="max_time_one_can_use" placeholder="" class="form-control" value="{{ $data->max_time_one_can_use }}">
                            @error('max_time_one_can_use') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Start date <span class="text-danger">*</span> </label>
                            <input type="date" name="start_date" placeholder="" class="form-control" value="{{ date('Y-m-d', strtotime($data->start_date)) }}">
                            @error('start_date') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">End date <span class="text-danger">*</span> </label>
                            <input type="date" name="end_date" placeholder="" class="form-control" value="{{ date('Y-m-d', strtotime($data->end_date)) }}">
                            @error('end_date') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-danger">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div> --}}
    </div>
</section>
@endsection