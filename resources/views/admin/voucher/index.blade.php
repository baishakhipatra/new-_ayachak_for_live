@extends('admin.layouts.app')

@section('page', 'Voucher')

@section('content')
<section>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">

                    <div class="search__filter">
                        <div class="row align-items-center justify-content-between">
                            <div class="col">
                                <ul>
                                    <li class="active"><a href="{{ route('admin.voucher.index') }}">All <span class="count">({{$data->count()}})</span></a></li>
                                    @php
                                        $activeCount = $inactiveCount = 0;
                                        foreach ($data as $catKey => $catVal) {
                                            if ($catVal->status == 1) $activeCount++;
                                            else $inactiveCount++;
                                        }
                                    @endphp
                                    <li><a href="{{ route('admin.voucher.index', ['status' => 'active'])}}">Active <span class="count">({{$activeCount}})</span></a></li>
                                    <li><a href="{{ route('admin.voucher.index', ['status' => 'inactive'])}}">Inactive <span class="count">({{$inactiveCount}})</span></a></li>
                                </ul>
                            </div>
                            <div class="col-auto">
                                <form action="{{ route('admin.voucher.index') }}" method="GET">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-auto">
                                            <input type="search" name="term" class="form-control" placeholder="Search here.." id="term" value="{{app('request')->input('term')}}" autocomplete="off">
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Search</button>
                                        </div>
                                    </div>
                                </form>
                                </form>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('admin.voucher.bulkDestroy') }}">
                        <div class="filter">
                            <div class="row align-items-center justify-content-between">
                            <div class="col">
                                <div class="row g-3 align-items-center">
                                    <div class="col-auto">
                                        <select name="bulk_action" class="form-control">
                                            <option value=" hidden selected">Bulk Action</option>
                                            <option value="delete">Delete</option>
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Apply</button>
                                        <a href="{{ route('admin.voucher.create') }}" class="btn btn-sm btn-danger">Generate new vouchers</a>
                                        <a href="{{ route('admin.voucher.csv.export') }}" class="btn btn-sm btn-warning">Export all vouchers into CSV</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                @php
                                    if (!empty($_GET['status'])) {
                                        if ($_GET['status'] == 'active') {
                                            ($activeCount>1) ? $itemShow = 'Items' : $itemShow = 'Item';
                                            echo '<p>'.$activeCount.' '.$itemShow.'</p>';
                                        } elseif ($_GET['status'] == 'inactive') {
                                            ($inactiveCount>1) ? $itemShow = 'Items' : $itemShow = 'Item';
                                            echo '<p>'.$inactiveCount.' '.$itemShow.'</p>';
                                        }
                                    } else {
                                        ($data->count() > 1) ? $itemShow = 'Items' : $itemShow = 'Item';
                                        echo '<p>'.$data->count().' '.$itemShow.'</p>';
                                    }
                                @endphp
                                {{-- <p>{{$data->count()}} Items</p> --}}
                            </div>
                            </div>
                        </div>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="check-column">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="flexCheckDefault" onclick="headerCheckFunc()">
                                            <label class="form-check-label" for="flexCheckDefault"></label>
                                        </div>
                                    </th>
                                    <th>Details</th>
                                    <th>Discount type</th>
                                    <th>Amount</th>
                                    <th>Generated Vouchers</th>
                                    <th>Validity</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $index => $item)
                                @php
                                if (!empty($_GET['status'])) {
                                    if ($_GET['status'] == 'active') {
                                        if ($item->status == 0) continue;
                                    } else {
                                        if ($item->status == 1) continue;
                                    }
                                }
                                @endphp
                                <tr>
                                    <td class="check-column">
                                        <input name="delete_check[]" class="tap-to-delete" type="checkbox" onclick="clickToRemove()" value="{{$item->id}}" 
                                        @php
                                        if (old('delete_check')) {
                                            if (in_array($item->id, old('delete_check'))) {
                                                echo 'checked';
                                            }
                                        }
                                        @endphp>
                                    </td>
                                    <td>
                                    {{$item->name}}
                                    <div class="row__action">
                                        {{-- <a href="{{ route('admin.voucher.view', $item->id) }}">Edit</a> --}}
                                        <a href="{{ route('admin.voucher.view', $item->slug) }}">View</a>
                                        <a href="{{ route('admin.voucher.status', $item->id) }}">{{($item->status == 1) ? 'Active' : 'Inactive'}}</a>
                                        {{-- <a href="{{ route('admin.voucher.delete', $item->id) }}" class="text-danger">Delete</a> --}}
                                    </div>
                                    </td>
                                    <td>{{$item->type == 1 ? 'Percentage discount' : 'Flat discount'}}</td>
                                    <td>{{$item->type == 1 ? $item->amount. ' %' : 'Rs. '.$item->amount}}</td>
                                    <td>
                                        @php
                                            $couponsCount = \DB::table('coupons')->where('slug', $item->slug)->count();
                                        @endphp
                                        <div class="btn-group">
                                            <a href="{{ route('admin.voucher.view', $item->slug) }}" class="btn btn-sm btn-primary">{{$couponsCount}}</a>
                                            <a href="{{ route('admin.voucher.detail.csv.export', $item->slug) }}" class="btn btn-sm btn-warning">Export generated vouchers</a>
                                        </div>
                                    </td>
                                    <td>{{date('d M Y', strtotime($item->start_date))}} - {{date('d M Y', strtotime($item->end_date))}}</td>
                                    <td>Published<br/>{{date('d M Y', strtotime($item->created_at))}}</td>
                                    <td><span class="badge bg-{{($item->status == 1) ? 'success' : 'danger'}}">{{($item->status == 1) ? 'Active' : 'Inactive'}}</span></td>
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

        {{-- <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.voucher.store') }}" enctype="multipart/form-data">
                    @csrf
                        <h4 class="page__subtitle">Add New</h4>
                        <div class="form-group mb-3">
                            <label class="label-control">Name <span class="text-danger">*</span> </label>
                            <input type="text" name="name" placeholder="" class="form-control" value="{{old('name')}}">
                            @error('name') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">voucher code <span class="text-danger">*</span> </label>
                            <input type="text" name="coupon_code" placeholder="" class="form-control" value="{{old('coupon_code')}}">
                            @error('coupon_code') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Type <span class="text-danger">*</span> </label>
                            <input type="text" name="type" placeholder="" class="form-control" value="{{old('type')}}">
                            @error('type') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Amount <span class="text-danger">*</span> </label>
                            <input type="number" name="amount" placeholder="" class="form-control" value="{{old('amount')}}">
                            @error('amount') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Max time of use <span class="text-danger">*</span> </label>
                            <input type="number" name="max_time_of_use" placeholder="" class="form-control" value="{{old('max_time_of_use')}}">
                            @error('max_time_of_use') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Max time one can use <span class="text-danger">*</span> </label>
                            <input type="number" name="max_time_one_can_use" placeholder="" class="form-control" value="{{old('max_time_one_can_use')}}">
                            @error('max_time_one_can_use') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Start date <span class="text-danger">*</span> </label>
                            <input type="date" name="start_date" placeholder="" class="form-control" value="{{old('start_date')}}">
                            @error('start_date') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">End date <span class="text-danger">*</span> </label>
                            <input type="date" name="end_date" placeholder="" class="form-control" value="{{old('end_date')}}">
                            @error('end_date') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="is_coupon" value="0">
                            <button type="submit" class="btn btn-sm btn-danger">Add New</button>
                        </div>
                    </form>
                </div>
            </div>
        </div> --}}
    </div>
</section>
@endsection