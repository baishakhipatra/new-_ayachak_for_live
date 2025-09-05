@extends('layouts.app')

@section('page', 'Category')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.0/css/bootstrap-slider.min.css">
    <style>
        .tooltip-main {
            opacity: 1;
        }

        select {
            border: none;
            background: transparent;
        }

        select:focus {
            outline: none;
            box-shadow: none;
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

        /*.customCats.active {
        display: block;
        border: 2px solid #c1080a;
    }*/
        .listing-block .product__single figure h6 {
            display: block;
            background: #fff;
        }

        .listing-block-left-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .listing-block-left-heading h4 {
            border-bottom: 0!important;
            padding-bottom: 0!important;
        }
        .listing-block-left-heading {
            border-bottom: 1px dashed #ddd;
        }
        .listing-block-left-heading a {
            border: none;
            border: 1px solid #111;
            border-radius: 3px;
            text-transform: capitalize;
            padding: 6px 10px;
            background: none;
            transition: all .5s ease-in-out;
        }
        .listing-block-left-heading a:hover {
            background: #c10909;
            border-color: #c10909;
            color: #fff;
        }
        .listing-block-left-heading {
            padding-bottom: 16px;
        }
        .filter_li.active {
            background: #c10909!important;
            border-color: #c10909!important;
        }
        .filter_li:hover {
            background: #c10909!important;
            border-color: #c10909!important;
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


{{-- @php
    dd($data);
@endphp --}}

<!-- <section class="listing-header">
    <div class="container">
        <div class="row flex-sm-row-reverse align-items-center">
            <div class="col-sm-3 d-none d-sm-block">
                <img src="{{ asset($data->banner_image) }}" class="img-fluid">
            </div>
            <div class="col-sm-9">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$data->name}}</li>
                    </ol>
                </nav>
                <h1>{{ $data->name }}</h1>
            </div>
        </div>
    </div>
</section> -->



<section class="listing-header">
    <div class="container">
        <div class="row align-items-center">
            <!-- <div class="col-sm-3 d-none d-sm-block">
                <img src="{{ asset($data->banner_image) }}" class="img-fluid">
            </div> -->
            <div class="col-sm-6">
                <h1>{{ $data->name }}</h1>
            </div>
            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$data->name}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>



<section class="filter_by_cat">
    <div class="container">
        <h3>{{ $data->parentCatDetails->name ?? '' }}</h3>
        <ul class="filter_cat_list">
            @php
                /* if(count($data->ProductDetails) > 0) {
                    $customCats = [];
                    foreach ($data->ProductDetails as $ProductDetailsKey => $ProductDetailsValue) {
                        if($ProductDetailsValue->status == 1) {
                            if(in_array_r($ProductDetailsValue->cat_id, $customCats)) continue;

                            $customCats[] = [
                                'id' => $ProductDetailsValue->cat_id,
                                'name' => $ProductDetailsValue->category->name,
                                'icon' => $ProductDetailsValue->category->icon_path
                            ];
                        }
                    }
                } */
            @endphp
            @foreach ($categories as $categoryKey => $categoryValue)
                @if($categoryValue->parent == $data->parent)
                    <li class="position-relative">
                        <a href="{{ route('front.category.detail', $categoryValue->slug) }}" class="customCats {{ ($categoryValue->id == $data->id) ? 'active' : '' }}" id="customCat_{{$categoryValue['id']}}" data-id="{{$categoryValue['id']}}">
                            <figure>
                                <img src="{{ asset($categoryValue['icon_path']) }}">
                            </figure>
                            <figcaption>
                                {{ $categoryValue['name'] }}
                            </figcaption>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</section>

<section class="listing-block">
    <div class="container">
        <div class="row g-3">
            <div class="col-12 col-md-3">

                <div class="listing-block-left">

                    <div class="listing-block-left-heading">
                        <h4>Filter By: </h4>
                        <a href="{{ url()->current() }}">clear filter</a>
                    </div>
                    <div class="listing-block-left-elem listing-block-left-elem2">
                        <h5>Size</h5>
                        <ul class="list-unstyled p-0 m-0">
                            @foreach ($sizeData as $item)
                                <li class="filter_li{{ request()->input('size') == $item->id ? 'bg-success text-white' : '' }}"
                                    style="">
                                    <label>
                                        <input type="checkbox" name="size[]" value="{{ $item->id }}"
                                            onclick="productsFetch()" class="checkbox">
                                        <span
                                            class="{{ request()->input('size') == $item->id ? 'bg-success' : '' }}">{{ $item->name }}</span>
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="listing-block-left-elem listing-block-left-elem2">
                        <h5>Style</h5>
                        <ul class="list-unstyled p-0 m-0">
                            @foreach ($styleNo as $item)
                                <li class="filter_li{{ request()->input('style') == $item->style_no ? 'bg-success text-white' : '' }}"
                                    style="">
                                    <label class="">
                                        <input type="checkbox" name="style[]" value="{{ $item->style_no }}"
                                            onclick="productsFetch()" class="checkbox">
                                        <span
                                            class="{{ request()->input('style') == $item->style_no ? 'bg-success' : '' }}">{{ $item->style_no }}</span>
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>


                    {{-- <div class="listing-block-left-elem listing-block-left-elem2">
                  <h5>Collection</h5>
                  <ul class="list-unstyled p-0 m-0">
                    @foreach ($collections as $categoryKey => $categoryValue)
                   
                    <li class="{{ (request()->input('collection') == $categoryValue->id) ? 'bg-success text-white' : '' }}" style="">                                
                        <label>
                            <form action="{{request()->fullUrl()}}" method="GET">
                                <input class="d-none" type="checkbox" onclick="$(this).parent().submit()" name="collection" value="{{ $categoryValue->id }}" {{ (request()->input('collection') == $categoryValue->id) ? 'checked' : '' }}>
                            </form>
                            <span class="{{ (request()->input('collection') == $categoryValue->id) ? 'bg-success' : '' }}">{{ $categoryValue->name }}</span>
                        </label>
                </li>
                    @endforeach
                  </ul>
                </div> --}}

                    <div class="listing-block-left-elem listing-block-left-elem3">
                        <h5>Color</h5>
                        <ul class="list-unstyled p-0 m-0">
                            @foreach ($colorData as $color)
                                <li class="filter_li{{ request()->input('color') == $color->id ? 'bg-success text-white' : '' }}"
                                    style="">
                                    <label class="">
                                        <input class="checkbox" type="checkbox" onclick="productsFetch()" name="color[]"
                                            value="{{ $color->id }}"
                                            {{ request()->input('color') == $color->id ? 'checked' : '' }}>

                                        <span class="filter-color" style="background-color:{{ $color->code }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{$color->name}}"></span>
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="listing-block-left-elem listing-block-left-elem2">
                        <h5>Price</h5>
                        <div class="price-top price-input">
                            {{-- <div class="min-value ">
                            ₹ <input type="number" name="price" class="input-min" value="179">
                        </div>
                        <div class="max-value">
                            ₹ <input type="number" name="price" class="input-max" value="2000">
                        </div> --}}
                            <input id="range" type="text" name="price" onchange="productsFetch()">
                        </div>
                        {{-- <div class="slider">
                        <div class="progress"></div>
                    </div>
                    <div class="range-input">
                        <input type="range" class="range-min" min="0" max="10000" value="0" step="100">
                        <input type="range" class="range-max" min="0" max="10000" value="10000" step="100">
                    </div> --}}
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-9 mt-3">
                @if (count($data->ProductDetails) > 0)
                <div class="listing-block__meta">
            {{-- <div class="filter">
                <div class="filter__toggle">
                    Filter
                </div>
                <div class="filter__data"></div>
            </div> --}}
            <div class="products mr-3">
                {{--<h6><span id="prod_count">{{ $data->ProductDetails->count() }}</span> <span id="prod_text">{{ ($data->ProductDetails->count() > 1) ? 'products' : 'product' }}</span> found</h6>--}}
            </div>
            <div class="sorting">
                Sort By:
                <select name="orderBy" onclick="productsFetch()">
                    <option value="new_arr">New Arrivals</option>
                    <option value="mst_viw">Most Viewed</option>
                    <option value="prc_low">Price: Low To High</option>
                    <option value="prc_hig">Price: High To Low</option>
                </select>
            </div>
        </div>

        <div class="product__wrapper">
            <div class="product__filter d-none">
                <div class="product__filter__bar">
                    <div class="filter__close">
                        <i class="fal fa-times"></i>
                    </div>
                    <div class="row">
                        <div class="col-6 col-sm-12 mb-3 mb-sm-0">
                            <h4>Range</h4>
                            <ul class="product__filter__bar-list">
                                @foreach($collections as $categoryKey => $categoryValue)
                                    <li><label><input type="checkbox" name="collection[]" pro-filter="{{$categoryValue->name}}" value="{{$categoryValue->id}}" onclick="productsFetch()"><i></i> {{$categoryValue->name}}</label></li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-6 col-sm-12 mb-3 mb-sm-0">
                            <h4>Sizes</h4>
                            <ul class="product__filter__bar-list">
                                @foreach($sizes as $sizeKey => $sizeValue)
                                    <li><label><input type="checkbox" pro-filter="{{$sizeValue->name}}"><i></i> {{$sizeValue->name}}</label></li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-6 col-sm-12 mb-3 mb-sm-0">
                            <h4>Price</h4>
                            <ul class="product__filter__bar-list">
                                <li><label><input type="checkbox" pro-filter="&#8377;339 - &#8377;425"><i></i> &#8377;339 - &#8377;425</label></li>
                                <li><label><input type="checkbox" pro-filter="&#8377;410 - &#8377;450"><i></i> &#8377;410 - &#8377;450</label></li>
                                <li><label><input type="checkbox" pro-filter="&#8377;499 - &#8377;525"><i></i> &#8377;499 - &#8377;525</label></li>
                                <li><label><input type="checkbox" pro-filter="&#8377;575 - &#8377;599"><i></i> &#8377;575 - &#8377;599</label></li>
                                <li><label><input type="checkbox" pro-filter="&#8377;599 - &#8377;625"><i></i> &#8377;599 - &#8377;625</label></li>
                                <li><label><input type="checkbox" pro-filter="&#8377;590 - &#8377;615"><i></i> &#8377;590 - &#8377;615</label></li>
                                <li><label><input type="checkbox" pro-filter="&#8377;450 - &#8377;475"><i></i> &#8377;450 - &#8377;475</label></li>
                                <li><label><input type="checkbox" pro-filter="&#8377;430 - &#8377;450"><i></i> &#8377;430 - &#8377;450</label></li>
                            </ul>
                        </div>
                        <div class="col-12">
                            <h4>Color</h4>
                            <ul class="product__filter__bar-list column-2">
                                @foreach($colors as $colorKey => $colorValue)
                                    <li><label> <input type="checkbox" pro-filter="{{$colorValue->name}}"><i></i> {{ucwords($colorValue->name)}}</label></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="product__holder">
                <div class="row">
                    @php
                        // $productsData = \App\Models\Product::where('cat_id', $data->id)->orderBy('style_no', 'asc')->get();
                    @endphp

                    {{-- @forelse($productsData as $categoryProductKey => $categoryProductValue) --}}
                    @forelse($data->ProductDetails as $categoryProductKey => $categoryProductValue)
                    @php if($categoryProductValue->status == 0) {continue;} @endphp
                    <a href="{{ route('front.product.detail', $categoryProductValue->slug) }}" class="product__single" data-events data-cat="tshirt">
                        <figure>
                            <img src="{{asset($categoryProductValue->image)}}" />
                        </figure>
                        <figcaption>
                            <h4>{{$categoryProductValue->name}} <br/>Style No: <span>{{$categoryProductValue->style_no}}</span></h4>
                            <h5>
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
                                @endphp
								@if($smaller == $bigger)
									&#8377;{{$bigger}}
								@else
									&#8377;{{$smaller}} - &#8377;{{$bigger}}
								@endif
                            @else
                                &#8377;{{$categoryProductValue->offer_price}}
                            @endif
                            </h5>
                        </figcaption>

                        {!! variationColors($categoryProductValue->id, 5) !!}
                    </a>
                    @empty

                    @endforelse
                </div>
            </div>
        </div>
        @else
            <p>Sorry, No products found under {{$data->name}} </p>
        @endif
    </div>
</section>
@endsection

@section('script')
<script>
     const filterLis = document.querySelectorAll('.filter_li')
        filterLis.forEach(filterLi => {
            //console.log(filterLi.classList.contains('label'))
            filterLi.addEventListener('click', (e) => {
                if(e.target.classList.contains('checkbox')) {
                    filterLi.classList.toggle('active')
                }
                else {
                    console.log('ee')
                }
            })
        })
    function productsFetch() {
        // collection values
        var collectionArr = [];
        $('input[name="collection[]"]:checked').each(function(i){
          collectionArr[i] = $(this).val();
        });
          //color value
          var colorArr = [];
            $('input[name="color[]"]:checked').each(function(i) {
                colorArr[i] = $(this).val();
                //console.log(colorArr);
            });
            //size value
            var sizeArr = [];
            $('input[name="size[]"]:checked').each(function(i) {
                sizeArr[i] = $(this).val();
               // console.log(sizeArr);
            });

            var styleArr = [];
            $('input[name="style[]"]:checked').each(function(i) {
                styleArr[i] = $(this).val();
               // console.log(styleArr);
            });
        $.ajax({
            url: '{{route("front.category.filter")}}',
            method: 'POST',
            data: {
                '_token' : '{{ csrf_token() }}',
                'categoryId' : '{{$data->id}}',
                'orderBy' : $('select[name="orderBy"]').val(),
                'price': $('input[name="price"]').val(),
                'collection' : collectionArr,
                'color': colorArr,
                'size': sizeArr,
                'style': styleArr,
            },
            beforeSend: function() {
                /* $loadingSwal = Swal.fire({
                    title: 'Please wait...',
                    text: 'We are adjusting the products as per your need!',
                    showConfirmButton: false,
                    allowOutsideClick: false
                    // timer: 1500
                }) */
            },
            success: function(result) {
                if (result.status == 200) {
                    var content = prodText = '';
                    $('#prod_count').text(result.data.length);
                    (result.data.length > 1) ? prodText = 'products' : prodText = 'product';
                    $('#prod_text').text(prodText);
                    $.each(result.data, function(key, value) {
                        var url = '{{ route('front.product.detail', ":slug") }}';
                        url = url.replace(':slug', value.slug);

                        content += `
                        <a href="${url}" class="product__single" data-events data-cat="tshirt">
                            <figure>
                                <img src="{{asset('${value.image}')}}" />
                                <!--<h6>${value.styleNo}</h6>-->
                            </figure>
                            <figcaption>
                                <h4>${value.name}<br/>Style No: <span>${value.styleNo}</span></h4>
                                <h5>${value.displayPrice}</h5>
                            </figcaption>
                            <div class="color">${value.colorVariation}</div>
                        </a>
                        `;
                    });

                    $('.product__holder .row').html(content);
                    // $loadingSwal.close();
                }
                // console.log(result);
            },
            error: function(result) {
                // $loadingSwal.close()
                console.log(result);
                $errorSwal = Swal.fire({
                    // icon: 'error',
                    // title: 'We cound not find anything',
                    text: 'We cound not find anything. Try again with a different filter!',
                    confirmButtonText: 'Okay'
                })
            },
        });
    }
</script>
<script>
    const rangeInput = document.querySelectorAll(".range-input input"),
        priceInput = document.querySelectorAll(".price-input input"),
        range = document.querySelector(".slider .progress");
    let priceGap = 1000;

    priceInput.forEach(input => {
        input.addEventListener("input", e => {
            let minPrice = parseInt(priceInput[0].value),
                maxPrice = parseInt(priceInput[1].value);

            if ((maxPrice - minPrice >= priceGap) && maxPrice <= rangeInput[1].max) {
                if (e.target.className === "input-min") {
                    rangeInput[0].value = minPrice;
                    range.style.left = ((minPrice / rangeInput[0].max) * 100) + "%";
                } else {
                    rangeInput[1].value = maxPrice;
                    range.style.right = 100 - (maxPrice / rangeInput[1].max) * 100 + "%";
                }
            }
        });
    });

    rangeInput.forEach(input => {
        input.addEventListener("input", e => {
            let minVal = parseInt(rangeInput[0].value),
                maxVal = parseInt(rangeInput[1].value);

            if ((maxVal - minVal) < priceGap) {
                if (e.target.className === "range-min") {
                    rangeInput[0].value = maxVal - priceGap
                } else {
                    rangeInput[1].value = minVal + priceGap;
                }
            } else {
                priceInput[0].value = minVal;
                priceInput[1].value = maxVal;
                range.style.left = ((minVal / rangeInput[0].max) * 100) + "%";
                range.style.right = 100 - (maxVal / rangeInput[1].max) * 100 + "%";
            }
        });
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.0/bootstrap-slider.min.js"></script>

<script>
    @if (!empty($request->price))
        @php
            $amount = explode(',', $request->price);
            $minAmount = $amount[0];
            $maxAmount = $amount[1];
        @endphp
        let val = [{{ $minAmount }}, {{ $maxAmount }}];
    @else
        let val = [90, 5000];
    @endif

    // Range Slider
    var slider = new Slider("#range", {
        min: {{ $range->min }},
        max: {{ $range->max }},
        value: val,
        range: true,
        tooltip: 'always'
    });
</script>
@endsection