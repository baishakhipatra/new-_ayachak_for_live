@extends('admin.layouts.app')

@section('page', 'Coupon')

@section('content')
<section>
    <div class="row">
        <div class="col-xl-8">
            <div class="card">    
                <div class="card-body">

                    <div class="search__filter">
                        <div class="row align-items-center justify-content-between">
                            <div class="col">
                                <ul>
                                    <li class="active"><a href="{{ route('admin.coupon.index') }}">All <span class="count">({{$data->count()}})</span></a></li>
                                    @php
                                        $activeCount = $inactiveCount = 0;
                                        foreach ($data as $catKey => $catVal) {
                                            if ($catVal->status == 1) $activeCount++;
                                            else $inactiveCount++;
                                        }
                                    @endphp
                                    <li><a href="{{ route('admin.coupon.index', ['status' => 'active'])}}">Active <span class="count">({{$activeCount}})</span></a></li>
                                    <li><a href="{{ route('admin.coupon.index', ['status' => 'inactive'])}}">Inactive <span class="count">({{$inactiveCount}})</span></a></li>
                                </ul>
                            </div>
                            <div class="col-auto">
                                <form action="{{ route('admin.coupon.index') }}" method="GET">
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
                    <form action="{{ route('admin.coupon.bulkDestroy') }}">
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
                                    </div>
									<div class="col-auto">
                                        <a href="#csvUploadModal" data-bs-toggle="modal" class="btn btn-outline-danger btn-sm">Bulk upload</a>
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
					<div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="check-column">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="flexCheckDefault" onclick="headerCheckFunc()">
                                            <label class="form-check-label" for="flexCheckDefault"></label>
                                        </div>
                                    </th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Discount type</th>
                                    <th>Amount</th>
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
                                        <a href="{{ route('admin.coupon.view', $item->id) }}">Edit</a>
                                        <a href="{{ route('admin.coupon.view', $item->id) }}">View</a>
                                        <a href="{{ route('admin.coupon.status', $item->id) }}">{{($item->status == 1) ? 'Active' : 'Inactive'}}</a>
                                        {{-- <a href="{{ route('admin.coupon.delete', $item->id) }}" class="text-danger">Delete</a> --}}
                                    </div>
                                    </td>
                                    <td>{{$item->coupon_code}}</td>
                                    <td>{{ ($item->type == 1) ? 'Percentage discount' : 'Flat discount' }}</td>
                                    <td>{!! ($item->type == 1) ? $item->amount.'%' : '&#8377; '.$item->amount !!}</td>
                                    <td>{{date('d M Y', strtotime($item->start_date))}} - {{date('d M Y', strtotime($item->end_date))}}</td>
                                    <td>Published<br/>{{date('d M Y', strtotime($item->created_at))}}</td>
                                    <td><span class="badge bg-{{($item->status == 1) ? 'success' : 'danger'}}">{{($item->status == 1) ? 'Active' : 'Inactive'}}</span></td>
                                </tr>
                                @empty
                                <tr><td colspan="100%" class="small text-muted">No data found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
						</div>
                    </form>    
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.coupon.store') }}" enctype="multipart/form-data">
                    @csrf
                        <h4 class="page__subtitle">Add New</h4>
                        <div class="form-group mb-3">
                            <label class="label-control">Name <span class="text-danger">*</span> </label>
                            <input type="text" name="name" placeholder="" class="form-control" value="{{old('name')}}">
                            @error('name') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Coupon code <span class="text-danger">*</span> </label>
                            <input type="text" name="coupon_code" placeholder="" class="form-control" value="{{old('coupon_code')}}">
                            @error('coupon_code') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Type <span class="text-danger">*</span> </label>
                            <select type="text" name="type" placeholder="" class="form-control" value="{{old('type')}}">
                                <option value="1">Percentage discount</option>
                                <option value="2">Flat discount</option>
                            </select>
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
                            <input type="hidden" name="is_coupon" value="1">
                            <button type="submit" class="btn btn-sm btn-danger">Add New</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- bulk upload variation modal --}}
<div class="modal fade" id="csvUploadModal" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Bulk Upload 
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('admin.coupon.csv.upload') }}" enctype="multipart/form-data" id="borrowerCsvUpload">@csrf
                    <input type="file" name="file" class="form-control" accept=".csv">
                    <br>
                    <a href="{{ asset('backend_asset/static/coupon-sample.csv') }}">Download Sample CSV</a>
                    <br>
                    <button type="submit" class="btn btn-danger mt-3" id="csvImportBtn">Import <i class="fas fa-upload"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>
</section>
@endsection
@section('script')
	@if (session('csv'))
		 <script>
			 swal("Success!", "{{ session('csv') }}", "success");
		 </script>
	@endif

@endsection