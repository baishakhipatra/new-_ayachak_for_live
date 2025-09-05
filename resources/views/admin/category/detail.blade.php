@extends('admin.layouts.app')

@section('page', 'Category detail')

@section('content')
<section>
    <div class="row">
        <div class="col-sm-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>{{ $data->name }}</h3>
                            <p class="text-muted">{{ $data->parentCatDetails ? $data->parentCatDetails->name : '' }}</p>
                            <p class="small">{{ $data->description }}</p>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <p class="text-muted">Icon</p>
                            <img src="{{ asset($data->icon_path) }}" alt="" style="height: 50px">
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted">Sketch</p>
                            <img src="{{ asset($data->sketch_icon) }}" alt="" style="height: 50px">
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted">Thumbnail</p>
                            <img src="{{ asset($data->image_path) }}" alt="" style="height: 50px">
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted">Banner</p>
                            <img src="{{ asset($data->banner_image) }}" alt="" style="height: 50px">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="text-muted">Products</h3>
                            <p>{{$data->ProductDetails->count()}} products total</p>

                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="text-center"><i class="fi fi-br-picture"></i></th>
                                    <th>Name</th>
                                    <th>Style No.</th>
                                    <th>Collection</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data->ProductDetails as $index => $item)
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
                                        <td class="text-center column-thumb">
                                            <img src="{{ asset($item->image) }}">
                                        </td>
                                        <td>
                                            {{$item->name}}
                                            <div class="row__action">
                                                <a href="{{ route('admin.product.edit', $item->id) }}">Edit</a>
                                                <a href="{{ route('admin.product.view', $item->id) }}">View</a>
                                            </div>
                                        </td>
                                        <td>{{$item->style_no}}</td>
                                        <td>{{$item->collection ? $item->collection->name : ''}}</td>
                                        <td>
                                            <small> <del>{{$item->price}}</del> </small> Rs. {{$item->offer_price}}
                                        </td>
                                        <td><span class="badge bg-{{($item->status == 1) ? 'success' : 'danger'}}">{{($item->status == 1) ? 'Active' : 'Inactive'}}</span></td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="100%" class="small text-muted">No data found</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.category.update', $data->id) }}" enctype="multipart/form-data">
                    @csrf
                        <h4 class="page__subtitle">Edit Category</h4>
                        <div class="form-group mb-3">
                            <label class="label-control">Name <span class="text-danger">*</span> </label>
                            <input type="text" name="name" placeholder="" class="form-control" value="{{ $data->name }}">
                            @error('name') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        {{-- <div class="form-group mb-3">
                            <label class="label-control">Parent <span class="text-danger">*</span> </label>
                            <input type="text" name="parent" placeholder="" class="form-control" value="{{ $data->parent }}">
                            @error('parent') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div> --}}
                        <div class="form-group mb-3">
                            <label class="label-control">Description </label>
                            <textarea name="description" class="form-control">{{$data->description}}</textarea>
                            @error('description') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 card">
                                <div class="card-header p-0 mb-3">Icon <span class="text-danger">*</span></div>
                                <div class="card-body p-0">
                                    <div class="w-100 product__thumb">
                                        <label for="icon"><img id="iconOutput" src="{{ asset($data->icon_path) }}" /></label>
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
                                        <label for="sketch_icon"><img id="sketchOutput" src="{{ asset($data->sketch_icon) }}" /></label>
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
                                <small>Image Size: 100px X 100px</small>
                                @error('sketch_icon') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 card">
                                <div class="card-header p-0 mb-3">Image <span class="text-danger">*</span></div>
                                <div class="card-body p-0">
                                    <div class="w-100 product__thumb">
                                        <label for="thumbnail"><img id="output" src="{{ asset($data->image_path) }}" /></label>
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
                                <div class="card-header p-0 mb-3">Banner Image <span class="text-danger">*</span></div>
                                <div class="card-body p-0">
                                    <div class="w-100 product__thumb">
                                        <label for="banner"><img id="bannerOutput" src="{{ asset($data->banner_image) }}" /></label>
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
                        @if(request('mode') == 'edit')
                            <div class="form-group">
                                <button type="submit" class="btn btn-sm btn-danger">Update Category</button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
