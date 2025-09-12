@extends('front/layout.app')
@section('content')

<section class="image-content-section ">
    <div class="container">
        <div class="content-holder-stack">
            <div class="row align-items-center justify-content-between">
                <div class="col-md-12">
                    <ul class="content-list">
                        <li>
                            <figure>
                                <img src="{{asset('assets/images/icon.svg')}}" alt="">
                            </figure>
                            <figcaption>
                                <h4>{{$page_heading}}</h4>
                                <p>{!!$description!!}</p>
                            </figcaption>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="section-circle-image">
        <img src="{{asset('assets/images/circle-divider.svg')}}" alt="">
    </div>
</section>

@endsection