@extends('admin.layouts.app')

@section('page', 'Banner')

@section('content')
<style>
    .file-holder img {
        height: 100px
    }
</style>

<section>
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#SR</th>
                                <th>Title</th>
                                <th>Sub title</th>
                                <th>Description</th>
                                <th>Banner Videos</th>
                                <th>Banner Image</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->title }}
                                    <div class="row__action">
                                        <a href="{{ route('admin.banner.view', $item->id) }}">Edit</a>
                                        <a href="{{ route('admin.banner.view', $item->id) }}">View</a>
                                        <a href="javascript:void(0)" onclick="deleteEvent({{ $item->id }})" class="text-danger">Delete</a>
                                </td>
                                <td>{{ $item->sub_title }}</td>
                                <td>{{ $item->description }}</td>

                                {{-- Banner Videos --}}
                                <td>
                                    @if ($item->banner_videos)
                                        <video height="100" muted loop controls playsinline>
                                            <source src="{{ asset($item->banner_videos) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    @else
                                        <span class="text-muted">No Video</span>
                                    @endif
                                </td>

                                {{-- Banner Image --}}
                                <td>
                                    @if ($item->banner_image)
                                        <img src="{{ asset($item->banner_image) }}" height="80" />
                                    @else
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="small text-muted">No data found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.banner.store') }}" enctype="multipart/form-data">
                        @csrf
                        <h4 class="page__subtitle">Add New Banner</h4>

                        {{-- Title --}}
                        <div class="mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                            @error('title') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>

                        {{-- Sub Title --}}
                        <div class="mb-3">
                            <label class="form-label">Sub Title</label>
                            <input type="text" name="sub_title" class="form-control" value="{{ old('sub_title') }}">
                            @error('sub_title') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
                            @error('description') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>

                        {{-- Banner Video --}}
                        <div class="mb-3 card">
                            <div class="card-header p-0 mb-2">Banner Video</div>
                            <div class="card-body p-0">
                                <input type="file" name="banner_videos" accept="video/*" class="form-control">
                                <p class="small text-muted mt-1">Upload MP4 video</p>
                            </div>
                            @error('banner_videos') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>

                        {{-- Banner Image --}}
                        <div class="mb-3 card">
                            <div class="card-header p-0 mb-2">Banner Image <span class="text-danger">*</span></div>
                            <div class="card-body p-0">
                                <div class="w-100 product__thumb mb-2">
                                    <label for="banner_image"><img id="bannerImagePreview" src="{{ asset('backend_asset/images/placeholder-image.jpg') }}" height="120"/></label>
                                </div>
                                <input type="file" name="banner_image" id="banner_image" accept="image/*" onchange="previewBannerImage(event)" class="form-control d-none">
                                <p class="small text-muted">Click above to browse image</p>
                                <script>
                                    let previewBannerImage = function(event) {
                                        let bannerImagePreview = document.getElementById('bannerImagePreview');
                                        bannerImagePreview.src = URL.createObjectURL(event.target.files[0]);
                                        bannerImagePreview.onload = function() {
                                            URL.revokeObjectURL(bannerImagePreview.src); // free memory
                                        }
                                    };
                                </script>
                            </div>
                            @error('banner_image') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-sm btn-danger">Add Banner</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

@section('script')
<script>
    function deleteEvent(id) {
        Swal.fire({
            icon: 'warning',
            title: "Are you sure you want to delete this?",
            text: "You won't be able to revert this!",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Delete",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('admin.banner.delete') }}",
                    type: 'POST',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (data) {
                        if (data.status !== 200) {
                            toastFire('error', data.message);
                        } else {
                            toastFire('success', data.message);
                            location.reload();
                        }
                    }
                });
            }
        });
    }
</script>
@endsection