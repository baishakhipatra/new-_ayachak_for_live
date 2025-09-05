@extends('admin.layouts.app')

@section('page', 'Image')

@section('content')
<section>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        @if (count($data) > 0)
                            @forelse ( $data as $item )
                                <div class="col-2 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body" style="display: contents;">
                                            <img src="{{ asset('/uploads/store_images/' . $item->images) }}" width="200px"
                                                height="250px" class="img-thumbnail">
                                        </div>
                                        <div class="position-absolute" style="right: 0">
                                            <a class="btn btn-sm btn-danger rounded-btn"
                                                onclick="return confirm('Are you sure to delete?')"
                                                href="{{ route('admin.retailer.image.delete', [$item->id,$item->images]) }}">
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
                    {{-- <div class="row">
                        @if (count($data) > 0)
                            @forelse (explode(',',$data[0]->images) as $item)
                                <div class="col m-2">
                                    <img src="{{ asset('/uploads/store_images/' . $item) }}" width="200px" height="250px"
                                        class="border-dark">
                                    <a class="text-danger" onclick="return confirm('Are you sure to delete?')"
                                        href="{{ route('front.store.image.delete', $item) }}">Delete</a>
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
                    </div> --}}
                </div>
                </div>
            </div>
        </div>


    </div>
</section>
@endsection
