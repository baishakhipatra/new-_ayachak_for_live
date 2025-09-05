@extends('admin.layouts.app')

@section('page', 'Banner Detail')

@section('content')
<section>
    <div class="row">
        <!-- Preview Section -->
        <div class="col-sm-8">
            <div class="card">    
                <div class="card-body">
                    <h4 class="page__subtitle">Current Banner Preview</h4>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            @if ($data->banner_videos)
                                <video style="width: 100%" autoplay muted loop controls playsinline>
                                    <source src="{{ asset($data->banner_videos) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @elseif ($data->banner_image)
                                <img src="{{ asset($data->banner_image) }}" class="w-100"/>
                            @else
                                <p class="text-muted">No banner uploaded yet.</p>
                            @endif
                        </div>
                    </div>  

                    <h5>Title: </h5>
                    <p>{{ $data->title }}</p>

                    <h5>Sub Title: </h5>
                    <p>{{ $data->sub_title }}</p>

                    <h5>Description: </h5>
                    <p>{{ $data->description }}</p>

                </div>
            </div>
        </div>

        <!-- Edit Section -->
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.banner.update', $data->id) }}" enctype="multipart/form-data">
                        @csrf

                        <h4 class="page__subtitle">Edit Banner</h4>

                        <div class="mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $data->title) }}">
                            @error('title') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sub Title</label>
                            <input type="text" name="sub_title" class="form-control" value="{{ old('sub_title', $data->sub_title) }}">
                            @error('sub_title') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="3" class="form-control">{{ old('description', $data->description) }}</textarea>
                            @error('description') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Banner Image (Desktop/Mobile)</label>
                            <input type="file" name="banner_image" accept="image/*" class="form-control">
                            <small class="text-muted">Leave blank if you don’t want to change</small>
                            @error('banner_image') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Banner Video</label>
                            <input type="file" name="banner_videos" accept="video/*" class="form-control">
                            <small class="text-muted">Leave blank if you don’t want to change</small>
                            @error('banner_videos') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-sm btn-danger">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection