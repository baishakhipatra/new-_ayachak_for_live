@extends('layouts.app')

@section('page', 'Category')

@section('content')
<section class="store_listing">
    <div class="container">
        <div class="row">
        @forelse($data as $categoryKey => $categoryValue)
            <div class="col-4 col-sm-3 col-lg-2">
                <a href="{{ route('front.catalouge.detail', [$categoryValue->slug]) }}" class="product__cat__single">
                    <figure>
                        <img src="{{asset($categoryValue->icon_path)}}" alt="">
                    </figure>
                    <h5>{{$categoryValue->name}}</h5>
                </a>
            </div>
            @empty
        @endforelse
        </div>
    </div>
</section>
@endsection
