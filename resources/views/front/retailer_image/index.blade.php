@extends('layouts.app')

@section('content')
<div class="col-sm-12">
    <div class="profile-card">
        <h3>Store Image</h3>

        <form action="{{ route('front.store.image.add') }}" id="img_frm" method="POST" enctype="multipart/form-data" class="mb-3">@csrf
            <label for="upload_image" class="btn btn-danger">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload-cloud mr-2"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/><polyline points="16 16 12 12 8 16"/></svg>
                Browse & Upload
            </label>
            <input type="file" name="images[]" multiple accept="image/jpeg, image/png" id="upload_image" style="display: none;" onchange="$('#img_frm').submit()">
        </form>

        <div class="row">
            @if (count($data) > 0)
                @forelse (explode(',',$data[0]->images) as $item)
                    <div class="col-2 mb-3">
                        <div class="card h-100">
                            <div class="card-body" style="display: contents;">
                                <img src="{{ asset('/uploads/store_images/' . $item) }}" width="200px"
                                    height="250px" class="img-thumbnail">
                            </div>
                            <div class="position-absolute" style="right: 0">
                                <a class="btn btn-sm btn-danger rounded-btn"
                                    onclick="return confirm('Are you sure to delete?')"
                                    href="{{ route('front.store.image.delete', $item) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                        <line x1="18" y1="6" x2="6" y2="18" />
                                        <line x1="6" y1="6" x2="18" y2="18" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col">
                        <div class="text-muted">No Images uploaded yet</div>
                    </div>
                @endforelse
            @else
                <div class="col">
                    <div class="text-muted">No Images uploaded yet</div>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
