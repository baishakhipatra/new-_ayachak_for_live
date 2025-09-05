@extends('layouts.app')

@section('page', 'Offer')

@section('content')
<section class="cart-header mb-3 mb-sm-5"></section>
<section class="cart-wrapper">
    <div class="container">
        <div class="complele-box">
            <figure>
                <img src="{{asset('img/shopping-basket.gif')}}" height="100">
            </figure>
            <figcaption>
                <h2>STAY TUNED</h2>
                <p>For upcoming offers</p>
                <a href="{{route('front.home')}}">Explore More</a>
            </figcaption>
        </div>
    </div>
</section>
@endsection