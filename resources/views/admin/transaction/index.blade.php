@extends('admin.layouts.app')

@section('page', 'Transaction')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.0/css/bootstrap-slider.min.css">
<style>
    .tooltip-main{
        opacity: 1;
    }
</style>

<section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <p class="small text-muted">Displaying {{$data->firstItem()}} - {{$data->lastItem()}} out of {{$data->total()}} records</p>
                        </div>

                        <div class="col-md-8 text-end">
                            <form method="GET">
                                <div class="row g-2 justify-content-end">
                                    <div class="col-auto">
                                        <label>Price range</label>
                                        <input id="range" type="text" name="amount">
                                    </div>
                                    <div class="col-auto">
                                        <input type="search" name="keyword" class="form-control" placeholder="Transaction or Order Id..." value="{{ request()->input('keyword') }}">
                                    </div>
                                    <div class="col-auto">
                                        <div class="btn-group">
                                            <button type="submit" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-danger" data-bs-original-title="Search"> <i class="fi fi-br-search"></i> </button>

                                            <a href="{{ url()->current() }}" class="btn btn-sm btn-light" data-bs-toggle="tooltip" title="" data-bs-original-title="Clear search"> <i class="fi fi-br-x"></i> </a>

                                            {{-- <a href="http://127.0.0.1:8000/admin/product/sku-list/export" data-bs-toggle="tooltip" class="btn btn-sm btn-danger" title="" data-bs-original-title="Export"> <i class="fi fi-br-download"></i> </a> --}}
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <table class="table">
                <thead>
                <tr>
                    <th>#SR</th>
                    <th>User</th>
                    <th>Transaction</th>
                    <th>Order</th>
                    <th>Amount</th>
                    <th>Datetime</th>
                    {{-- <th>Status</th> --}}
                </tr>
                </thead>
                <tbody>
                    @forelse ($data as $index => $item)
                    <tr>
                        <td>
                            {{ $data->firstItem() + $index }}
                        </td>
                        <td>
                            @if($item->user_id != 0)
                                @if ($item->userDetails)
                                    <p class="small text-dark mb-1">{{$item->userDetails->fname.' '.$item->userDetails->lname}}</p>
                                    <p class="small text-muted mb-0">{{$item->userDetails->email.' | '.$item->userDetails->mobile}}</p>
                                @endif
                            @else
                                <p class="text-danger mb-0">Guest checkout</p>
                            @endif
                            {{-- <div class="row__action">
                                <a href="{{ route('admin.transaction.view', $item->id) }}">View</a>
                            </div> --}}
                        </td>
                        <td>
                            {{$item->transaction}}
                        </td>
                        <td>
							@if($item->orderDetails)
                            <a href="{{ route('admin.order.view', $item->orderDetails->id) }}" class="badge bg-primary text-decoration-none">View Order</a>
							@endif
                        </td>
                        <td>
                            <p class="small text-muted mb-1">Rs {{$item->amount}}</p>
                        </td>
                        <td>
                            <p class="small">{{date('j M Y g:i A', strtotime($item->created_at))}}</p>
                        </td>
                        {{-- <td><span class="badge bg-{{($item->status == 1) ? 'success' : 'danger'}}">{{($item->status == 1) ? 'Active' : 'Inactive'}}</span></td> --}}
                    </tr>
                    @empty
                    <tr><td colspan="100%" class="small text-muted">No data found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="pagination justify-content-end">
                {{$data->appends($_GET)->links()}}
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.0/bootstrap-slider.min.js"></script>

    <script>
        @if (!empty($request->amount))
            @php
                $amount = explode(',', $request->amount);
                $minAmount = $amount[0];
                $maxAmount = $amount[1];
            @endphp
            let val = [{{$minAmount}}, {{$maxAmount}}];
        @else
            let val = [500, 5000];
        @endif

        // Range Slider
        var slider = new Slider("#range", {
            min: {{$range->min}},
            max: {{$range->max}},
            value: val,
            range: true,
            tooltip: 'always'
        });
    </script>
@endsection