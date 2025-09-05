@extends('admin.layouts.app')

@section('page', 'Customer')

@section('content')
<section>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">    
                <div class="card-body">
                    <div class="search__filter">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto">
                                <form action="" method="GET">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-auto">
                                            <input type="search" name="term" class="form-control" placeholder="Search here.." id="term" value="{{app('request')->input('term')}}" autocomplete="off">
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Search Product</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <form action="">
                        <table class="table customer-table">
                            <thead>
                                <tr>
                                    <th width="10%" class="text-center">#SR.NO</th>
                                    <th width="25%">Order Id</th>
                                    <th width="20%">Ordered Date</th>
                                    <th width="35%">Order Status</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse (perCustomerList($id) as $index => $item)
                                <tr>
                                    <td class="text-center">{{$index+1}}</td>
                                    <td>{{$item->order_no}} <br> Rs.{{$item->amount}}</td>
                                    <td>{{$item->created_at}}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="javascript: void(0)" onclick="productStatusUpdate({{$item->id}}, 1)" type="button" class="status_1 btn btn-outline-primary btn-sm {{($item->status == 1) ? 'active' : ''}}">New</a>
                
                                            <a href="javascript: void(0)" onclick="productStatusUpdate({{$item->id}}, 2)" type="button" class="status_2 btn btn-outline-primary btn-sm {{($item->status == 2) ? 'active' : ''}}">Confirm</a>
                
                                            <a href="javascript: void(0)" onclick="productStatusUpdate({{$item->id}}, 3)" type="button" class="status_3 btn btn-outline-primary btn-sm {{($item->status == 3) ? 'active' : ''}}">Shipped</a>
                
                                            <a href="javascript: void(0)" onclick="productStatusUpdate({{$item->id}}, 4)" type="button" class="status_4 btn btn-outline-success btn-sm {{($item->status == 4) ? 'active' : ''}}">Delivered</a>
                
                                            <a href="javascript: void(0)" onclick="productStatusUpdate({{$item->id}}, 5)" type="button" class="status_5 btn btn-outline-danger btn-sm {{($item->status == 5) ? 'active' : ''}}">Cancelled</a>
                                        </div>
                                    </td>
                                    <td><a href="{{route('admin.order.view',$item->id)}}" class="btn btn-danger"><i class="fa fa-eye"></i>View</a></td>
                                </tr>
                                @empty
                                <tr><td colspan="100%" class="small text-muted">No data found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </form>    
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
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
    </script>
@endsection