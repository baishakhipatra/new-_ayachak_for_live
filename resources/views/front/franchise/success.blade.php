@extends('layouts.app')

@section('page', 'Franchise partner thank you')

@section('content')
<section class="cart-header mb-3 mb-sm-5"></section>
<section class="cart-wrapper">
    <div class="container">
        <div class="complele-box">
            <figure>
                <img src="{{asset('img/positive-vote.png')}}" height="100">
            </figure>
            <figcaption>
                <h2>We Thankyou for your enquiry.</h2>
                <p>Our Team will connect with you soon.</p>
                <a href="{{route('front.franchise.index')}}">Back to Franchise</a>
            </figcaption>
        </div>
    </div>
</section>
@endsection

@section('script')
    <script>
        setTimeout(() => {
            window.location = '{{route("front.franchise.index")}}';
        }, 5000);
    </script>
@endsection