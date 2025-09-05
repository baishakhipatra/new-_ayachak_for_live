@extends('layouts.app')

@section('page', app('request')->input('query'))

@section('content')
@if (count($data) > 0)
    <section class="listing-block mt-5 pt-5">
        <div class="container">
            <div class="listing-block__meta">
                <p>Displaying search result for <b>{{app('request')->input('query')}}</b></p>
            </div>

            <div class="product__wrapper">
                <div class="product__holder">
                    <div class="row">
                        @foreach($data as $productKey => $productValue)
						 @php if($productValue->status == 0) {continue;} @endphp
                        <a href="{{ route('front.product.detail', $productValue->slug) }}" class="product__single" data-events data-cat="tshirt">
                            <figure>
                                <img src="{{asset($productValue->image)}}" />
                                <h6 class="d-block">{{$productValue->style_no}}</h6>
                            </figure>
                            <figcaption>
                                <h4>{{$productValue->name}}</h4>
                                <h5>
                                @if (count($productValue->colorSize) > 0)
                                    @php
                                        $varArray = [];
                                        foreach($productValue->colorSize as $productVariationKey => $productVariationValue) {
                                            $varArray[] = $productVariationValue->offer_price;
                                        }
                                        $bigger = $varArray[0];
                                        for ($i = 1; $i < count($varArray); $i++) {
                                            if ($bigger < $varArray[$i]) {
                                                $bigger = $varArray[$i];
                                            }
                                        }

                                        $smaller = $varArray[0];
                                        for ($i = 1; $i < count($varArray); $i++) {
                                            if ($smaller > $varArray[$i]) {
                                                $smaller = $varArray[$i];
                                            }
                                        }
                                    @endphp

                                    @if($smaller == $bigger)
                                        &#8377;{{$bigger}}
                                    @else
                                        &#8377;{{$smaller}} - &#8377;{{$bigger}}
                                    @endif
                                @else
                                    &#8377;{{$productValue->offer_price}}
                                @endif
                                </h5>
                            </figcaption>

                            {!! variationColors($productValue->id, 5) !!}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@else
    <section class="cart-header mb-3 mb-sm-5"></section>
    <section class="cart-wrapper">
        <div class="container">
            <div class="complele-box">
                <figure>
                    <img src="{{asset('img/close.svg')}}" height="100">
                </figure>
                <figcaption>
                    <h2>OOPS ! <b>{{app('request')->input('query')}}</b> returns no result</h2>
                    <p>Search new query.</p>
                    <a href="{{route('front.home')}}">Back to Home</a>
                </figcaption>
            </div>
        </div>
    </section>
@endif
@endsection
