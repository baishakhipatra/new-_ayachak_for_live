@extends('layouts.app')

@section('page', 'Home')

@section('content')
<section class="storeList_panel">
    <div class="storeCatgoryListWrap">
        <div class="container">
            <ul class="storeCatgoryList">
                @foreach ($category as $categoryKey => $categoryVal)
                @php
                    if($categoryVal->ProductDetails->count() == 0) {continue;}
                @endphp
                    <li><a href="#tab{{$categoryKey+1}}">{{$categoryVal->name}}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        @foreach ($category as $categoryKeyDet => $category)
            <div class="storeProduct_list" id="tab{{$categoryKeyDet + 1}}">
                <h3>{{ $category->name }} <span>{{ $category->ProductDetails->count() }} products</span></h3>

                <div class="row mb-3">
                @forelse($category->ProductDetails as $categoryProductKey => $categoryProductValue)
                    @php if($categoryProductValue->status == 0) {continue;} @endphp
                    <div class="col-sm-6 col-md-6 col-lg-4">
                        <div class="storeProduct_card">
                            <div class="storeProduct_card_body">
                                <a href="{{ route('front.product.detail', $categoryProductValue->slug) }}" class="product__single" data-events data-cat="tshirt">
                                    <figure class="storeProduct_card_img">
                                        {{-- <img src="{{asset('img/product-box.png')}}" class="" /> --}}
                                        <img src="{{asset($category->sketch_icon)}}" class="" />
                                    </figure>
                                    <figcaption>
                                        <span class="collectionTag">{{ $categoryProductValue->collection->name }}</span>
                                        <h4>{{$categoryProductValue->name}}</h4>
                                        <h5>Style # {{$categoryProductValue->style_no}}</h5>
                                        <h6 class="mb-0">
                                        <span class="mr_price">
                                        @if (count($categoryProductValue->colorSize) > 0)
                                            @php
                                                $varArray = [];
                                                foreach($categoryProductValue->colorSize as $productVariationKey => $productVariationValue) {
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

                                                $displayPrice = $smaller.' - '.$bigger;

                                                if ($smaller == $bigger) $displayPrice = $smaller;
                                            @endphp
                                            Rs: {{$displayPrice}}
                                        @else
                                            Rs: {{$categoryProductValue->offer_price}}
                                        @endif
                                        </span>
                                        </h6>
                                    </figcaption>
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty

                @endforelse
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection
