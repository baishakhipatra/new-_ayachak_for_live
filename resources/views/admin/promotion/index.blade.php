@extends('admin.layouts.app')

@section('page', 'Promotions')

@section('content')
<section>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#SR</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Coupon</th>
                                <th>Created Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $index => $item)
                            <tr>
                                <td>{{$index+1}}</td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->email}}</td>
                                <td>{{$item->phone}}</td>
                                <td>{{$item->coupon_code}}
									@if($item->order_id == null)
										<p class="small text-danger">Not used</p>
									@else
										<p class="small">Order ID: <a href="{{route('admin.order.view', $item->order_id)}}">{{ $item->order_no }}</a></p>
									@endif
								</td>
                                <td>{{date('j F, Y g:i a', strtotime($item->created_at))}}</td>
                            </tr>
                            @empty
                            <tr><td colspan="100%" class="small text-muted">No data found</td></tr>
                            @endforelse
                        </tbody>
                    </table>    
                </div>
            </div>
        </div>
    </div>
</section>
@endsection