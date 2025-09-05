@extends('admin.layouts.app')

@section('page', 'Collection')

@section('content')
<section>
    <div class="row">
        <div class="col-lg-12 text-end">
            <button type="button" class="btn btn-outline-danger btn-sm mb-3"  data-bs-toggle="modal" data-bs-target="#addCat">Add New</button>
        </div>
        <div class="col-xl-12">
            <div class="card bg-none">
                <div class="card-body">
                    <div class="search__filter">
                        <div class="row align-items-center justify-content-between">
                            <div class="col">
                                <ul>
                                    <li class="active"><a href="{{ route('admin.collection.index') }}">All <span class="count">({{$data->count()}})</span></a></li>
                                    @php
                                        $activeCount = $inactiveCount = 0;
                                        foreach ($data as $key => $collValue) {
                                            if($collValue->status == 1) $activeCount++;
                                            else $inactiveCount++;
                                        }
                                    @endphp
                                    <li><a href="{{ route('admin.collection.index', ['status' => 'active'])}}">Active <span class="count">{{$activeCount}}</span></a></li>
                                    <li><a href="{{ route('admin.collection.index', ['status' => 'inactive'])}}">Inactive <span class="count">{{$inactiveCount}}</span></a></li>
                                </ul>
                            </div>
                            <div class="col-auto">
                                <form action="{{ route('admin.collection.index')}}">
                                <div class="row g-3 align-items-center">
                                    <div class="col-auto">
                                        <input type="search" name="term" id="term" class="form-control" placeholder="Search here.." value="{{app('request')->input('term')}}" autocomplete="off">
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Search Collection</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('admin.collection.bulkDestroy') }}">
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

                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="check-column">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="flexCheckDefault" onclick="headerCheckFunc()">
                                        <label class="form-check-label" for="flexCheckDefault"></label>
                                    </div>
                                    </th>
                                    <th class="text-center"><i class="fi fi-br-picture"></i> Icon</th>
                                    <th class="text-center"><i class="fi fi-br-picture"></i> Sketch</th>
                                    <th class="text-center"><i class="fi fi-br-picture"></i> Thumb</th>
                                    <th class="text-center"><i class="fi fi-br-picture"></i> Banner</th>
                                    <th>Name</th>
                                    <th>Products</th>
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
                                    <td class="text-center column-thumb">
                                        <img src="{{ asset($item->icon_path) }}">
                                    </td>
                                    <td class="text-center column-thumb">
                                        <img src="{{ asset($item->sketch_icon) }}">
                                    </td>
                                    <td class="text-center column-thumb">
                                        <img src="{{ asset($item->image_path) }}">
                                    </td>
                                    <td class="text-center column-thumb">
                                        <img src="{{ asset($item->banner_image) }}">
                                    </td>
                                    <td>
                                        <h6 class="text-dark">{{$item->name}}</h6>
                                        <div class="row__action">
                                            <a href="{{ route('admin.collection.view', [$item->id,'mode'=>'edit']) }}">Edit</a>
                                            <a href="{{ route('admin.collection.view', [$item->id,'mode'=>'view']) }}">View</a>
                                            <a href="{{ route('admin.collection.status', $item->id) }}">{{($item->status == 1) ? 'Active' : 'Inactive'}}</a>
                                            {{-- <a href="{{ route('admin.collection.delete', $item->id) }}" class="text-danger">Delete</a> --}}
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.collection.view', $item->id) }}">{{$item->ProductDetails->count()}} products</a>
                                    </td>
                                    <td>Published<br/>{{date('d M Y', strtotime($item->created_at))}}</td>
                                    <td>
                                        <span class="badge bg-{{($item->status == 1) ? 'success' : 'danger'}}">{{($item->status == 1) ? 'Active' : 'Inactive'}}</span>
                                    </td>
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

    <!-- Modal -->
    <div class="modal fade" id="addCat" tabindex="-1" aria-labelledby="addCatLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="page__subtitle mb-0">Add New Collection</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.collection.store') }}" enctype="multipart/form-data">
                            @csrf
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
                                <div class="row">
                                    <div class="col-md-6 card">
                                        <div class="card-header p-0 mb-3">Icon <span class="text-danger">*</span></div>
                                        <div class="card-body p-0">
                                            <div class="w-100 product__thumb">
                                                <label for="icon"><img id="iconOutput" src="{{ asset('backend_asset/images/placeholder-image.jpg') }}" /></label>
                                            </div>
                                            <input type="file" name="icon_path" id="icon" accept="image/*" onchange="loadIcon(event)" class="d-none">
                                            <script>
                                                let loadIcon = function(event) {
                                                    let iconOutput = document.getElementById('iconOutput');
                                                    iconOutput.src = URL.createObjectURL(event.target.files[0]);
                                                    iconOutput.onload = function() {
                                                        URL.revokeObjectURL(iconOutput.src) // free memory
                                                    }
                                                };
                                            </script>
                                        </div>
                                        <small>Image Size: 300px X 400px</small>
                                        @error('icon_path') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="col-md-6 card">
                                        <div class="card-header p-0 mb-3">Sketch Icon <span class="text-danger">*</span></div>
                                        <div class="card-body p-0">
                                            <div class="w-100 product__thumb">
                                                <label for="sketch_icon"><img id="sketchOutput" src="{{ asset('backend_asset/images/placeholder-image.jpg') }}" /></label>
                                            </div>
                                            <input type="file" name="sketch_icon" id="sketch_icon" accept="image/*" onchange="loadSketch(event)" class="d-none">
                                            <script>
                                                var loadSketch = function(event) {
                                                    var sketchOutput = document.getElementById('sketchOutput');
                                                    sketchOutput.src = URL.createObjectURL(event.target.files[0]);
                                                    sketchOutput.onload = function() {
                                                        URL.revokeObjectURL(sketchOutput.src) // free memory
                                                    }
                                                };
                                            </script>
                                        </div>
                                        <small>Image Size: 324px X 30px</small>
                                        @error('sketch_icon') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 card">
                                        <div class="card-header p-0 mb-3">Thumbnail <span class="text-danger">*</span></div>
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
                                        <small>Image Size: 512px X 512px</small>
                                        @error('image_path') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="col-md-6 card">
                                        <div class="card-header p-0 mb-3">Banner <span class="text-danger">*</span></div>
                                        <div class="card-body p-0">
                                            <div class="w-100 product__thumb">
                                                <label for="banner"><img id="bannerOutput" src="{{ asset('backend_asset/images/placeholder-image.jpg') }}" /></label>
                                            </div>
                                            <input type="file" name="banner_image" id="banner" accept="image/*" onchange="loadBanner(event)" class="d-none">
                                            <script>
                                                let loadBanner = function(event) {
                                                    let output = document.getElementById('bannerOutput');
                                                    output.src = URL.createObjectURL(event.target.files[0]);
                                                    output.onload = function() {
                                                        URL.revokeObjectURL(output.src) // free memory
                                                    }
                                                };
                                            </script>
                                        </div>
                                        <small>Image Size: 1920px X 1080px</small>
                                        @error('banner_image') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-danger">Add New Collection</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
@endsection
