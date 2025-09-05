@extends('layouts.app')

@section('page', 'Promotion success')

@section('content')
<section class="cart-header mb-3 mb-sm-5"></section>
<section class="cart-wrapper">
    <div class="container">
        <div class="complele-box">
            <figure>
                <img src="{{asset('img/positive-vote.png')}}" height="100">
            </figure>
            <figcaption>
                <h2>We Thank you for your information.</h2>
                <p>Please check your email address for the latest generated coupon.</p>
                <a href="{{url('/')}}">Back to Home</a>
            </figcaption>
        </div>
    </div>
</section>
@endsection

@section('script')
    <script>
        setTimeout(() => {
            window.location = '{{url("/")}}';
        }, 5000);
    </script>
@endsection