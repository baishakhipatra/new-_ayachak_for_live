@extends('front.layout.app')
@section('page-title', 'Order Details')
@section('content')
<section class="main">
    <div class="container">
        <div class="profile-wrapper">
            <div class="row">
                <div class="col-lg-3 mb-4 mb-md-5 mb-lg-0">
                    @include('front/sidebar_profile')
                </div>
                @if($orders)
                <div class="col-lg-9">
                    <div class="profile-right">
                        <h2>Order History</h2>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="profile-order-place">
                                    <table>
                                        <thead>
                                            <th>Order Id</th>
                                            <th>Date</th>
                                            <th>Total Amount</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </thead>
                                        <tbody>
                                            @foreach($orders as $orders)
                                            <tr>
                                                <td>{{$orders->order_no}}</td>
                                                <td>{{$orders->created_at->format('d/m/Y')}}</td>
                                                <td>₹{{$orders->final_amount}}</td>
                                                <td>
                                                    @if ($orders->status == 1) <span class="label complete">Processing</span>
                                                    @elseif ($orders->status == 2) <span class="label complete">Confirmed</span>
                                                    @elseif ($orders->status == 3) <span class="label complete">Shipped</span>
                                                    @elseif ($orders->status == 4) <span class="label complete">Delivered</span>
                                                    @elseif ($orders->status == 5) <span class="label cancel">Cancelled</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{route('front.order',$orders->order_no)}}" class="view"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                           
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                    <div class="d-flex justify-content-center align-items-center">
                        <p class="text-muted fs-5">You don’t have any orders yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
