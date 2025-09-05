@extends('layouts.app')

@section('page', 'Home')

@section('content')
<style>
.color_holder {
    height: 20px;
    width: 20px;
    border-radius: 50%
}
.product__color {
	display: flex;
    flex-wrap: wrap;
	padding: 0 20px 20px;
    align-items: center;
    justify-content: center;
}
.color-holder {
	width: 20px;
    height: 20px;
    border-radius: 50%;
    flex: 0 0 20px;
	margin-right: 7px;
	box-shadow: 0px 5px 10px rgb(0 0 0 / 10%);
}
@media(max-width: 575px) {
    .color-holder {
        width: 15px;
        height: 15px;
        flex: 0 0 15px;
    }
    .product__color {
        justify-content: center;
    }
}
</style>
<section id="home" class="home-banner p-0">
    <div class="home-banner__slider swiper-container">
        <div class="slider swiper-wrapper">
			@foreach ($banner as $item)
                <div class="home-banner__slider-single swiper-slide">
                    <div class="video__wrapper">
                        @if ($item->type == 'video')
                            <video id="onn-video" width="320" height="240" autoplay muted loop playsinline>
                                <source src="{{ asset($item->file_path) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @else
                            <img src="{{ asset($item->file_path) }}" />
                        @endif
                    </div>
                </div>
            @endforeach
            {{-- <div class="home-banner__slider-single swiper-slide">
                <div class="video__wrapper">
                    <img src="img/banner3.jpg" />
                </div>
            </div>
            <div class="home-banner__slider-single swiper-slide">
                <div class="video__wrapper">
                    <video id="onn-video" width="320" height="240" muted loop playsinline>
                        <source src="video/banner.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
            <div class="home-banner__slider-single swiper-slide">
                <div class="video__wrapper">
                    <img src="img/banner2.jpg" />
                </div>
            </div>
            <div class="home-banner__slider-single swiper-slide">
                <div class="video__wrapper">
                    <img src="img/banner1.jpg" />
                </div>
            </div> --}}
        </div>
    </div>
</section>

<section id="sale" class="home-offers">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <h4>Become <span>franchise</span> of</h4>
                <h2><span>ONN</span> exclusive store</h2>
                <!-- <p>Offer valid upto 15th may</p> -->
            </div>
            <div class="col-sm-5 offset-sm-1 text-sm-right">
                <a href="{{route('front.franchise.index')}}" class="offer-button">Learn More</a>
            </div>
        </div>
    </div>
</section>

<section id="category" class="home-category">
    <!-- <div class="home-category__text">Total Comfort</div> -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12 mb-0 mb-md-5">
                <h2><span>Featured <strong>Categories</strong></span></h2>
            </div>
            @foreach ($categories as $categoryKey => $categoryValue)
            <div class="col-4 col-sm-3 col-lg">
                <a href="{{ route('front.category.detail', $categoryValue->slug) }}" class="home-category-single">
                    <figure>
                        <img src="{{asset($categoryValue->icon_path)}}">
                    </figure>
                    <figcaption>
                        {{$categoryValue->name}}
                    </figcaption>
                </a>
            </div>
            @if($categoryKey == 4) <div class="d-none d-lg-block col-12"></div> @endif
            @endforeach
        </div>
    </div>
</section>

<section id="collection" class="home-collection pb-0">
    <div class="home-collection__before"></div>
    <div class="container">
        <!-- <div class="home-collection__text">Collections</div> -->

        <div class="row align-items-center">
            <div class="col-sm">
                <h2>Shop By <strong>Collection</strong></h2>
                <!-- <div class="home-collection__thumbs swiper-container">
                    <div class="slider swiper-wrapper">
                        @foreach($collections as $collectionKey => $collectionValue)
                            <div class="home-collection__thumbs-single swiper-slide">
                                <h2>{{$collectionValue->name}} <strong>Collection</strong></h2>
                            </div>
                        @endforeach
                    </div>
                </div> -->
            </div>
            <div class="col-auto d-none d-sm-flex">
                <button type="button" class="collection-btn collection-left">
                    <img src="img/collection-left.svg" />
                </button>
                <button type="button" class="collection-btn collection-right">
                    <img src="img/collection-right.svg" />
                </button>
            </div>
        </div>
    </div>
    <div class="container pr-0">
        <div class="row mr-0 align-items-end">
            <div class="col-sm-12 col-md-12 pr-0">
                <div class="home-collection__slider swiper-container">
                    <div class="slider swiper-wrapper">
                        @foreach($collections as $collectionKey => $collectionValue)
                            <div class="home-collection__single swiper-slide">
                                <figure class="{{$collectionValue->slug}}">
                                <!-- <a href="{{ route('front.collection.detail', $collectionValue->slug) }}"> -->
                                    <img src="{{asset($collectionValue->image_path)}}" />
                                    <figcaption>
                                        <h3>{{$collectionValue->name}}<br><strong>Collection</strong></h3>
                                        <p>View All Products</p>
                                    </figcaption>
                                <!-- </a> -->
                                </figure>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="tranding" class="home-product">
    <div class="container">
        <div class="home-product__text">Products</div>
        <div class="row align-items-center mb-2 mb-sm-4">
            <div class="col-sm">
                <h2 class="">Trending <strong>Products</strong></h2>
                <!-- <div class="home-collection__thumbs swiper-container">
                    <div class="slider swiper-wrapper">
                        @foreach($collections as $collectionKey => $collectionValue)
                            <div class="home-collection__thumbs-single swiper-slide">
                                <h2>{{$collectionValue->name}} <strong>Collection</strong></h2>
                            </div>
                        @endforeach
                    </div>
                </div> -->
            </div>
            <div class="col-auto d-none d-sm-flex">
                <button type="button" class="collection-btn product-collection-left">
                    <img src="img/collection-left.svg" />
                </button>
                <button type="button" class="collection-btn product-collection-right">
                    <img src="img/collection-right.svg" />
                </button>
            </div>
        </div>
    </div>
    <div class="container pr-0">
        <div class="home-product__holder">
            <!-- <div class="home-product__holder__left">
                <h2 class="home-product__holder__title">Trending <strong>Products</strong></h2>
            </div> -->
    
            <div class="home-product__holder__right">
                <div class="home-product__slider swiper-container">
                    <div class="slider swiper-wrapper">
                        @foreach ($products as $productKey => $productValue)
                            <a href="{{ route('front.product.detail', $productValue->slug) }}" class="home-product__single swiper-slide">
                                <figure>
                                    <img src="{{asset($productValue->image)}}" />
                                </figure>
                                <figcaption>
                                    <h4>{{$productValue->name}}</h4>
                                    <h6>Style # OF {{$productValue->style_no}}</h6>
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

                                    /* $displayPrice = $smaller.' - '.$bigger;

                                    if ($smaller == $bigger) $displayPrice = $smaller; */
                                @endphp
                                {{-- &#8377;{{$displayPrice}} --}}
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

                            {{-- <div class="color">
                                @if (count($productValue->colorSize) > 0)
                                @php
                                $uniqueColors = [];

                                foreach ($productValue->colorSize as $variantKey => $variantValue) {
                                    if (in_array_r($variantValue->colorDetails->code, $uniqueColors)) continue;

                                    $uniqueColors[] = [
                                        'id' => $variantValue->colorDetails->id,
                                        'code' => $variantValue->colorDetails->code,
                                        'name' => $variantValue->colorDetails->name,
                                    ];
                                }

                                echo '<ul class="product__color">';
                                // echo count($uniqueColors);
                                foreach($uniqueColors as $colorCodeKey => $colorCode) {
                                    if ($colorCodeKey == 5) {break;}
                                    // if ($colorCodeKey < 5) {
                                        if ($colorCode['id'] == 61) {
                                            echo '<li style="background: -webkit-linear-gradient(left,  rgba(219,2,2,1) 0%,rgba(219,2,2,1) 9%,rgba(219,2,2,1) 10%,rgba(254,191,1,1) 10%,rgba(254,191,1,1) 10%,rgba(254,191,1,1) 20%,rgba(1,52,170,1) 20%,rgba(1,52,170,1) 20%,rgba(1,52,170,1) 30%,rgba(15,0,13,1) 30%,rgba(15,0,13,1) 30%,rgba(15,0,13,1) 40%,rgba(239,77,2,1) 40%,rgba(239,77,2,1) 40%,rgba(239,77,2,1) 50%,rgba(254,191,1,1) 50%,rgba(137,137,137,1) 50%,rgba(137,137,137,1) 60%,rgba(254,191,1,1) 60%,rgba(254,191,1,1) 60%,rgba(254,191,1,1) 70%,rgba(189,232,2,1) 70%,rgba(189,232,2,1) 80%,rgba(209,2,160,1) 80%,rgba(209,2,160,1) 90%,rgba(48,45,0,1) 90%); " class="color-holder" data-bs-toggle="tooltip" data-bs-placement="top" title="Assorted"></li>';
                                        } else {
                                            echo '<li onclick="sizeCheck('.$productValue->id.', '.$colorCode['id'].')" style="background-color: '.$colorCode['code'].'" class="color-holder" data-bs-toggle="tooltip" data-bs-placement="top" title="'.$colorCode['name'].'"></li>';
                                        }
                                    // }
                                }
                                if (count($uniqueColors) > 5) {echo '<li>+ more</li>';}
                                echo '</ul>';
                                @endphp
                                @endif
                            </div> --}}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@foreach ($categoryNavList as $categoryNavKey => $categoryNavValue)
@php 
    if($categoryNavValue['parent'] == 'Innerwear') {
@endphp
<section class="home-shop">
    <div class="container">
        <h2>Shop <strong>{{$categoryNavValue['parent']}}</strong></h2>
        <div class="row">
            @foreach ($categoryNavValue['child'] as $childCatKey => $childCatValue)
            <div class="col-12 col-sm-6 col-md-4 mb-3 mb-sm-0"> 
                <a href="{{ route('front.category.detail', $childCatValue['slug']) }}" class="home-shop_thumb">
                    <figure>
                        <img src="{{ asset($childCatValue['image_path']) }}" class="img-fluid">
                    </figure>
                    <h4>{{$childCatValue['name']}}</h4>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@php 
    } elseif($categoryNavValue['parent'] == 'Outerwear') {
@endphp
<section class="home-shop">
    <div class="container">
        <h2>Shop <strong>{{$categoryNavValue['parent']}}</strong></h2>
        <div class="row">
            @foreach ($categoryNavValue['child'] as $childCatKey => $childCatValue)
            <div class="col-12 col-sm-6 col-md-4 mb-3 mb-sm-0"> 
                <a href="{{ route('front.category.detail', $childCatValue['slug']) }}" class="home-shop_thumb">
                    <figure>
                        <img src="{{ asset($childCatValue['image_path']) }}" class="img-fluid">
                    </figure>
                    <h4>{{$childCatValue['name']}}</h4>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@php 
    } elseif($categoryNavValue['parent'] == 'Footkins') {
@endphp
    <section class="home-shop">
        <div class="container">
            <div class="row">
                
                <div class="col-sm-3">
                    <h2>Shop <strong>{{$categoryNavValue['parent']}}</strong></h2>
                    <div class="row">
                        @foreach ($categoryNavValue['child'] as $childCatKey => $childCatValue)
                        <div class="col-12 mb-3 mb-sm-0"> 
                            <a href="{{ route('front.category.detail', $childCatValue['slug']) }}" class="home-shop_thumb">
                                <figure>
                                    <img src="{{ asset($childCatValue['image_path']) }}" class="img-fluid">
                                </figure>
                                <h4>{{$childCatValue['name']}}</h4>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
@php
    } else {
@endphp 
<div class="col-sm-9">
                    <h2>Shop <strong>{{$categoryNavValue['parent']}}</strong></h2>
                    <div class="row">
                        @foreach ($categoryNavValue['child'] as $childCatKey => $childCatValue)
                        <div class="col-12 col-sm-4 mb-3 mb-sm-0"> 
                            <a href="{{ route('front.category.detail', $childCatValue['slug']) }}" class="home-shop_thumb">
                                <figure>
                                    <img src="{{ asset($childCatValue['image_path']) }}" class="img-fluid">
                                </figure>
                                <h4>{{$childCatValue['name']}}</h4>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@php
    }
@endphp 

@endforeach

<section class="home-sale">
    <div class="home-sale__slider swiper-container">
        <div class="slider swiper-wrapper">
            @foreach ($galleries as $galleryKey => $galleryValue)
                <div class="home-sale__single swiper-slide">
                    <figure>
                        <img src="{{asset($galleryValue->image)}}" />
                    </figure>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
