@extends('front/layout.app')
@section('content')

<section class="content-image-section pt-4">
    <div class="container">
        <div class="content-holder-stack">
            <div class="row align-items-center justify-content-between">
                <div class="col-md-12 col-lg-6 order-lg-1 order-2">
                    <div class="heading-group">
                        <figure>
                            <img src="{{asset('assets/images/divider.svg')}}" alt="">
                        </figure>
                        <h2 class="section-heading">{{$page_heading}}</h2>
                        <h3 class="section-sub-heading">{{$sub_heading}}</h3>
                    </div>
                    <div class="section-content-place">
                        <p>{{$description}}</p>
                    </div>
                </div>
                <div class="col-md-12 col-lg-5 mb-4 mb-md-5 mb-lg-0 order-lg-2 order-1">
                    <div class="section-image-holder">
                        <div class="section-image-holder image-holder-medium radious-top-left">
                            <img src="{{asset('assets/images/snehamoy.jpg')}}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section-circle-image">
        <img src="{{asset('assets/images/circle-divider.svg')}}" alt="">
    </div>
</section>

@endsection