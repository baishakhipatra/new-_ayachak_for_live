@extends('layouts.app')

@section('page', 'Collection')

@section('content')
<style>
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

{{-- <section class="filter_by_cat">
    <div class="container">
        <h3>Filter By Category</h3>

        <ul class="filter_cat_list">
            <li>
                <a href="#">
                    <figure>
                        <img src="https://eager-elgamal.43-225-53-183.plesk.page/uploads/category/catago_1.png">
                    </figure>
                    <figcaption>
                        Hoodie
                    </figcaption>
                </a>
            </li>
            <li>
                <a href="#">
                    <figure>
                        <img src="https://eager-elgamal.43-225-53-183.plesk.page/uploads/category/catago_2.png">
                    </figure>
                    <figcaption>
                        Boxer
                    </figcaption>
                </a>
            </li>
            <li>
                <a href="#">
                    <figure>
                        <img src="https://eager-elgamal.43-225-53-183.plesk.page/uploads/category/catago_3.png">
                    </figure>
                    <figcaption>
                        Vest
                    </figcaption>
                </a>
            </li>
            <li>
                <a href="#">
                    <figure>
                        <img src="https://eager-elgamal.43-225-53-183.plesk.page/uploads/category/catago_4.png">
                    </figure>
                    <figcaption>
                        Brief
                    </figcaption>
                </a>
            </li>
            <li>
                <a href="#">
                    <figure>
                        <img src="https://eager-elgamal.43-225-53-183.plesk.page/uploads/category/catago_5.png">
                    </figure>
                    <figcaption>
                        T-Shirt
                    </figcaption>
                </a>
            </li>
            <li>
                <a href="#">
                    <figure>
                        <img src="https://eager-elgamal.43-225-53-183.plesk.page/uploads/category/catago_6.png">
                    </figure>
                    <figcaption>
                        Thermal
                    </figcaption>
                </a>
            </li>
            <li>
                <a href="#">
                    <figure>
                        <img src="https://eager-elgamal.43-225-53-183.plesk.page/uploads/category/catago_7.png">
                    </figure>
                    <figcaption>
                        Joggers
                    </figcaption>
                </a>
            </li>
            <li>
                <a href="#">
                    <figure>
                        <img src="https://eager-elgamal.43-225-53-183.plesk.page/uploads/category/catago_8.png">
                    </figure>
                    <figcaption>
                        Socks
                    </figcaption>
                </a>
            </li>
        </ul>
    </div>
</section> --}}

<section class="listing-block">
    <div class="container">
        @if (count($data->ProductDetails) > 0)
        <div class="listing-block__meta">
            {{-- <div class="filter">
                <div class="filter__toggle">
                    Filter
                </div>
                <div class="filter__data"></div>
            </div> --}}
            <div class="products mr-3">
                {{-- <h6><span id="prod_count">{{ $data->ProductDetails->count() }}</span> <span id="prod_text">{{ ($data->ProductDetails->count() > 1) ? 'products' : 'product' }}</span> found</h6> --}}
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
                        <form method="GET" action="{{route('front.collection.detail', $data->slug)}}">
                            <div class="col-12 col-sm-12 mb-3 mb-sm-0">
                                <h4>Categories</h4>
                                <ul class="product__filter__bar-list">
                                    @foreach($categories as $categoryKey => $categoryValue)
                                        <li><label><input type="checkbox" name="category[]" value="{{$categoryValue->slug}}" pro-filter="{{$categoryValue->name}}" {{( request()->query('category') == $categoryValue->slug ) ? 'checked' : ''}}><i></i> {{$categoryValue->name}}</label></li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-12 col-sm-12 mb-3 mb-sm-0">
                                <h4>Range</h4>
                                <ul class="product__filter__bar-list">
                                    @foreach($collections as $collectionKey => $collectionValue)
                                        <li><label><input type="checkbox" name="collection" value="{{$collectionValue->slug}}" pro-filter="{{$collectionValue->name}}"><i></i> {{$collectionValue->name}}</label></li>
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
                                        <li><label><input type="checkbox" pro-filter="{{$colorValue->name}}"><i></i> {{ucwords($colorValue->name)}}</label></li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-sm btn-danger">Apply</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="product__holder">
                <div class="row">
                    @forelse($data->ProductDetails as $collectionProductKey => $collectionProductValue)
                    @php if($collectionProductValue->status == 0) {continue;} @endphp
                    <a href="{{ route('front.product.detail', $collectionProductValue->slug) }}" class="product__single" data-events data-cat="tshirt">
                        <figure>
                            <img src="{{asset($collectionProductValue->image)}}" />
                            <h6>{{$collectionProductValue->style_no}}</h6>
                        </figure>
                        <figcaption>
                            <h4>{{$collectionProductValue->name}}</h4>
                            <h5>
                            @if (count($collectionProductValue->colorSize) > 0)
                                @php
                                    $varArray = [];
                                    foreach($collectionProductValue->colorSize as $productVariationKey => $productVariationValue) {
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
                                &#8377;{{$collectionProductValue->offer_price}}
                            @endif
                            </h5>
                            {{-- <h5>
                            &#8377;{{$collectionProductValue->offer_price}} 
                            </h5> --}}
                        </figcaption>
                    </a>
                    @empty
                    
                    @endforelse
                </div>
            </div>
        </div>
        @else
        <p class="mt-5">Sorry, No products found under {{$data->name}} </p>
        @endif
    </div>
</section> 
@endsection

@section('script')
<script>
    function productsFetch() {
        // collection values
        var collectionArr = [];
        $('input[name="collection[]"]:checked').each(function(i){
          collectionArr[i] = $(this).val();
        });

        $.ajax({
            url: '{{route("front.collection.filter")}}',
            method: 'POST',
            data: {
                '_token' : '{{ csrf_token() }}',
                'collectionId' : '{{$data->id}}',
                'orderBy' : $('select[name="orderBy"]').val(),
                'collection' : collectionArr,
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
                                <h6>${value.styleNo}</h6>
                            </figure>
                            <figcaption>
                                <h4>${value.name}</h4>
                                <h5>&#8377;${value.displayPrice}</h5>
                            </figcaption>
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
@endsection