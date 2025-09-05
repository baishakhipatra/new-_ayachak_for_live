@extends('layouts.app')

@section('page', 'Product orders')

@section('content')
<div class="col-sm-12">
    <div class="profile-card">
        <h3>Product orders</h3>
        <div class="row">
            <div class="col-12 text-right">
                <a data-toggle="collapse" href="#storesList" class="btn btn-sm btn-danger">View Stores</a>
                {{-- <a href="#" class="btn btn-sm btn-danger">CSV export</a>
                <a href="#" class="btn btn-sm btn-danger">PDF export</a> --}}
            </div>
            <div class="col-12 collapse" id="storesList">
                <div class="card card-body my-3">
                    <p>Stores under {{ auth()->guard('web')->user()->name }}</p>

                    <div class="row">
                        @forelse ($stores as $store)
                        <div class="col-md-3">
                            <div class="card card-body p-2">
                                <h5 class="mb-0 card-title">{{ $store->store_name }}</h5>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center">
                            <p class="mb-0 text-muted">No data found</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-12">
                <form action="" method="get" class="row">
                    <div class="col-md-2">
                        <label class="small text-muted mb-0" for="collection">Range</label>
                        <select name="collection" id="collection" class="form-control form-control-sm">
                            <option value="" disabled>Select</option>
                            <option value="all" selected>All</option>
                            @foreach ($collections as $item)
                                <option value="{{ $item->id }}" {{ ($request->collection == $item->id) ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="small text-muted mb-0" for="from">Date from</label>
                            <input type="date" name="from" id="from" class="form-control form-control-sm" value="{{ (request()->input('from')) ? request()->input('from') : date('Y-m-01') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="small text-muted mb-0" for="to">Date to</label>
                            <input type="date" name="to" id="to" class="form-control form-control-sm" value="{{ (request()->input('to')) ? request()->input('to') : date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="small text-muted mb-0" for="orderBy">Sort by</label>
                        <select name="orderBy" id="orderBy" class="form-control form-control-sm">
                            <option value="" disabled>Select</option>
                            <option value="date_desc" {{ ($request->orderBy == "date_desc") ? 'selected' : '' }}>Purchase date Desc</option>
                            <option value="date_asc" {{ ($request->orderBy == "date_asc") ? 'selected' : '' }}>Purchase date Asc</option>
                            <option value="qty_asc" {{ ($request->orderBy == "qty_asc") ? 'selected' : '' }}>Quantity Asc</option>
                            <option value="qty_desc" {{ ($request->orderBy == "qty_desc") ? 'selected' : '' }}>Quantity Desc</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="small text-muted mb-0" for="style_no">Style number</label>
                            <input type="search" name="style_no" id="style_no" class="form-control form-control-sm" placeholder="eg: 423" value="{{ $request->style_no }}">
                        </div>
                    </div>
                    <div class="col-md-2 text-right">
                        <div class="form-group">
                            <label class="small text-muted mb-0" for="" style="visibility: hidden;">save</label>
                            <br>
                            <button type="submit" class="btn btn-sm btn-danger">Apply</button>

                            <a href="{{url()->current()}}" class="btn btn-sm btn-light border" data-toggle="tooltip" data-placement="top" title="Remove filter">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-12">
                <table class="table table-hover table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Style no</th>
                            <th>Product</th>
                            <th class="text-right">Quantity</th>
                            <th class="text-right">Order details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>
                                    <p class="mb-0">Style no #{{$product->style_no}}</p>
                                </td>
                                <td>
                                    <p class="small mb-0 text-muted">{{$product->product_name}}</p>
                                </td>
                                <td class="text-right">
                                    <h5 class="mb-0">{{$product->product_count}}</h5>
                                </td>
                                <td class="text-right">
                                    <a href="javascript:void(0)" onclick="productOrders({{$product->product_id}})">View details</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="text-center">
                                    <p class="mb-0 text-muted">No data found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="productOrderDetails">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"></div>
            <div class="modal-body"></div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        function productOrders(productId) {
            $.ajax({
                url : "{{ route('front.distributor.store.orders.product.index') }}",
                method: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'productId': productId,
                },
                success: function(result) {
                    if (result.status == 200) {
                        var content = `
                        <table class="table table-sm table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Order time</th>
                                    <th>Quantity</th>
                                    <th>Color</th>
                                    <th>Size</th>
                                </tr>
                            </thead>`;

                        var totalQty = 0;
                        $.each(result.data, (key, value) => {
                            totalQty += value.qty;  
                            var date = new Date(value.created_at).toLocaleTimeString('en-us', {month:'short', year:'numeric', day:'numeric'});

                            content += `
                            <tr>
                                <td>${date}</td>
                                <td>${value.qty}</td>
                                <td>${ (value.color_details) ? value.color_details.name : '' }</td>
                                <td>${value.size}</td>
                            </tr>`;
                        });

                        content += `
                            <tr>
                                <th>Total</th>
                                <th>${totalQty}</th>
                                <td colspan="2"></td>
                            </tr>
                        </table>`;

                        $('#productOrderDetails .modal-body').html(content);
                        $('#productOrderDetails .modal-header').html('<h5 class="modal-title">'+result.data[0].product_name+' (Style no #'+result.data[0].style_no+')</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                    } else {
                        $('#productOrderDetails .modal-body').html('<h5>Something happened. Try again.</h5>');
                    }
                    $('#productOrderDetails').modal('show');
                }
            });
        }
    </script>
@endsection