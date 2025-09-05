@php
// Illuminate\Support\Facades\DB::table('products')->where('id', $data->id)->update(['view_count' => +1]);
App\Models\Product::where('id', $data->id)->increment('view_count', 1, ['last_view_count_updated_at' => \Carbon\Carbon::now()]);
@endphp

@extends('layouts.app')

@section('page', 'Product detail')

@section('content')
<style>
.product-details__content__holder .product__enquire form button {
    display: -webkit-inline-box;
    display: -ms-inline-flexbox;
    display: inline-flex;
    vertical-align: top;
    width: 200px;
    height: 60px;
    background: #141b4b;
    color: #fff;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    font-size: 16px;
    font-weight: 500;
    border: transparent;
}
.product-details__content__holder .product__enquire form button:hover {
    box-shadow: inset 264px 0px 0px 0px #c10909;
    border-color: #c10909;
    color: #fff;
}
.product-details__content__holder .product__color li {
    width: 36px;
    height: 36px;
    padding: 2px 8px;
    border-radius: 50%;
}
.product-details__content__holder .product__color li.active {
    border-width: 2px;
    border-color: #000000;
    color: #fff;
}
.product-details__gallery__thumb {
    position: relative;
}
.thumb_button {
    width: 100%;
    height: 24px;
    padding: 0;
    background: rgba(255,255,255, 0.5);
    border: none;
    text-align: center;
}
.top_button {
    position: absolute;
    top: 0;
    left: 0;
    z-index: 9;
}
.buttom_button {
    position: absolute;
    bottom: 0;
    left: 0;
    z-index: 9;
}
.color_holder {
    height: 20px;
    width: 20px;
    border-radius: 50%
}
.product__color {
	display: flex;
    flex-wrap: wrap;
    align-items: center;
	padding: 0 20px 20px;
}
.color-holder {
	width: 20px;
    height: 20px;
    border-radius: 50%;
    flex: 0 0 20px;
	margin-right: 7px;
	box-shadow: 0px 5px 10px rgb(0 0 0 / 10%);
}
.product-details__content__holder .n_code {
    display: -webkit-inline-box;
    margin-bottom: 30px;
}
.home-gallary__single h6 {
    display: block;
}
.product_meta {
    padding: 0;
    display: flex;
    align-items: center;
}
.product_meta li {
    display: inline-block;
    font-weight: 400;
    margin-bottom: 15px;
    border-right: 1px solid #ddd;
    padding-right: 15px;
    margin-right: 15px;
}
.product_meta li:last-child {
    margin-right: 0;
    padding-right: 0;
    border: none;
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
    /*.product_meta {
        flex-wrap: wrap;
    }*/
    .product_meta li {
        max-width: 50%;
        flex: 0 0 50%;
    }
}
</style>

<section id="specifications" class="product-details">
    <div class="product-details__gallery">
        <div class="product-details__gallery__holder">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('front.category.detail', $data->category->slug) }}">{{$data->category->name}}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{$data->name}}</li>
                </ol>
            </nav>
            <div class="w-100">
                <div class="product-details__gallery__thumb swiper-container">
                    <button type="button" class="thumb_button top_button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-up"><polyline points="18 15 12 9 6 15"></polyline></svg>
                    </button>
                    <div class="slider swiper-wrapper">
                        {{-- <div class="product-details__gallery__thumb-single swiper-slide">
                            <img src="{{ asset($data->image) }}" />
                        </div> --}}

						@php
							$color = \App\Models\ProductColorSize::where('product_id', $data->id)->first();
                            if ($color) {
                                $lazyImages = \App\Models\ProductImage::where('product_id', $data->id)->where('color_id', $color->color)->get();
                            }
						@endphp

						@if($color)
                            @if(count($lazyImages) == 0)
                                <div class="product-details__gallery__thumb-single swiper-slide">
                                    <img src="{{ asset($data->image) }}" />
                                </div>
                            @else
                                @foreach($lazyImages as $singleImage)
                                    <div class="product-details__gallery__thumb-single swiper-slide">
                                        <img src="{{ asset($singleImage->image) }}" />
                                    </div>
                                @endforeach
                            @endif
                        @else
                        <div class="product-details__gallery__thumb-single swiper-slide">
                            <img src="{{ asset($data->image) }}" />
                        </div>
						@endif

                    </div>
                    <button type="button" class="thumb_button buttom_button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                </div>
                <div class="product-details__gallery__slider swiper-container">
                    <div class="slider swiper-wrapper">
                        {{-- <div class="product-details__gallery__slider-single swiper-slide">
                            <img src="{{ asset($data->image) }}" />
                        </div> --}}

                        @if($color)
                            @if(count($lazyImages) == 0)
                                <div class="product-details__gallery__slider-single swiper-slide">
                                    <img src="{{ asset($data->image) }}" />
                                </div>
                            @else
                                @foreach($lazyImages as $singleImage)
                                    <div class="product-details__gallery__slider-single swiper-slide">
                                        <img src="{{ asset($singleImage->image) }}" />
                                    </div>
                                @endforeach
                            @endif
                        @else
                        <div class="product-details__gallery__slider-single swiper-slide">
                            <img src="{{ asset($data->image) }}" />
                        </div>
						@endif

                        @if($color)
                            @foreach($lazyImages as $singleImage)
                                <div class="product-details__gallery__slider-single swiper-slide">
                                    <img src="{{ asset($singleImage->image) }}" />
                                </div>
                            @endforeach
						@endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="product-details__content">
        <div class="product-details__content__holder">
            {{-- @if (Session::get('success'))
                <div class="alert alert-success"> {{Session::get('success')}} </div>
            @endif

            @if (Session::get('failure'))
                <div class="alert alert-danger"> {{Session::get('failure')}} </div>
            @endif --}}

            <span class="n_code"># {{$data->style_no}}</span>
            {{-- <img src="{{ asset('img/logo_outerwear.png') }}" class="brand__logo"> --}}
			<!-- <p>{{$data->category->name}}</p>
			<p>{{$data->only_for}}</p> -->
            <h2>{{$data->name}}</h2>

            <ul class="product_meta">
                <!-- <li><strong>Style No:</strong> # {{$data->style_no}}</li> -->
                <li><strong>Collections: </strong> {{$data->collection->name}} </li>
                <li><strong>Net Qty:</strong> {{ $data->pack }}</li>
            </ul>

            <div class="product__pricing">
                <img src="{{ asset('img/wallet.png') }}">
                <h3>
                @if (count($data->colorSize) > 0)
                    @php
                        $varArray = [];
                        foreach($data->colorSize as $productVariationKey => $productVariationValue) {
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
                    &#8377;<span class="price_val">{{$displayPrice}}</span>
                @else
                    &#8377; <span class="price_val">{{$data->offer_price}}</span>
                @endif
                </h3>
            </div>

            {{--<div class="filter_wrapper">
                <button type="button" class="filter_cat_button left_button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#c10909" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </button>
                <div class="filter_category swiper-container">
                    <div class="slider swiper-wrapper">
                        @foreach ($categories as $childCatKey => $childCatValue)
                            <div class="swiper-slide"><a href="{{ route('front.category.detail', $childCatValue['slug']) }}"><img src="{{asset($childCatValue['sketch_icon'])}}"> {{$childCatValue['name']}}</a></div>
                        @endforeach
                    </div>
                </div>
                <button type="button" class="filter_cat_button right_button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#c10909" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            </div>--}}

            @php
                // $RAWcolorsFetch = \DB::select('SELECT pc.id, pc.position, pc.color, c.name, pc.status as color_id FROM product_color_sizes AS pc JOIN colors AS c ON pc.color = c.id WHERE pc.product_id = '.$data->id.' GROUP BY pc.color ORDER BY pc.position ASC');

                // dd($RAWcolorsFetch);

                // foreach ($RAWcolorsFetch as $rawColorsKey => $rawColorsVal) {

                // }
            @endphp

            @if (count($data->colorSize) > 0)
                @php
                $uniqueColors = [];

                foreach ($data->colorSize as $variantKey => $variantValue) {
                    // if (in_array_r($variantValue->colorDetails->code, $uniqueColors)) continue;

                    $uniqueColors[] = [
                        'id' => $variantValue->colorDetails->id,
                        'position' => $variantValue->position,
                        'code' => $variantValue->colorDetails->code,
                        'name' => $variantValue->colorDetails->name,
                        'status' => $variantValue->status,
                        'color_fabric' => $variantValue->color_fabric,
                        'color_name' => $variantValue->color_name,
                    ];
                }

                // dd($uniqueColors);

                echo '<h6>Available Colour</h6><ul class="product__color">';
                foreach($uniqueColors as $colorCodeKey => $colorCode) {
                    if ($colorCode['status'] == 1) {
                        $activeCLass = '';
                        ($colorCodeKey == 0) ? $activeCLass = 'active' : $activeCLass = '';

                        // set color name
                        if ($colorCode['color_name']) {
                            $colorNameToDislay = $colorCode['color_name'];
                        } else {
                            // $orgColorName = \App\Models\Color::select('name')->where('id', $productWiseColorsVal->color)->first();

                            $colorNameToDislay = $colorCode['name'];
                        }

                        if ($colorCode['color_fabric'] != null) {
                            echo '<li onclick="sizeCheck('.$data->id.', '.$colorCode['id'].')" style="background-image: url('.asset($colorCode['color_fabric']).');background-size: cover;" class="'.$activeCLass.'" data-bs-toggle="tooltip" data-bs-placement="top" title="'.$colorNameToDislay.'"></li>';
                        } else {
                            if ($colorCode['id'] == 61) {
                                echo '<li style="background: -webkit-linear-gradient(left,  rgba(219,2,2,1) 0%,rgba(219,2,2,1) 9%,rgba(219,2,2,1) 10%,rgba(254,191,1,1) 10%,rgba(254,191,1,1) 10%,rgba(254,191,1,1) 20%,rgba(1,52,170,1) 20%,rgba(1,52,170,1) 20%,rgba(1,52,170,1) 30%,rgba(15,0,13,1) 30%,rgba(15,0,13,1) 30%,rgba(15,0,13,1) 40%,rgba(239,77,2,1) 40%,rgba(239,77,2,1) 40%,rgba(239,77,2,1) 50%,rgba(254,191,1,1) 50%,rgba(137,137,137,1) 50%,rgba(137,137,137,1) 60%,rgba(254,191,1,1) 60%,rgba(254,191,1,1) 60%,rgba(254,191,1,1) 70%,rgba(189,232,2,1) 70%,rgba(189,232,2,1) 80%,rgba(209,2,160,1) 80%,rgba(209,2,160,1) 90%,rgba(48,45,0,1) 90%); " data-bs-toggle="tooltip" data-bs-placement="top" title="'.$colorNameToDislay.'"></li>';
                            } else {
                                echo '<li onclick="sizeCheck('.$data->id.', '.$colorCode['id'].')" style="background-color: '.$colorCode['code'].'" class="'.$activeCLass.'" data-bs-toggle="tooltip" data-bs-placement="top" title="'.$colorNameToDislay.'"></li>';
                            }
                        }
					}
                }
                echo '</ul>';
                @endphp

                <div class="d-flex justify-content-between">
                    <h6 id="sizeHead" style="{{$primaryColorSizes ? 'display:block;' : 'display:none;' }}">Available Size</h6>
                    <a href="javascript: void(0)" data-bs-target="#sizeChartModal" data-bs-toggle="modal">Size chart</a>
                </div>
                <p id="colorSelectAlert" style="{{$primaryColorSizes ? 'display:none;' : 'display:block;' }}">Please select a colour first</p>
                <ul class="product__sizes" id="sizeContainer">
                    {{-- {{dd($primaryColorSizes)}} --}}
                    @foreach ($primaryColorSizes as $primaryColorSizesKey => $primaryColorSizesValue)
						@if($primaryColorSizesValue->sizeDetails->id == 23)
                            {{-- FREE SIZE --}}
                            <li style="width: 100px;" data-price="{{$primaryColorSizesValue->offer_price}}" data-id="{{$primaryColorSizesValue->id}}">{{$primaryColorSizesValue->sizeDetails->name}}</li>
						@else
                            {{-- OTHER SIZES --}}
                            <li data-price="{{$primaryColorSizesValue->offer_price}}" data-id="{{$primaryColorSizesValue->id}}">{{$primaryColorSizesValue->sizeDetails->name}}</li>
						@endif
                    @endforeach
                </ul>
            @endif

            {{-- <h6>Available Packs</h6>
            <ul class="product__packs">
                <li class="active">{{ $data->pack }}</li>
                <li>Packs of 3</li>
            </ul> --}}



            <div class="product__enquire d-flex">
                {{-- @auth --}}
                <form method="POST" action="{{route('front.cart.add')}}" class="d-flex" id="addToCartForm">
                {{-- @else
                <form method="POST" action="{{route('front.cart.add.guest')}}" class="d-flex" id="addToCartForm">
                @endauth --}}
                    @csrf
                    <div class="item-qty mr-1">
                        <!-- <div class="cart-text">Quantity</div> -->
                        <div class="qty-box">
                            <a href="javascript: void(0)" class="decrement" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            </a>
                            <input class="counter" type="number" value="1" name="qty" readonly>
                            <a href="javascript: void(0)" class="increment" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            </a>
                        </div>
                    </div>
                    <input type="hidden" name="user_id" value="{{ auth()->guard('web')->check() ? auth()->guard('web')->user()->id : 0 }}">
                    <input type="hidden" name="product_id" value="{{$data->id}}">
                    <input type="hidden" name="product_name" value="{{$data->name}}">
                    <input type="hidden" name="product_style_no" value="{{$data->style_no}}">
                    <input type="hidden" name="product_image" value="{{asset($data->image)}}">
                    <input type="hidden" name="product_slug" value="{{$data->slug}}">
                    <input type="hidden" name="product_variation_id" value="">
                    <input type="hidden" name="price" value="{{$data->price}}">
                    <input type="hidden" name="offer_price" value="{{$data->offer_price}}">
                    <button type="submit" id="addToCart__btn" class="mr-1 @if(count($data->colorSize) > 0) missingVariationSelection @endif"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg> <span>Add to Cart</span></button>
                </form>

                @auth
                <form method="POST" action="{{route('front.wishlist.add')}}" id="toggleWishlistForm">@csrf
                    <input type="hidden" name="product_id" value="{{$data->id}}">
                    <button type="submit" class="wishlist_btn {{ ($wishlistCheck) ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                    </button>
                </form>
                @else
                <a href="javascript: void(0)" class="wishlist_btn" onclick="toastFire('warning', 'Login to continue')" style="width: 60px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                </a>
                @endauth
            </div>

            <p>{!! $data->short_desc !!}</p>

            <!-- <h6>Details & Specifications</h6>
            <div class="specification">
                {!! $data->desc !!}
                {{-- {!! substr($data->desc, 0, 100) !!} --}}
                {{-- <h6><a href="javascript: void(0)" data-bs-target="#productDescModal" data-bs-toggle="modal">Read more</a></h6> --}}
            </div> -->
        </div>
    </div>
</section>

<section id="related" class="related-product">
    <div class="container">
        <h3>Related Product</h3>
        <div class="row">
            @forelse($relatedProducts as $relatedProductKey => $relatedProductValue)

            @php if($relatedProductValue->status == 0) {continue;} @endphp
            <a href="{{ route('front.product.detail', $relatedProductValue->slug) }}" class="home-gallary__single" data-events data-cat="tshirt">
                <figure>
                    <img src="{{asset($relatedProductValue->image)}}" />
                    <h6>{{$relatedProductValue->style_no}}</h6>
                </figure>
                <figcaption>
                    <h4>{{$relatedProductValue->name}}</h4>
                    <h5>
                    @if (count($relatedProductValue->colorSize) > 0)
                        @php
                            $varArray = [];
                            foreach($relatedProductValue->colorSize as $productVariationKey => $productVariationValue) {
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
                        &#8377;{{$displayPrice}}
                    @else
                        &#8377;{{$relatedProductValue->offer_price}}
                    @endif
                    </h5>

                    {!! variationColors($relatedProductValue->id, 4) !!}

                    {{-- @php
                        $relatedProductsVariationRAW = \DB::select('SELECT pc.id, pc.position, pc.color AS color_id, c.name as color_name, c.code as color_code, pc.status, pc.color_fabric, pc.color_name FROM product_color_sizes pc JOIN colors c ON pc.color = c.id WHERE pc.product_id = '.$relatedProductValue->id.' GROUP BY pc.color ORDER BY pc.position ASC');

                        if (count($relatedProductsVariationRAW) > 0) {
                            echo '<div class="color"><ul class="product__color">';

                            $usedColros = $activeColros = 1;
                            foreach($relatedProductsVariationRAW as $relatedProsVarKey => $relatedProsVarVal) {
                                if($relatedProsVarVal->status == 1) {
                                    if($usedColros < 5) {
                                        echo '<li style="background-color: '.$relatedProsVarVal->color_code.'" class="color-holder" data-bs-toggle="tooltip" data-bs-placement="top" title="'.$relatedProsVarVal->color_name.'"></li>';
                                        $usedColros++;
                                    }
                                    $activeColros++;
                                }
                            }

                            if ($activeColros > 4 && $usedColros == 5) echo '<li>+ more</li>';

                            echo '</ul></div>';
                        }
                    @endphp --}}

                    {{-- <div class="color">
                        @if (count($relatedProductValue->colorSize) > 0)
                        @php
                        $relaredProUniqueColors = [];

                        foreach ($relatedProductValue->colorSize as $relatedProductsVariantKey => $relatedProductsVariantValue) {
                            // if (in_array_r($relatedProductsVariantValue->colorDetails->code, $relaredProUniqueColors)) continue;
                            if ($relatedProductsVariantValue->status == 1) {
                                $relaredProUniqueColors[] = [
                                    'id' => $relatedProductsVariantValue->colorDetails->id,
                                    'code' => $relatedProductsVariantValue->colorDetails->code,
                                    'name' => $relatedProductsVariantValue->colorDetails->name,
                                    // 'status' => $relatedProductsVariantValue->status,
                                ];
                            }
                        }

                        if ($relatedProductsVariantKey == 5) {
                            dd($relaredProUniqueColors);
                        }

                        echo '<ul class="product__color">';
                        // echo count($relaredProUniqueColors);
                        foreach($relaredProUniqueColors as $colorCodeKey => $colorCode) {
                            // if ($colorCode['status'] == 1) {
                                if ($colorCodeKey == 4) {break;}
                                // if ($colorCodeKey < 4) {
                                    if ($colorCode['id'] == 61) {
                                        echo '<li style="background: -webkit-linear-gradient(left,  rgba(219,2,2,1) 0%,rgba(219,2,2,1) 9%,rgba(219,2,2,1) 10%,rgba(254,191,1,1) 10%,rgba(254,191,1,1) 10%,rgba(254,191,1,1) 20%,rgba(1,52,170,1) 20%,rgba(1,52,170,1) 20%,rgba(1,52,170,1) 30%,rgba(15,0,13,1) 30%,rgba(15,0,13,1) 30%,rgba(15,0,13,1) 40%,rgba(239,77,2,1) 40%,rgba(239,77,2,1) 40%,rgba(239,77,2,1) 50%,rgba(254,191,1,1) 50%,rgba(137,137,137,1) 50%,rgba(137,137,137,1) 60%,rgba(254,191,1,1) 60%,rgba(254,191,1,1) 60%,rgba(254,191,1,1) 70%,rgba(189,232,2,1) 70%,rgba(189,232,2,1) 80%,rgba(209,2,160,1) 80%,rgba(209,2,160,1) 90%,rgba(48,45,0,1) 90%); " class="color-holder" data-bs-toggle="tooltip" data-bs-placement="top" title="Assorted"></li>';
                                    } else {
                                        echo '<li onclick="sizeCheck('.$relatedProductValue->id.', '.$colorCode['id'].')" style="background-color: '.$colorCode['code'].'" class="color-holder" data-bs-toggle="tooltip" data-bs-placement="top" title="'.$colorCode['name'].'"></li>';
                                    }
                                // }
                            // }
                        }
                        if (count($relaredProUniqueColors) > 4) {echo '<li>+ more</li>';}
                        echo '</ul>';
                        @endphp
                        @endif
                    </div> --}}
                </figcaption>
            </a>
            @empty
            <p class="ml-2">Sorry, No related products found </p>
            @endforelse
        </div>
    </div>
</section>

<div class="modal fade" id="productDescModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6>DETAILS & SPECIFICATIONS</h6>
                <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body">
            {!! $data->desc !!}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="sizeChartModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6>Size Chart For {{$data->category->name}}</h6>
                <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body">
            <!-- {!! $data->size_chart !!} -->


            @php
                if($data->category->name == "T-Shirt") {
                    if($data->only_for == 'all') {
            @endphp
                <figure>
                    <img src="{{asset($data->category->image_path)}}" class="img-fluid">
                </figure>
                <table class="size_table">
                    <tr>
                        <th>XS</th>
                        <th>S</th>
                        <th>M</th>
                        <th>L</th>
                        <th>XL</th>
                        <th>XXL</th>
                        <th>3XL</th>
                        <th>4XL</th>
                    </tr>
                    <tr>
                        <td>70-75</td>
                        <td>80-85</td>
                        <td>90-95</td>
                        <td>100-105</td>
                        <td>110-115</td>
                        <td>120-125</td>
                        <td>130-135</td>
                        <td>140-145</td>
                    </tr>
                </table>
                @php
                    } elseif($data->only_for == 'Women') {
                @endphp
                <figure>
                    <img src="{{asset($data->category->image_path)}}" class="img-fluid">
                </figure>
                <table class="size_table">
                    <tr>
                        <th>S</th>
                        <th>M</th>
                        <th>L</th>
                        <th>XL</th>
                        <th>XXL</th>
                    </tr>
                    <tr>
                        <td>75-80</td>
                        <td>85-90</td>
                        <td>95-100</td>
                        <td>105-110</td>
                        <td>115-120</td>
                    </tr>
                </table>
                @php
                    } else {
                @endphp
                <figure>
                    <img src="{{asset($data->category->image_path)}}" class="img-fluid">
                </figure>
                <table class="size_table">
                    <tr>
                        <th>3-4</th>
                        <th>5-6</th>
                        <th>7-8</th>
                        <th>9-10</th>
                        <th>11-12</th>
                        <th>13-14</th>
                    </tr>
                    <tr>
                        <td>50cms</td>
                        <td>55cms</td>
                        <td>60cms</td>
                        <td>65cms</td>
                        <td>70cms</td>
                        <td>75cms</td>
                    </tr>
                </table>
                @php
                    }
                @endphp
            @php
                } elseif($data->category->name == "Vest") {
            @endphp
            <figure>
                    <img src="{{asset($data->category->image_path)}}" class="img-fluid">
                </figure>
            <table class="size_table">
                <tr>
                    <th>XS</th>
                    <th>S</th>
                    <th>M</th>
                    <th>L</th>
                    <th>XL</th>
                    <th>2XL</th>
                </tr>
                <tr>
                    <td>70</td>
                    <td>80</td>
                    <td>85</td>
                    <td>90</td>
                    <td>95</td>
                    <td>100</td>
                </tr>
            </table>
            @php
                } elseif($data->category->name == "Brief") {
            @endphp
            <figure>
                    <img src="{{asset($data->category->image_path)}}" class="img-fluid">
                </figure>
            <table class="size_table">
                <tr>
                    <th>S</th>
                    <th>M</th>
                    <th>L</th>
                    <th>XL</th>
                    <th>2XL</th>
                </tr>
                <tr>
                    <td>80</td>
                    <td>85</td>
                    <td>90</td>
                    <td>95</td>
                    <td>100</td>
                </tr>
            </table>
            @php
                } elseif($data->category->name == "Trunk") {
            @endphp
            <figure>
                    <img src="{{asset($data->category->image_path)}}" class="img-fluid">
                </figure>
            <table class="size_table">
                <tr>
                    <th>XS</th>
                    <th>S</th>
                    <th>M</th>
                    <th>L</th>
                    <th>XL</th>
                    <th>2XL</th>
                </tr>
                <tr>
                    <td>70</td>
                    <td>80</td>
                    <td>85</td>
                    <td>90</td>
                    <td>95</td>
                    <td>100</td>
                </tr>
            </table>
            @php
                } elseif($data->category->name == "Boxer") {
            @endphp
            <figure>
                    <img src="{{asset($data->category->image_path)}}" class="img-fluid">
                </figure>
            <table class="size_table">
                <tr>
                    <th>S</th>
                    <th>M</th>
                    <th>L</th>
                    <th>XL</th>
                    <th>2XL</th>
                </tr>
                <tr>
                    <td>80</td>
                    <td>85</td>
                    <td>90</td>
                    <td>95</td>
                    <td>100</td>
                </tr>
            </table>
            @php
                } elseif($data->category->name == "Track Pants") {
            @endphp
            <figure>
                    <img src="{{asset($data->category->image_path)}}" class="img-fluid">
                </figure>
            <table class="size_table">
                <tr>
                    <th>S</th>
                    <th>M</th>
                    <th>L</th>
                    <th>XL</th>
                    <th>XXL</th>
                </tr>
                <tr>
                    <td>70-75</td>
                    <td>80-85</td>
                    <td>90-95</td>
                    <td>100-105</td>
                    <td>110-115</td>
                </tr>
            </table>
            @php
                } elseif($data->category->name == "Half Pants") {
            @endphp
            <figure>
                    <img src="{{asset($data->category->image_path)}}" class="img-fluid">
                </figure>
            <table class="size_table">
                <tr>
                    <th>S</th>
                    <th>M</th>
                    <th>L</th>
                    <th>XL</th>
                    <th>XXL</th>
                </tr>
                <tr>
                    <td>70-75</td>
                    <td>80-85</td>
                    <td>90-95</td>
                    <td>100-105</td>
                    <td>110-115</td>
                </tr>
            </table>
            @php
                } elseif($data->category->name == "Thermal") {
            @endphp
            <figure>
                    <img src="{{asset($data->category->image_path)}}" class="img-fluid">
                </figure>
            <table class="size_table">
                <tr>
                    <th>XS</th>
                    <th>S</th>
                    <th>M</th>
                    <th>L</th>
                    <th>XL</th>
                    <th>XXL</th>
                    <th>3XL</th>
                </tr>
                <tr>
                    <td>75cm</td>
                    <td>80cm</td>
                    <td>85cm</td>
                    <td>90cm</td>
                    <td>95cm</td>
                    <td>1.0mtr</td>
                    <td>1.05mtr</td>
                </tr>
            </table>
            @php
                } elseif($data->category->name == "Sweatshirt") {
            @endphp
            <figure>
                    <img src="{{asset($data->category->image_path)}}" class="img-fluid">
                </figure>
            <table class="size_table">
                <tr>
                    <th>S</th>
                    <th>M</th>
                    <th>L</th>
                    <th>XL</th>
                    <th>XXL</th>
                </tr>
                <tr>
                    <td>70-75</td>
                    <td>80-85</td>
                    <td>90-95</td>
                    <td>100-105</td>
                    <td>110-115</td>
                </tr>
            </table>
            @php
                } elseif($data->category->name == "Jackets") {
            @endphp
            <figure>
                    <img src="{{asset($data->category->image_path)}}" class="img-fluid">
                </figure>
            <table class="size_table">
                <tr>
                    <th>S</th>
                    <th>M</th>
                    <th>L</th>
                    <th>XL</th>
                    <th>XXL</th>
                </tr>
                <tr>
                    <td>70-75</td>
                    <td>80-85</td>
                    <td>90-95</td>
                    <td>100-105</td>
                    <td>110-115</td>
                </tr>
            </table>
            @php
                } else {
            @endphp
            <figure>
                    <img src="{{asset($data->category->image_path)}}" class="img-fluid">
                </figure>
            <table class="size_table">
                <tr>
                    <th>Free Size</th>
                </tr>
            </table>
            @php
                }
            @endphp

            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // $('.product__color li').eq(0).addClass('active');

    function sizeCheck(productId, colorId) {
        $.ajax({
            url : '{{route("front.product.size")}}',
            method : 'POST',
            data : {'_token' : '{{csrf_token()}}', productId : productId, colorId : colorId},
            beforeSend: function() {
                /* $loadingSwal = Swal.fire({
                    title: 'Please wait...',
                    text: 'We are fetching your details!',
                    showConfirmButton: false,
                    // allowOutsideClick: false
                    // timer: 1500
                }) */
            },
            success : function(result) {
                if (result.error === false) {
                    // $loadingSwal.close();
                    $('#sizeHead').show();
                    $('#colorSelectAlert').hide();

                    // size handling
                    let content = '';
                    $.each(result.data, (key, val) => {
                        content += `<li data-price="${val.offerPrice}" data-id="${val.variationId}">${val.sizeName}</li>`;
                    })
                    $('#addToCart__btn').addClass('missingVariationSelection');
                    $('#sizeContainer').html(content);

                    // color handling
                    let imgContentThumb = '';
                    let imgContentSlider = '';
                    $.each(result.images, (key, val) => {
                        imgContentThumb += `<div class="product-details__gallery__thumb-single swiper-slide"><img src="${val.image}" /></div>`;
                        imgContentSlider += `<div class="product-details__gallery__slider-single swiper-slide"><img src="${val.image}" /></div>`;
                    });
                    $('.product-details__gallery__thumb .swiper-wrapper').html(imgContentThumb);
                    $('.product-details__gallery__slider .swiper-wrapper').html(imgContentSlider);

                    var gallery__thumb = new Swiper(".product-details__gallery__thumb", {
                        direction: "vertical",
                        loop: false,
                        spaceBetween: 10,
                        slidesPerView: 4,
                        freeMode: true,
                        lazy: true,
                        observer: true,
                        runCallbacksOnInit: true,
                        watchSlidesProgress: true,
                        slideToClickedSlide: true,
                    });
                    var gallery__slider = new Swiper(".product-details__gallery__slider", {
                        loop: false,
                        lazy: true,
                        observer: true,
                        spaceBetween: 0,
                        centeredSlides: true,
                        runCallbacksOnInit: true,
                        navigation: {
                            nextEl: ".buttom_button",
                            prevEl: ".top_button",
                        },
                        thumbs: {
                            swiper: gallery__thumb,
                        },
                    });
                } else {
                    // $loadingSwal.close();

                    Swal.fire({
                        title: 'OOPS',
                        text: 'No images found!',
                        timer: 1000
                    })
                }
            },
            error: function(xhr, status, error) {
                $('#colorSelectAlert').text('Something Went wrong. Try again');
            }
        });
    }

    // variation selection check
    $('#addToCart__btn').on('click', function(e) {
        if ($(this).hasClass('missingVariationSelection')) {
            e.preventDefault();
            alert('Select color & size first');
        }
    });

    // get variation id & load into product_variation_id
    $(document).on('click', '#sizeContainer li', function(){
        $('#addToCart__btn').removeClass('missingVariationSelection');
        var variationId = $(this).attr('data-id');
        $('input[name="product_variation_id"]').val(variationId);
        // console.log(variationId);
    });

    /* $(document).on('click', '.missingVariationSelection', function(){
        alert('here');
    }); */

	// add to cart ajax
	$('#addToCartForm').on('submit', function(e) {
		e.preventDefault();
        let token = '';

        if ($('input[name="user_id"]').val() == 0) {
            const tokenExists = localStorage.getItem('cartToken');

            if (!tokenExists) {
                token = '{{generateUniqueAlphaNumeric(10)}}';
                const tokenStr = JSON.stringify('{{generateUniqueAlphaNumeric(10)}}');
                // localStorage.setItem('cartToken', tokenStr);
                localStorage.setItem('cartToken', token);
            } else {
                token = tokenExists;
            }
        }

		var data = $(this).serialize();
		$.ajax({
			// url: $(this).attr('action'),
			url: "{{ route('front.cart.add.guest') }}",
			method: $(this).attr('method'),
			data: data+'&token='+token,
			beforeSend: function() {
				$('#addToCart__btn').addClass('missingVariationSelection').text('Adding to Cart');
			},
			success: function(result) {
				const Toast = Swal.mixin({
					toast: true,
					position: 'top-end',
					showConfirmButton: false,
					timer: 2000,
					// timerProgressBar: true,
					didOpen: (toast) => {
						toast.addEventListener('mouseenter', Swal.stopTimer)
						toast.addEventListener('mouseleave', Swal.resumeTimer)
					}
				})
				if (result.status == 200) {
					Toast.fire({
					  icon: 'success',
					  title: result.message
					})
					$('#cart-count').text(result.response).removeClass('d-none');
				} else {
					Toast.fire({
					  icon: 'error',
					  title: result.message
					})
				}
				$('#addToCart__btn').attr('disabled', false).removeClass('missingVariationSelection').html(`<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg> <span>Add to Cart</span>`);
				// $('#addToCart__btn').attr('disabled', false).removeClass('missingVariationSelection').text('Add to Cart');
				$('.wishlist_btn').attr('disabled', false);
			},
		});
	});

	// wishlist ajax
	$('#toggleWishlistForm').on('submit', function(e) {
		e.preventDefault();
		var data = $(this).serialize();
		$.ajax({
			url: $(this).attr('action'),
			method: $(this).attr('method'),
			data: data,
			beforeSend: function() {
				// $('#addToCart__btn').addClass('missingVariationSelection').text('Adding to Cart');
			},
			success: function(result) {
				const Toast = Swal.mixin({
					toast: true,
					position: 'top-end',
					showConfirmButton: false,
					timer: 2000,
					// timerProgressBar: true,
					didOpen: (toast) => {
						toast.addEventListener('mouseenter', Swal.stopTimer)
						toast.addEventListener('mouseleave', Swal.resumeTimer)
					}
				})
				if (result.status == 200) {
					Toast.fire({
					  icon: 'success',
					  title: result.message
					});

					if (result.message == "wishlisted") {
						Toast.fire({
						  icon: 'success',
						  title: 'Product addded to Wishlist'
						});
						$('.wishlist_btn').addClass('active');
					} else {
						Toast.fire({
						  icon: 'success',
						  title: 'Product removed from Wishlist'
						});
						$('.wishlist_btn').removeClass('active');
					}

					$('#wishlist-count').text(result.count);
				} else {
					Toast.fire({
					  icon: 'error',
					  title: result.message
					})
				}
				$('#addToCart__btn').attr('disabled', false);
				$('.wishlist_btn').attr('disabled', false);
			},
		});
	});
</script>
@endsection
