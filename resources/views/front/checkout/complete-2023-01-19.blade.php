@extends('layouts.app')

@section('page', 'Order Complete')

@section('content')

@if(Session::get('success'))
    <section class="cart-header mb-3 mb-sm-5"></section>
    <section class="cart-wrapper">
        <div class="container">
            <div class="complele-box">
                <figure>
                    <img src="{{asset('img/box.png')}}">
                </figure>
                <figcaption>
                    <h2>Your order is complete</h2>
                    <p>{{Session::get('success')}}</p>
                    <p>You will receive an email confirmation shortly.</p>
                    <a href="{{route('front.user.order')}}">View all orders</a>
                </figcaption>
            </div>
        </div>
    </section>
@else
    <script>window.location = "{{route('front.home')}}";</script>
@endif

@endsection