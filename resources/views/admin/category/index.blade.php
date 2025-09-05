@extends('admin.layouts.app')

@section('page', 'Category')

@section('content')
<section>
    <div class="row">
        <div class="col-lg-12 text-end">
            <button type="button" class="btn btn-outline-danger btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#addCat">
                Add New
            </button>
        </div>

        <div class="col-xl-12 order-2 order-xl-1">
            <div class="search__filter mb-0">
                <div class="row align-items-center justify-content-between">
                    <div class="col">
                        <ul>
                            <li class="active"><a href="{{ route('admin.category.index') }}">All <span class="count">({{$data->count()}})</span></a></li>
                            @php
                            $activeCount = $inactiveCount = 0;
                            foreach ($data as $catKey => $catVal) {
                            if ($catVal->status == 1) $activeCount++;
                            else $inactiveCount++;
                            }
                            @endphp
                            <li><a href="{{ route('admin.category.index', ['status' => 'active'])}}">Active <span class="count">({{$activeCount}})</span></a></li>
                            <li><a href="{{ route('admin.category.index', ['status' => 'inactive'])}}">Inactive <span class="count">({{$inactiveCount}})</span></a></li>
                        </ul>
                    </div>

                    <div class="col-auto">
                        <form action="{{ route('admin.category.index') }}" method="GET">
                            <div class="row g-3 align-items-center">
                                <div class="col-auto">
                                    <input type="search" name="term" class="form-control" placeholder="Search here.." id="term" value="{{app('request')->input('term')}}" autocomplete="off">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Search Category</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <form action="{{ route('admin.category.bulkDestroy') }}">
                <div class="filter">
                    <div class="row align-items-center justify-content-between">
                        <div class="col">
                            <div class="row align-items-center">
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
                            {{-- <p>{{$data->count()}} {{ ($data->count() > 1) ? 'Items' : 'Item' }}</p> --}}
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
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
                                        <input name="delete_check[]" class="tap-to-delete" type="checkbox" onclick="clickToRemove()" value="{{$item->id}}" @php if (old('delete_check')) { if (in_array($item->id, old('delete_check'))) {
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
                                        <h6 class="text-dark">{{$item->name ? $item->name : ''}}</h6>
                                        <small>{{$item->parentCatDetails ? $item->parentCatDetails->name : ''}}</small>
                                        <div class="row__action">
                                            <a href="{{ route('admin.category.view', [$item->id, 'mode'=>'edit']) }}">Edit</a>
                                            <a href="{{ route('admin.category.view', [$item->id, 'mode'=>'view']) }}">View</a>
                                            <a href="{{ route('admin.category.status', $item->id) }}">{{($item->status == 1) ? 'Active' : 'Inactive'}}</a>
                                            {{-- <a href="{{ route('admin.category.delete', $item->id) }}" class="text-danger">Delete</a> --}}
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.category.view', $item->id) }}">{{$item->ProductDetails->count()}} products</a>
                                    </td>
                                    <td>Published<br />{{date('d M Y', strtotime($item->created_at))}}</td>
                                    <td><span class="badge bg-{{($item->status == 1) ? 'success' : 'danger'}}">{{($item->status == 1) ? 'Active' : 'Inactive'}}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="100%" class="small text-muted">No data found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="addCat" tabindex="-1" aria-labelledby="addCatLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="page__subtitle mb-0">Add New Category</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card mb-0">
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.category.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-md-6 col-xl-12">
                                        <div class="form-group mb-3">
                                            <label class="label-control">Name <span class="text-danger">*</span> </label>
                                            <input type="text" name="name" placeholder="" class="form-control" value="{{old('name')}}">
                                            @error('name') <p class="small text-danger">{{ $message }}</p> @enderror
                                        </div>
                                        {{-- <div class="form-group mb-3">
                                            <label class="label-control">Parent <span class="text-danger">*</span> </label>
                                            <input type="text" name="parent" placeholder="" class="form-control" value="{{old('parent')}}">
                                            @error('parent') <p class="small text-danger">{{ $message }}</p> @enderror
                                        </div> --}}
                                        <div class="form-group mb-3">
                                            <label class="label-control">Description </label>
                                            <textarea name="description" class="form-control">{{old('description')}}</textarea>
                                            @error('description') <p class="small text-danger">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-12">
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
                                                <small>Image Size: 230px X 282px</small>
                                                @error('icon_path') <p class="small text-danger">{{ $message }}</p> @enderror
                                            </div>
                                            <div class="col-md-6 card">
                                                <div class="card-header p-0 mb-3">Sketch icon <span class="text-danger">*</span></div>
                                                <div class="card-body p-0">
                                                    <div class="w-100 product__thumb">
                                                        <label for="sketch_icon"><img id="sketchOutput" src="{{ asset('backend_asset/images/placeholder-image.jpg') }}" /></label>
                                                    </div>
                                                    <input type="file" name="sketch_icon" id="sketch_icon" accept="image/*" onchange="loadSketch(event)" class="d-none">
                                                    <script>
                                                        let loadSketch = function(event) {
                                                            let sketchOutput = document.getElementById('sketchOutput');
                                                            sketchOutput.src = URL.createObjectURL(event.target.files[0]);
                                                            sketchOutput.onload = function() {
                                                                URL.revokeObjectURL(sketchOutput.src) // free memory
                                                            }
                                                        };
                                                    </script>
                                                </div>
                                                <small>Image Size: 100px X 100px</small>
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
                                                        let loadFile = function(event) {
                                                            let output = document.getElementById('output');
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
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-sm btn-danger">Add New Category</button>
                                    </div>
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
