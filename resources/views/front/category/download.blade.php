@extends('layouts.app')

@section('page', 'Offer')

@section('content')
<div class="col-sm-12">
    <div class="profile-card">
        <h3>Catalogue</h3>

        <section class="store_listing">
            <div class="container">
                <div class="row">
                @forelse($data as $catalougeKey => $catalougeValue)
                    <div class="col-4 col-sm-3 col-lg-2">
                        <a href="{{asset($catalougeValue->pdf)}}" class="product__cat__single" download>
                            <figure>
                                <img src="{{asset($catalougeValue->image)}}" alt="">
                            </figure>
                            <h5>{{$catalougeValue->title}}</h5>
                        </a>
                    </div>
                    @empty
                @endforelse
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
