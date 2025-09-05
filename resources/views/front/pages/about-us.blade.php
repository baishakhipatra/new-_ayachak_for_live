@extends('front/layout.app')
@section('content')

<section class="about-section">
    <div class="container">
        <div class="heading-group">
            <figure>
                <img src="./assets/images/divider.svg" alt="">
            </figure>
            <h3 class="section-sub-heading">Introduction</h3>
            <h2 class="section-heading">{{ ucwords(str_replace('-', ' ', $page_heading)) }}</h2>
        </div>
        <p>{{$description}}</p>
    </div>
</section>

@endsection