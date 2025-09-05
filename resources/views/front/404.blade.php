@extends('layouts.app')

@section('page', 'Order Complete')

@section('content')
<section class="cart-header mb-3 mb-sm-5"></section>
<section class="cart-wrapper">
    <div class="container">
        <div class="complele-box">
            <figure>
                <img src="{{asset('img/close.svg')}}" height="100">
            </figure>
            <figcaption>
                <h2>Looks like you are lost</h2>
                <p>You can stay here or get back to home.</p>
                <a href="{{route('front.home')}}">Back to Home</a>
            </figcaption>
        </div>
    </div>
</section>
@endsection