@extends('admin.layouts.app')

@section('page', 'Sub-category')

@section('content')
<section>
    <div class="row">
        <div class="col-lg-8">
            <div class="card">    
                <div class="card-body">

                    <div class="search__filter">
                        <div class="row align-items-center justify-content-between">
                            <div class="col">
                                <ul>
                                    <li class="active"><a href="{{ route('admin.subcategory.index') }}">All <span class="count">({{$data->count()}})</span></a></li>
                                    @php
                                        $activeCount = $inactiveCount = 0;
                                        foreach ($data as $catKey => $catVal) {
                                            if ($catVal->status == 1) $activeCount++;
                                            else $inactiveCount++;
                                        }
                                    @endphp
                                    <li><a href="{{ route('admin.subcategory.index', ['status' => 'active'])}}">Active <span class="count">({{$activeCount}})</span></a></li>
                                    <li><a href="{{ route('admin.subcategory.index', ['status' => 'inactive'])}}">Inactive <span class="count">({{$inactiveCount}})</span></a></li>
                                </ul>
                            </div>
                            <div class="col-auto">
                                <form>
                                <div class="row g-3 align-items-center">
                                    <div class="col-auto">
                                        <input type="search" name="" class="form-control" placeholder="Search here..">
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Search Product</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('admin.subcategory.bulkDestroy') }}">
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
                                    </div>
                                </div>
                            <div class="col-auto">
                                @php
                                if (!empty($_GET['status'])) {
                                    if ($_GET['status'] == 'active') {
                                        ($activeCount > 1) ? $itemShow = 'Items' : $itemShow = 'Item';
                                        echo '<p>'.$activeCount.' '.$itemShow.'</p>';
                                    } elseif ($_GET['status'] == 'inactive') {
                                        ($inactiveCount > 1) ? $itemShow = 'Items' : $itemShow = 'Item';
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
                                    <th class="text-center"><i class="fi fi-br-picture"></i></th>
                                    <th>Name</th>
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


                                {{-- @forelse ($data as $index => $item) --}}
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
                                        <img src="{{ asset($item->image_path) }}">
                                    </td>
                                    <td>
                                    {{$item->name}}
                                    <div class="row__action">
                                        <a href="{{ route('admin.subcategory.view', $item->id) }}">Edit</a>
                                        <a href="{{ route('admin.subcategory.view', $item->id) }}">View</a>
                                        <a href="{{ route('admin.subcategory.status', $item->id) }}">{{($item->status == 1) ? 'Active' : 'Inactive'}}</a>
                                        <a href="{{ route('admin.subcategory.delete', $item->id) }}" class="text-danger">Delete</a>
                                    </div>
                                    </td>
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

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.subcategory.store') }}" enctype="multipart/form-data">
                    @csrf
                        <h4 class="page__subtitle">Add New</h4>
                        <div class="form-group mb-3">
                            <label class="label-control">Category <span class="text-danger">*</span> </label>
                            <select class="form-control" name="cat_id">
                                <option hidden selected>Select category...</option>
                                @foreach ($categories as $index => $item)
                                    <option value="{{$item->id}}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('cat_id') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Name <span class="text-danger">*</span> </label>
                            <input type="text" name="name" placeholder="" class="form-control" value="{{old('name')}}">
                            @error('name') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Description </label>
                            <textarea name="description" class="form-control">{{old('description')}}</textarea>
                            @error('description') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="card">
                            <div class="card-header p-0 mb-3">Image <span class="text-danger">*</span></div>
                            <div class="card-body p-0">
                                <div class="w-100 product__thumb">
                                    <label for="thumbnail"><img id="output" src="{{ asset('backend_asset/images/placeholder-image.jpg') }}" /></label>
                                </div>
                                <input type="file" name="image_path" id="thumbnail" accept="image/*" onchange="loadFile(event)" class="d-none">
                                <script>
                                    var loadFile = function(event) {
                                        var output = document.getElementById('output');
                                        output.src = URL.createObjectURL(event.target.files[0]);
                                        output.onload = function() {
                                            URL.revokeObjectURL(output.src) // free memory
                                        }
                                    };
                                </script>
                            </div>
                            @error('image_path') <p class="small text-danger">{{ $message }}</p> @enderror
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