@extends('admin.layouts.app')

@section('page', 'Customer')

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
                                    <li class="active"><a href="{{route('admin.customer.index')}}">All <span class="count">({{$data->count()}})</span></a></li>
                                    @php
                                        $activeCount = $inactiveCount = 0;
                                        foreach ($data as $key => $customerValue) {
                                        if ($customerValue->status == 1)$activeCount++;
                                        else $inactiveCount++;
                                        }
                                    @endphp
                                    @endphp
                                    <li><a href="{{route('admin.customer.index',['status' => 'active'])}}">Active <span class="count">{{$activeCount}}</span></a></li>
                                    <li><a href="{{route('admin.customer.index',['status' => 'inactive'])}}">Inactive <span class="count">{{$inactiveCount}}</span></a></li>
                                </ul>
                            </div>
                            <div class="col-auto">
                                <form action="{{ route('admin.customer.index')}}" method="GET">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-auto">
                                            <input type="search" name="term" class="form-control" placeholder="Search here.." id="term" value="{{app('request')->input('term')}}" autocomplete="off">
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Search Customer</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('admin.customer.bulkDestroy') }}">
                        <div class="filter">
                            <div class="row align-items-center justify-content-between">
                            <div class="col">
                                <div class="row g-3 align-items-center">
                                    <div class="col-auto">
                                    <select class="form-control" name="bulk_action">
                                        <option value=" hidden selected">Bulk Action</option>
                                        <option value="delete">Delete</option>
                                    </select>
                                    </div>
                                    <div class="col-auto">
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Apply</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                {{-- <p>{{$data->count()}} Items</p> --}}
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
                            </div>
                            </div>
                        </div>

                        <table class="table customer-table">
                            <thead>
                                <tr>
                                    <th class="check-column">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="flexCheckDefault" onclick="headerCheckFunc()">
                                        <label class="form-check-label" for="flexCheckDefault"></label>
                                    </div>
                                    </th>
                                    <th class="text-center"><i class="fi fi-br-picture"></i></th>
                                    <th>Name</th>
                                    {{-- <th>Orders</th> --}}
                                    <th width="30%">Contact</th>
                                    <th>Gender</th>
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
                                    <td class="text-center column-thumb">
                                        {{-- <img src="{{ asset($item->image) }}"> --}}
                                        @if($item->image)
                                            <img src="{{asset($item->image)}}" alt="" style="height: 100px" class="mr-4">
                                        @else
                                            <img src="{{asset('backend_asset/images/placeholder-image.jpg')}}" alt="" class="mr-4" style="width: 100px;height: 100px;border-radius: 50%;">
                                        @endif
                                    </td>
                                    <td>
                                        {{$item->fname.' '.$item->lname}}
                                        <div class="row__action">
                                            <a href="{{ route('admin.customer.view', [$item->id, 'mode'=>'edit']) }}">Edit</a>
                                            <a href="{{ route('admin.customer.view', [$item->id, 'mode'=>'view']) }}">View</a>
                                            <a href="{{ route('admin.customer.status', $item->id) }}">{{($item->status == 1) ? 'Active' : 'Inactive'}}</a>
                                            <a href="{{route('admin.customer.getPerUserOrder',$item->id)}}">{{count(perCustomerList($item->id))}} Orders</a>
                                            {{-- <a href="{{ route('admin.customer.delete', $item->id) }}" class="text-danger">Delete</a> --}}
                                        </div>
                                    </td>
                                    {{-- <td><a href="{{route('admin.customer.getPerUserOrder',$item->id)}}">{{count(perCustomerList($item->id))}} Orders</a></td> --}}
                                    <td>{{ $item->email }} <br> {{ $item->mobile }}</td>
                                    <td>{{$item->gender}}</td>
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
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.customer.store') }}" enctype="multipart/form-data">
                    @csrf
                        <h4 class="page__subtitle">Add New</h4>
                        <div class="form-group mb-3">
                            <label class="label-control">First Name <span class="text-danger">*</span> </label>
                            <input type="text" name="fname" placeholder="" class="form-control" value="{{old('fname')}}">
                            @error('fname') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Last Name <span class="text-danger">*</span> </label>
                            <input type="text" name="lname" placeholder="" class="form-control" value="{{old('lname')}}">
                            @error('lname') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Email <span class="text-danger">*</span> </label>
                            <input type="email" name="email" placeholder="" class="form-control" value="{{old('email')}}">
                            @error('email') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Mobile <span class="text-danger">*</span> </label>
                            <input type="number" name="mobile" style="-webkit-appearance: none; margin:0px" placeholder="" class="form-control" value="{{old('mobile')}}">
                            @error('mobile') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Gender <span class="text-danger">*</span> </label>
                            <select class="form-control" name="gender">
                                <option value="" hidden selected>Select...</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="trans">Trans</option>
                                <option value="other">Other</option>
                            </select>
                            @error('gender') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Password <span class="text-danger">*</span> </label>
                            <input type="password" name="password" placeholder="" class="form-control" value="{{old('password')}}">
                            @error('password') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-danger">Add New</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection