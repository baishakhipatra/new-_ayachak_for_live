@extends('front/layout.app')
@section('content')

<section class="content-image-section pb-4">
    <div class="container">
        <div class="content-holder-stack">
            <div class="row align-items-center justify-content-between">
                <div class="col-md-12 col-lg-6 md-lg-6 order-lg-1 order-2">
                    {{-- <div class="heading-group">
                        <figure>
                            <img src="{{asset('assets/images/divider.svg')}}" alt="">
                        </figure>
                        <h2 class="section-heading">Uniqueness of Principles of Sri Sri Babamoni</h2>
                    </div> --}}
                    <ul class="content-list">
                        <li>
                            <figure>
                                <img src="{{asset('assets/images/icon.svg')}}" alt="">
                            </figure>
                            <figcaption>
                                <h4>{{$page_heading}}</h4>
                                <p>{{$description}}</p>
                            </figcaption>
                        </li>
                    </ul>
                </div>
                <div class="col-md-12 col-lg-5 mb-4 mb-md-5 mb-lg-0 order-lg-2 order-1">
                    <div class="section-image-holder">
                        <div class="section-image-holder image-holder-medium radious-bottom-left">
                            <img src="{{asset('assets/images/dummy-image.jpg')}}" alt="">
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