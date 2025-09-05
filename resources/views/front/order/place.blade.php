@extends('layouts.app')

@section('page', 'Home')

@section('content')
<style>
    .color__holder {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 1px solid #000;
    }
    .color__holder.active {
        border: 4px solid #000;
    }
    .customCats-cont.active {
        border-bottom: 1px solid #000;
    }
</style>

@php
    if(!function_exists('in_array_r')) {
        // multi-dimensional in_array
        function in_array_r($needle, $haystack, $strict = false) {
            foreach ($haystack as $item) {
                if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) return true;
            }
            return false;
        }
    }

    // variation colors fetch
    if(!function_exists('variationColors')) {
        function variationColors(int $productId, int $maxColorsToShow) {
            $relatedProductsVariationRAW = \DB::select('SELECT pc.id, pc.position, pc.color AS color_id, c.name as color_name, c.code as color_code, pc.status FROM product_color_sizes pc JOIN colors c ON pc.color = c.id WHERE pc.product_id = '.$productId.' GROUP BY pc.color ORDER BY pc.position ASC');

            $resp = '';

            if (count($relatedProductsVariationRAW) > 0) {
                $resp .= '<div class="color"><ul class="product__color">';

                $usedColros = $activeColros = 1;
                foreach($relatedProductsVariationRAW as $relatedProsVarKey => $relatedProsVarVal) {
                    if($relatedProsVarVal->status == 1) {
                        if($usedColros < $maxColorsToShow + 1) {
                            if ($relatedProsVarVal->color_id == 61) {
                                $resp .= '<li style="background: -webkit-linear-gradient(left,  rgba(219,2,2,1) 0%,rgba(219,2,2,1) 9%,rgba(219,2,2,1) 10%,rgba(254,191,1,1) 10%,rgba(254,191,1,1) 10%,rgba(254,191,1,1) 20%,rgba(1,52,170,1) 20%,rgba(1,52,170,1) 20%,rgba(1,52,170,1) 30%,rgba(15,0,13,1) 30%,rgba(15,0,13,1) 30%,rgba(15,0,13,1) 40%,rgba(239,77,2,1) 40%,rgba(239,77,2,1) 40%,rgba(239,77,2,1) 50%,rgba(254,191,1,1) 50%,rgba(137,137,137,1) 50%,rgba(137,137,137,1) 60%,rgba(254,191,1,1) 60%,rgba(254,191,1,1) 60%,rgba(254,191,1,1) 70%,rgba(189,232,2,1) 70%,rgba(189,232,2,1) 80%,rgba(209,2,160,1) 80%,rgba(209,2,160,1) 90%,rgba(48,45,0,1) 90%);" class="color-holder" data-bs-toggle="tooltip" data-bs-placement="top" title="Assorted"></li>';
                            } else {
                                $resp .= '<li style="background-color: '.$relatedProsVarVal->color_code.'" class="color-holder" data-bs-toggle="tooltip" data-bs-placement="top" title="'.$relatedProsVarVal->color_name.'"></li>';
                            }
                            $usedColros++;
                        }
                        $activeColros++;
                    }
                }

                if ($activeColros > $maxColorsToShow && $usedColros == $maxColorsToShow + 1) $resp .= '<li>+ more</li>';

                $resp .= '</ul></div>';

                return $resp;
            }
        }
    }
@endphp

<div class="col-sm-12">
    <div class="profile-card">
        <h3>Place order to company</h3>

        <section class="storeProducts">
            <div class="storeCatgoryListWrap mx-0">
                <div class="container">
                    <div class="row pt-3">
                        <div class="col-md-7"></div>
                        <div class="col-md-5">
                            <div class="dropdown">
                                <input type="search" class="form-control dropdown-toggle" name="product" value="" placeholder="Search product" data-toggle="dropdown">
                                <div class="respDrop"></div>
                            </div>
                        </div>
                    </div>

                    <nav>
                        <p class="small">Sort products by Range</p>
                        <div class="nav nav-pills" id="nav-tab" role="tablist">
                            @foreach ($collections as $itemKey => $item)
                                <a class="nav-link {{ ($itemKey == 0) ? 'active' : '' }}" id="nav-{{$item->slug}}-tab" data-toggle="tab" href="#nav-{{$item->slug}}" role="tab" aria-controls="nav-{{$item->slug}}" aria-selected="true">{{$item->name}}</a>
                            @endforeach
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        @foreach ($collections as $itemKey => $item)
                            <div class="tab-pane fade {{ ($itemKey == 0) ? 'show active' : '' }}" id="nav-{{$item->slug}}" role="tabpanel" aria-labelledby="nav-{{$item->slug}}-tab">
                                {{-- collection wise categories --}}
                                {{-- <ul class="filter_cat_list mt-3">
                                    @php
                                    if(count($item->ProductDetails) > 0) {
                                        $customCats = [];
                                        foreach ($item->ProductDetails as $ProductDetailsKey => $ProductDetailsValue) {
                                            if($ProductDetailsValue->status == 1) {
                                                if(in_array_r($ProductDetailsValue->cat_id, $customCats)) continue;

                                                if ($ProductDetailsValue->category->status == 1) {
                                                    $customCats[] = [
                                                        'id' => $ProductDetailsValue->cat_id,
                                                        'name' => $ProductDetailsValue->category->name,
                                                        'slug' => $ProductDetailsValue->category->slug,
                                                        'icon' => $ProductDetailsValue->category->icon_path,
                                                    ];
                                                }
                                            }
                                        }
                                    }
                                    @endphp
                                    @foreach ($customCats as $categoryKey => $categoryValue)
                                    <li class="position-relative customCats-cont">
                                        <a href="javascript: void(0)" class="customCats" id="customCat_{{$categoryValue['id']}}" data-id="{{$categoryValue['id']}}" onclick="sortCats('{{ $categoryValue['slug'] }}', '{{$item->slug}}')">
                                            <figure>
                                                <img src="{{ asset($categoryValue['icon']) }}">
                                            </figure>
                                            <figcaption>
                                                {{ $categoryValue['name'] }}
                                            </figcaption>
                                        </a>
                                    </li>
                                    @endforeach
                                    <li class="position-relative customCats-cont active">
                                        <a href="javascript: void(0)" class="customCats" id="customCat_0" data-id="{{$categoryValue['id']}}" onclick="sortCats('all', '{{$item->slug}}')">
                                            <figcaption>
                                                Show all
                                            </figcaption>
                                        </a>
                                    </li>
                                </ul> --}}

                                <ul class="filter_cat_list mt-3">
                                    @php
                                    if(count($item->ProductDetails) > 0) {
                                        $customCats = [];
                                        foreach ($item->ProductDetails as $ProductDetailsKey => $ProductDetailsValue) {
                                            if($ProductDetailsValue->status == 1) {
                                                if(in_array_r($ProductDetailsValue->cat_id, $customCats)) continue;
        
                                                if ($ProductDetailsValue->category->status == 1) {
                                                    $customCats[] = [
                                                        'id' => $ProductDetailsValue->cat_id,
                                                        'name' => $ProductDetailsValue->category->name,
                                                        'slug' => $ProductDetailsValue->category->slug,
                                                        'icon' => $ProductDetailsValue->category->icon_path,
                                                    ];
                                                }
                                            }
                                        }
                                    }
                                    @endphp
                                    @foreach ($customCats as $categoryKey => $categoryValue)
                                    <li class="position-relative customCats-cont">
                                        <a href="javascript: void(0)" class="customCats" id="customCat_{{$categoryValue['id']}}" data-id="{{$categoryValue['id']}}" onclick="sortCats('{{ $categoryValue['slug'] }}', '{{$item->slug}}')">
                                            <figure>
                                                <img src="{{ asset($categoryValue['icon']) }}">
                                            </figure>
                                            <figcaption>
                                                {{ $categoryValue['name'] }}
                                            </figcaption>
                                        </a>
                                    </li>
                                    @endforeach
                                    <li class="position-relative customCats-cont active">
                                        <a href="javascript: void(0)" class="customCats" id="customCat_0" data-id="{{$categoryValue['id']}}" onclick="sortCats('all', '{{$item->slug}}')">
                                            {{-- <figure>
                                                <img src="{{ asset($categoryValue['icon']) }}">
                                            </figure> --}}
                                            <figcaption>
                                                Show all
                                            </figcaption>
                                        </a>
                                    </li>
                                </ul>

                                {{-- collection wise products --}}
                                <div class="row" id="products__cont_{{$item->slug}}">
                                    @forelse($item->ProductDetails as $collectionProductKey => $categoryProductValue)
                                    @php if($categoryProductValue->status == 0) {continue;} @endphp

                                    {{-- <div class="col-sm-6 col-md-6 col-lg-4"> --}}
                                    <div class="col-sm-6 col-md-6 col-lg-4 storeProduct_col product_{{ $categoryProductValue->category->slug }}_{{$item->slug}}">
                                        <div class="storeProduct_card">
                                            <div class="storeProduct_card_body">
                                                @php
                                                    if (request()->input('type') == 'order-on-call') {
                                                        $type = 'order-on-call';
                                                    } else {
                                                        $type = 'store-visit';
                                                    }
                                                @endphp

                                                <a href="javascript: void(0)" data-toggle="modal" class="product__single" data-events data-cat="tshirt" onclick="proDetail('{{$categoryProductValue->slug}}')">
                                                    <figure class="storeProduct_card_img">
                                                        <img src="{{asset($categoryProductValue->collection->sketch_icon)}}" class="" />
                                                    </figure>
                                                    <figcaption>
                                                        <h4>{{$categoryProductValue->name}}</h4>
                                                        <h5>Style # {{$categoryProductValue->style_no}}</h5>
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
                </div>
            </div>
        </section>

    </div>
</div>

{{-- product detail modal --}}
<div id="productDetailsModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        // product search
        $('input[name="product"]').on('keyup', function() {
            var $this = 'input[name="product"]'
            var type = '{{$type}}';

            if ($($this).val().length > 0) {
                $.ajax({
                    url: '{{route("front.product.search")}}',
                    method: 'post',
                    data: {
                        '_token': '{{csrf_token()}}',
                        name: $($this).val(),
                        type: type,
                    },
                    success: function(result) {
                        var content = '';
                        if (result.error === false) {
                            content += `<div class="dropdown-menu show w-100 postcode-dropdown" aria-labelledby="dropdownMenuButton">`;

                            $.each(result.data, (key, value) => {
                                // content += `<a class="dropdown-item" href="${value.route}">${value.name} (${value.style_no})</a>`;

                                content += `<a class="dropdown-item" href="javascript: void(0)" onclick="proDetail('${value.slug}')">
                                    <div class="d-flex">
                                        <img src="{{asset('img/product-box.png')}}" class="mr-3" height="30">
                                        <p class="mb-0">${value.name} (${value.style_no})</p>
                                    </div>
                                </a>`;
                            })
                            content += `</div>`;
                        } else {
                            content += `<div class="dropdown-menu show w-100 postcode-dropdown" aria-labelledby="dropdownMenuButton"><li class="dropdown-item">${result.message}</li></div>`;
                        }
                        $('.respDrop').html(content);
                    }
                });
            } else {
                $('.respDrop').text('');
            }
        });

        // product detail fetch
        function proDetail(productSlug, storeId, storeType) {
            $.ajax({
                url : '{{route("front.product.detail.ajax")}}',
                method : 'POST',
                data : {
                    _token: '{{csrf_token()}}',
                    productSlug: productSlug,
                    storeId: storeId,
                    storeType: storeType,
                },
                success: function(result) {
                    // console.log(result.response.data.collection_id);

                    var content = `
                    <div class="row">
                        <div class="col-lg-4 col-md-5">
                            <img src="{{asset('img/product-box.png')}}" class="mb-3">
                        </div>
                        <div class="col-lg-8 col-md-7">
                            <div class="productDetails_info pupupProductDetails_info">
                                <div class="badgeId"><span># ${result.response.data.style_no}</span></div>
                                <h2>${result.response.data.name}</h2>
                                <p class="mb-4">${result.response.data.short_desc}</p>
                            </div>
                            <p>Master pack : <strong>${result.response.data.master_pack}</strong></p>
                        </div>
                    </div>

                    <div class="w-100">
                        <form action="{{ route('front.cart.add.bulk') }}" method="POST">@csrf
                            <h5>Select colors</h5>
                            <ul class="pl-0 pb-3 colors-container" style="list-style: none;">`;

                            $.each(result.response.primaryColorSizes[0].colors, function(colorsKey, colorsValue) {
                                let colorActive = (colorsKey == 0) ? "active" : "";

                                if (colorsValue.id == 61) {
                                    colorContent = `style="background: -webkit-linear-gradient(left,  rgba(219,2,2,1) 0%,rgba(219,2,2,1) 9%,rgba(219,2,2,1) 10%,rgba(254,191,1,1) 10%,rgba(254,191,1,1) 10%,rgba(254,191,1,1) 20%,rgba(1,52,170,1) 20%,rgba(1,52,170,1) 20%,rgba(1,52,170,1) 30%,rgba(15,0,13,1) 30%,rgba(15,0,13,1) 30%,rgba(15,0,13,1) 40%,rgba(239,77,2,1) 40%,rgba(239,77,2,1) 40%,rgba(239,77,2,1) 50%,rgba(254,191,1,1) 50%,rgba(137,137,137,1) 50%,rgba(137,137,137,1) 60%,rgba(254,191,1,1) 60%,rgba(254,191,1,1) 60%,rgba(254,191,1,1) 70%,rgba(189,232,2,1) 70%,rgba(189,232,2,1) 80%,rgba(209,2,160,1) 80%,rgba(209,2,160,1) 90%,rgba(48,45,0,1) 90%); "`;
                                } else {
                                    colorContent = `style="background-color: ${colorsValue.code}"`;
                                }

                                content += `
                                <li class="mr-3 single-color">
                                    <a href="javascript: void(0)">
                                        <div class="color__holder ${colorActive}" ${colorContent} data-toggle="tooltip" title="${colorsValue.name}" onclick="sizeCheck(${result.response.data.id}, ${colorsValue.id}, '${colorsValue.name}')"></div>
                                    </a>
                                    ${colorsValue.color_name}
                                </li>
                                `;
                            });

                        content += `</ul>
                            <h5 class="mb-3">Select size for <span id="colorName">${result.response.primaryColorSizes[0].colors[0].name}</span> </h5>
                            <div class="row sizeBoxWrap" id="sizeLoad">`;

                            $.each(result.response.primaryColorSizes[0].primarySizes, function(sizesKey, sizesValue) {
                                content += `
                                <div class="col-xl-6 col-lg-8pu col-6">
                                    <div class="sizeBox">
                                        <div class="productSize">
                                            <span>
                                                ${sizesValue.name}
                                            </span>
                                            <p>
                                                ${sizesValue.size_details}
                                            </p>
                                        </div>
                                        <div class="productPrice
                                        @if(auth()->guard('web')->user()->user_type == 4)
                                        d-none
                                        @endif
                                        ">
                                            Rs. ${sizesValue.offer_price}
                                        </div>
                                        <div class="prductQuantity">
                                            <input min="0" type="number" class="qqttyy" name="qty[]" id="" value="0" onkeyup="testFunc(this.value)">
                                        </div>
                                        <input type="hidden" name="size[]" value="${sizesValue.name}">
                                        <input type="hidden" name="price[]" value="${sizesValue.offer_price}">
                                    </div>
                                </div>
                                `;
                            })

                        content += `</div>
                            <div class="row">
                                <div class="col-xl-4 col-lg-6 col-md-6 col-6">
                                    <input type="hidden" name="store_id" value="${storeId}">
                                    <input type="hidden" name="order_type" value="${storeType}">
                                    <input type="hidden" name="user_id" value="{{Auth::guard('web')->user()->id}}">
                                    <input type="hidden" name="product_id" value="${result.response.data.id}">
                                    <input type="hidden" name="product_name" value="${result.response.data.name}">
                                    <input type="hidden" name="product_style_no" value="${result.response.data.style_no}">
                                    <input type="hidden" name="product_slug" value="${result.response.data.slug}">
                                    <input type="hidden" name="master_pack_count" value="${result.response.data.master_pack_count}">
                                    <input type="hidden" name="color" value="${result.response.primaryColorSizes[0].colors[0].id}">

                                    <button type="submit" class="btn btn-block addtocartBtn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                                        Add to cart
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    `;

                    $('#productDetailsModal .modal-title').text(result.response.data.name);
                    $('#productDetailsModal .modal-body').html(content);
                    $('#productDetailsModal').modal('show');
                }
            });
        }
        /* function proDetail(productSlug) {
            $.ajax({
                url : '{{route("front.product.detail.ajax")}}',
                method : 'POST',
                data : {
                    _token: '{{csrf_token()}}',
                    productSlug: productSlug
                },
                success: function(result) {
                    // console.log(result.response.primaryColorSizes[0].colors);

                    var content = `
                    <div class="row">
                        <div class="col-lg-4 col-md-5">
                            <img src="{{asset('img/product-box.png')}}" class="mb-3">
                        </div>
                        <div class="col-lg-8 col-md-7">
                            <div class="productDetails_info pupupProductDetails_info">
                                <div class="badgeId"><span># ${result.response.data.style_no}</span></div>
                                <h2>${result.response.data.name}</h2>
                                <p class="mb-4">${result.response.data.short_desc}</p>
                            </div>
                        </div>
                    </div>

                    <div class="w-100">
                        <form action="{{ route('front.cart.add.bulk.distributor') }}" method="POST">@csrf
                            <h5>Select colors</h5>
                            <ul class="pl-0 pb-3 colors-container" style="list-style: none;">`;

                            $.each(result.response.primaryColorSizes[0].colors, function(colorsKey, colorsValue) {
                                let colorActive = (colorsKey == 0) ? "active" : "";

                                if (colorsValue.id == 61) {
                                    colorContent = `style="background: -webkit-linear-gradient(left,  rgba(219,2,2,1) 0%,rgba(219,2,2,1) 9%,rgba(219,2,2,1) 10%,rgba(254,191,1,1) 10%,rgba(254,191,1,1) 10%,rgba(254,191,1,1) 20%,rgba(1,52,170,1) 20%,rgba(1,52,170,1) 20%,rgba(1,52,170,1) 30%,rgba(15,0,13,1) 30%,rgba(15,0,13,1) 30%,rgba(15,0,13,1) 40%,rgba(239,77,2,1) 40%,rgba(239,77,2,1) 40%,rgba(239,77,2,1) 50%,rgba(254,191,1,1) 50%,rgba(137,137,137,1) 50%,rgba(137,137,137,1) 60%,rgba(254,191,1,1) 60%,rgba(254,191,1,1) 60%,rgba(254,191,1,1) 70%,rgba(189,232,2,1) 70%,rgba(189,232,2,1) 80%,rgba(209,2,160,1) 80%,rgba(209,2,160,1) 90%,rgba(48,45,0,1) 90%); "`;
                                } else {
                                    colorContent = `style="background-color: ${colorsValue.code}"`;
                                }

                                content += `
                                <li class="mr-3 single-color"><a href="javascript: void(0)">
                                    <div class="color__holder ${colorActive}" ${colorContent} data-toggle="tooltip" title="${colorsValue.name}" onclick="sizeCheck(${result.response.data.id}, ${colorsValue.id}, '${colorsValue.name}')"></div>
                                </a></li>
                                `;
                            });

                        content += `</ul>
                            <h5 class="mb-3">Select size for <span id="colorName">${result.response.primaryColorSizes[0].colors[0].name}</span> </h5>
                            <div class="row sizeBoxWrap" id="sizeLoad">`;

                            $.each(result.response.primaryColorSizes[0].primarySizes, function(sizesKey, sizesValue) {
                                content += `
                                <div class="col-xl-6 col-lg-8pu col-6">
                                    <div class="sizeBox">
                                        <div class="productSize">
                                            <span>
                                                ${sizesValue.name}
                                            </span>
                                        </div>
                                        <div class="productPrice">
                                            Rs. ${sizesValue.offer_price}
                                        </div>
                                        <div class="prductQuantity">
                                            <input min="0" type="number" name="qty[]" id="" value="0">
                                        </div>
                                        <input type="hidden" name="size[]" value="${sizesValue.name}">
                                        <input type="hidden" name="price[]" value="${sizesValue.offer_price}">
                                    </div>
                                </div>
                                `;
                            })

                        content += `</div>
                            <div class="row">
                                <div class="col-xl-4 col-lg-6 col-md-6 col-6">
                                    <input type="hidden" name="store_id" value="1">
                                    <input type="hidden" name="order_type" value="1">
                                    <input type="hidden" name="user_id" value="{{Auth::guard('web')->user()->id}}">
                                    <input type="hidden" name="product_id" value="${result.response.data.id}">
                                    <input type="hidden" name="product_name" value="${result.response.data.name}">
                                    <input type="hidden" name="product_style_no" value="${result.response.data.style_no}">
                                    <input type="hidden" name="product_slug" value="${result.response.data.slug}">
                                    <input type="hidden" name="color" value="${result.response.primaryColorSizes[0].colors[0].id}">

                                    <button type="submit" class="btn btn-block addtocartBtn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                                        Add to cart
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    `;

                    $('#productDetailsModal .modal-title').text(result.response.data.name);
                    $('#productDetailsModal .modal-body').html(content);
                    $('#productDetailsModal').modal('show');
                }
            });
        } */

        function sizeCheck(productId, colorId, colorName) {
            $.ajax({
                url : '{{route("front.product.size")}}',
                method : 'POST',
                data : {'_token' : '{{csrf_token()}}', productId : productId, colorId : colorId},
                beforeSend: function() {
                    $loadingSwal = Swal.fire({
                        title: 'Please wait...',
                        text: 'We are fetching your details!',
                        showConfirmButton: false,
                        // allowOutsideClick: false
                        // timer: 1500
                    })
                },
                success : function(result) {
                    if (result.error === false) {
                        $loadingSwal.close();

                        $('#colorName').text(colorName);
                        $('input[name="color"]').val(colorId);
                        var content = '';

                        $.each(result.data, (key, val) => {
                            content += `
                            <div class="col-xl-6 col-lg-8pu col-6">
                                <div class="sizeBox">
                                    <div class="productSize">
                                        <span>
                                            ${val.sizeName}
                                        </span>
                                    </div>
                                    <div class="productPrice">
                                        Rs. ${val.offerPrice}
                                    </div>
                                    <div class="prductQuantity">
                                        <input min="0" type="number" name="qty[]" id="" value="0">
                                    </div>
                                    <input type="hidden" name="size[]" value="${val.sizeName}">
                                    <input type="hidden" name="price[]" value="${val.offerPrice}">
                                </div>
                            </div>
                            `;
                        })
                        $('#sizeLoad').html(content);
                    } else {
                        $loadingSwal.close();

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

        // sort products by categories
        function sortCats(cat, range) {
            if (cat == 'all') {
                $('#products__cont_'+range+' .storeProduct_col').show();
            } else {
                $('#products__cont_'+range+' .storeProduct_col').hide();
                $('#products__cont_'+range+' .product_'+cat+'_'+range).show();
            }
        }
    </script>
@endsection
