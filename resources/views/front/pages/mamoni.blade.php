@extends('front/layout.app')
@section('content')

<section class="guru-section mamoni-section">
    <div class="container">
       <div class="content-holder-stack">
            <div class="row align-items-center justify-content-between">
                <div class="col-md-12 col-lg-6 order-lg-1 order-2">
                    <div class="heading-group">
                        <figure>
                            <img src="{{asset('assets/images/divider.svg')}}" alt="">
                        </figure>
                        <h2 class="section-heading">{{ ucwords(str_replace('-', ' ', $page_heading)) }}</h2>
                        <h3 class="section-sub-heading">{{ucwords(str_replace('-', ' ',$sub_heading))}}</h3>
                    </div>
                    <div class="section-content-place">
                        <p>{{$description}}</p>
                    </div>
                </div>
                <div class="col-md-12 col-lg-5 mb-4 mb-md-5 mb-lg-0 order-lg-2 order-1">
                    <div class="section-image-holder">
                        <div class="section-image-holder image-holder-big radious-bottom-left">
                            <img src="{{asset('assets/images/mamoni.jpg')}}" alt="">
                        </div>
                        <div class="image-group">
                            <img src="{{asset('assets/images/double-flower.png')}}" alt="">
                        </div>
                    </div>
                </div>
            </div>
       </div>
    </div>
    <div class="section-overlay"></div>
</section>

@endsection