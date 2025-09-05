@extends('front.layout.app')
@section('page-title', 'Product Details')
@section('content')

<style>
    .strike {
        text-decoration: line-through;
    }
    .sale-price {
        color: #E91E63; /* any highlight color */
        font-weight: bold;
    }
</style>

<section class="main">
    <div class="container">
        <ul class="breadcrumb breadcrumb-white mt-4">
            <li><a href="{{ route('front.home') }}">Home</a></li>
            <li>{{ $data->name }}</li>
        </ul>
    </div>
</section>

<section class="details-body">
    <div class="container">
        <div class="row">
            {{-- Product Images --}}
            <div class="col-lg-6 mb-4 mb-md-5 mb-lg-0">
                <div class="gallery-place">

                    {{-- Big slider --}}
                    <div class="slider-big swiper">
                        <div class="swiper-wrapper" id="default-big-slider">
                            {{-- @if($images && count($images))
                                @foreach($images as $image)
                                    <div class="swiper-slide">
                                        <div class="single-image-big">
                                            <img src="{{ asset($image->image_path) }}" alt="{{ $data->name }}">
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="swiper-slide">
                                    <div class="single-image-big">
                                        <img src="{{ asset('assets/images/placeholder-product.jpg') }}" alt="{{ $data->name }}">
                                    </div>
                                </div>
                            @endif --}}
                            <div class="swiper-slide">
                                <div class="single-image-big">
                                    <img src="{{ asset('assets/images/placeholder-product.jpg') }}" alt="{{ $data->name }}">
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- Thumb slider --}}
                    <div thumbsSlider="" class="slider-thumb swiper">
                        <div class="swiper-wrapper" id="default-thumb-slider">
                            {{-- @if($images && count($images))
                                @foreach($images as $image)
                                    <div class="swiper-slide">
                                        <div class="single-image-thumb">
                                            <img src="{{ asset($image->image_path) }}" alt="{{ $data->name }}">
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="swiper-slide">
                                    <div class="single-image-thumb">
                                        <img src="{{ asset('assets/images/placeholder-product.jpg') }}" alt="{{ $data->name }}">
                                    </div>
                                </div>
                            @endif --}}
                            <div class="swiper-slide">
                                <div class="single-image-thumb">
                                    <img src="{{ asset('assets/images/placeholder-product.jpg') }}" alt="{{ $data->name }}">
                                </div>
                            </div>
                        </div>
                        <div class="swiper-thumb-button-next"></div>
                        <div class="swiper-thumb-button-prev"></div>
                    </div>
                </div>
            </div>


            {{-- Product Details --}}
            <div class="col-lg-6">
                <div class="details-conetnt">
                    <h1>{{ ucwords($data->name) }}</h1>

                    <div class="description">
                        {{ ucfirst(html_entity_decode(strip_tags($data->short_desc))) }}
                    </div>

                    <form action="{{ route('front.cart.add') }}" method="POST" id="addToCartForm">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $data->id }}">
                        @if (isset($productVariations[0]))
                            <input type="hidden" name="variation_id" id="selectedVariation" value="{{ $productVariations[0]->id }}">
                        @else
                            <input type="hidden" name="variation_id" id="selectedVariation" value="">
                        @endif

                        <div class="price" id="price-section">
                            @if($data->offer_price > 0 && $data->offer_price < $data->price)
                                <span class="original-price strike">₹{{ number_format($data->price, 2) }}</span>
                                <span class="sale-price">₹{{ number_format($data->offer_price, 2) }}</span>
                            @else
                                {{-- ₹{{ number_format($data->price, 2) }} --}}
                                <span class="single-price">₹{{ number_format($data->price, 2) }}</span>
                            @endif
                        </div>

                        @if(isset($productVariations) && $productVariations->count() > 0)
                            <div class="variation-list">
                                @foreach ($productVariations as $key => $variation)
                                    <label>
                                        {{ $variation->weight }}
                                        <input type="radio"
                                            name="variation"
                                            value="{{ $variation->id }}"
                                            data-price="{{ $variation->price }}"
                                            data-offer-price="{{ $variation->offer_price }}"
                                            data-images="@json($variation->images)"
                                            {{ $key == 0 ? 'checked' : '' }}>
                                        <span></span>
                                    </label>
                                @endforeach
                            </div>
                        @endif

                        <div class="quantity-group">
                            <div class="number-input">
                                <button type="button" class="decrement">-</button>
                                <input type="number" class="quantity" name="quantity" min="1" max="10" value="1" step="1" readonly>
                                <button type="button" class="increment">+</button>
                            </div>
                            <input type="submit" class="bton btn-fill" value="Add to Cart">
                        </div>
                    </form>
                    
                    <div class="description">
                        {{ ucfirst(html_entity_decode(strip_tags($data->desc))) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Related Products --}}
        @if(isset($relatedProducts) && $relatedProducts->count())
            <div class="related-product-stack">
                <div class="heading-group">
                    <h2 class="section-heading">Related Products</h2>

                    <div class="navi-slide">
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>

                <div class="relared-pro-stack swiper">
                    <div class="swiper-wrapper">
                        @foreach($relatedProducts as $related)
                            <div class="swiper-slide">
                                <div class="pro-inner">
                                    <figure>
                                        <a href="{{ route('front.shop.detail', $related->slug) }}">
                                            <img src="{{ $related->image ? asset($related->image) : asset('assets/images/placeholder-product.jpg') }}" alt="{{ $related->name }}">
                                        </a>
                                    </figure>
                                    <figcaption>
                                        <a href="{{ route('front.shop.detail', $related->slug) }}">
                                            <h3>{{ $related->name }}</h3>
                                        </a>
                                        <div class="price-group">
                                            @if($related->offer_price > 0 && $related->offer_price < $related->price)
                                                <span class="original-price strike">₹{{ number_format($related->price, 2) }}</span>
                                                <span class="sale-price">₹{{ number_format($related->offer_price, 2) }}</span>
                                                <div class="sale-persentage">
                                                    {{ round((($related->price - $related->offer_price) / $related->price) * 100) }}% save
                                                </div>
                                                <div class="sale-badge">Sale</div>
                                            @else
                                                <span class="original-price">₹{{ number_format($related->price, 2) }}</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('front.shop.detail', $related->slug) }}" class="bton btn-fill">Shop Now</a>
                                    </figcaption>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

    </div>
</section>


@endsection

@section('script')

  <script>
  $( function() {
        // const rangeInput = document.querySelectorAll(".range-input input"),
        // priceInput = document.querySelectorAll(".price-input input"),
        // range = document.querySelector(".slider .progress");
        // let priceGap = 1000;

        // priceInput.forEach((input) => {
        // input.addEventListener("input", (e) => {
        //     let minPrice = parseInt(priceInput[0].value),
        //     maxPrice = parseInt(priceInput[1].value);

        //     if (maxPrice - minPrice >= priceGap && maxPrice <= rangeInput[1].max) {
        //     if (e.target.className === "input-min") {
        //         rangeInput[0].value = minPrice;
        //         range.style.left = (minPrice / rangeInput[0].max) * 100 + "%";
        //     } else {
        //         rangeInput[1].value = maxPrice;
        //         range.style.right = 100 - (maxPrice / rangeInput[1].max) * 100 + "%";
        //     }
        //     }
        // });
        // });

        // rangeInput.forEach((input) => {
        // input.addEventListener("input", (e) => {
        //     let minVal = parseInt(rangeInput[0].value),
        //     maxVal = parseInt(rangeInput[1].value);

        //     if (maxVal - minVal < priceGap) {
        //     if (e.target.className === "range-min") {
        //         rangeInput[0].value = maxVal - priceGap;
        //     } else {
        //         rangeInput[1].value = minVal + priceGap;
        //     }
        //     } else {
        //     priceInput[0].value = minVal;
        //     priceInput[1].value = maxVal;
        //     range.style.left = (minVal / rangeInput[0].max) * 100 + "%";
        //     range.style.right = 100 - (maxVal / rangeInput[1].max) * 100 + "%";
        //     }
        // });
        // });
    });

    // quantity jquery
    document.addEventListener("DOMContentLoaded", () => {
        const input = document.getElementById("quantity");
        document.querySelector(".increment").addEventListener("click", (e) => {
            e.preventDefault(); // Prevent form submission
            input.stepUp();
        });
        document.querySelector(".decrement").addEventListener("click", (e) => {
            e.preventDefault(); // Prevent form submission
            input.stepDown();
        });
    });

    let swiperBig;
    let swiperThumb;

    function initSwipers() {
        swiperThumb = new Swiper(".slider-thumb", {
            loop: false,
            spaceBetween: 10,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesProgress: true,
            navigation: {
                nextEl: ".swiper-thumb-button-next",
                prevEl: ".swiper-thumb-button-prev",
            },
        });

        swiperBig = new Swiper(".slider-big", {
            loop: false,
            spaceBetween: 10,
            thumbs: {
                swiper: swiperThumb,
            },
        });
    }

    $(document).ready(function () {

        // variation price + image change
        $('input[name="variation"]').on('change', function () {
            let price = parseFloat($(this).data('price')).toFixed(2);
            let offerPrice = parseFloat($(this).data('offer-price')).toFixed(2);

            let priceHtml = '';
            if (offerPrice > 0 && offerPrice < price) {
                priceHtml = `
                    <span class="original-price strike">₹${price}</span>
                    <span class="sale-price">₹${offerPrice}</span>
                `;
            } else {
                priceHtml = `<span class="single-price">₹${price}</span>`;
            }

            $('#price-section').html(priceHtml);

            let variationId = $(this).val();

            $.ajax({
                url: "{{ route('front.shop.variation-images') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    variation_id: variationId
                },
                success: function (response) {
                    if (response.status) {
                        let bigSlider = $('.slider-big .swiper-wrapper');
                        let thumbSlider = $('.slider-thumb .swiper-wrapper');

                        if (swiperBig) swiperBig.destroy(true, true);
                        if (swiperThumb) swiperThumb.destroy(true, true);

                        bigSlider.empty();
                        thumbSlider.empty();

                        if (response.images.length > 0) {
                            response.images.forEach(function (img) {
                                bigSlider.append(`
                                    <div class="swiper-slide">
                                        <div class="single-image-big">
                                            <img src="${img}" alt="">
                                        </div>
                                    </div>
                                `);
                                thumbSlider.append(`
                                    <div class="swiper-slide">
                                        <div class="single-image-thumb">
                                            <img src="${img}" alt="">
                                        </div>
                                    </div>
                                `);
                            });
                        } else {
                            let placeholder = "{{ asset('assets/images/placeholder-product.jpg') }}";
                            bigSlider.append(`
                                <div class="swiper-slide">
                                    <div class="single-image-big">
                                        <img src="${placeholder}" alt="">
                                    </div>
                                </div>
                            `);
                            thumbSlider.append(`
                                <div class="swiper-slide">
                                    <div class="single-image-thumb">
                                        <img src="${placeholder}" alt="">
                                    </div>
                                </div>
                            `);
                        }
                        initSwipers();
                    }
                }
            });
        });
        
        let defaultVariation = $('input[name="variation"]:checked');
        if (defaultVariation.length > 0) {
            defaultVariation.trigger('change');
        }
    });

    $(document).on('change', 'input[name="variation"]', function () {
        const selectedVariationId = $(this).val();
        $('#selectedVariation').val(selectedVariationId);
    });

    $(document).on('click', '.increment', function (e) {
        e.preventDefault();
        let qtyInput = $(this).siblings('.quantity');
        let currentQty = parseInt(qtyInput.val()) || 1;
        qtyInput.val(currentQty + 1);
    });

    $(document).on('click', '.decrement', function (e) {
        e.preventDefault();
        let qtyInput = $(this).siblings('.quantity');
        let currentQty = parseInt(qtyInput.val()) || 1;
        if (currentQty > 1) {
            qtyInput.val(currentQty - 1);
        }
    });

  </script>

@endsection






























{{-- @extends('front.layout.app')
   @section('content')
   <style>
    .product_details_wishlist.active {
    background-color: red;
   
}
</style>
    <section class="product_details_sec">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff"
                        class="swiper wallet_swiper2">
                        <div class="swiper-wrapper" id="color_wise_image">
                        @if($primaryColorSizes)
                            @foreach($primaryColorSizes['images'] as $item)
                            <div class="swiper-slide">
                                <img src="{{$base_url.'/'.$item->image}}" alt="">
                            </div>
                            @endforeach
                        @endif   
                        </div>
                    </div>
                    <div thumbsSlider="" class="swiper wallet_swiper">
                        <div class="swiper-wrapper" id="color_wise_slider_images">
                        @if($primaryColorSizes)
                            @foreach($primaryColorSizes['images'] as $item)
                            <div class="swiper-slide">
                               <div class="product_d_slider_img">
                                    <img src="{{$base_url.'/'.$item->image}}" alt="">
                                </div>
                            </div>
                            @endforeach
                        @endif 
                      
                        </div>

                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>


                </div>
                <div class="col-lg-6">
                    <div class="product_details_text">
                        <h3>{{$data->name}}</h3>
                        <span class="sku">#{{strtoupper($data->style_no)}}</span>
                        <!--  -->
                        
                        <div class="product_details_amoutn">
                            <div class="product_span_amoutn">
                                @php
                                    $price = count($primaryColorSizes['sizes'])>0?$primaryColorSizes['sizes'][0]->price:0;
                                    $offer_price = count($primaryColorSizes['sizes'])?$primaryColorSizes['sizes'][0]->offer_price:0;
                                    $discount = (($price - $offer_price) / $price) * 100;
                                @endphp
                                 @if($offer_price>0 && $price>0)
                                     @if($price != $offer_price)
                                    <div id="price_module"><h4>₹{{$offer_price}}<span>₹{{$price}}</span>@if($discount > 0)<span class="discount-tag">({{ (int) $discount }}%  off)</span> @endif</h4></div>
                                    @else
                                    <div id="price_module"><h4>₹{{$price}}</h4></div>
                                    @endif
                                @else
                                <h4>₹{{$price}}</h4>
                                @endif
                            </div>
                            @php
                                $active_wishhList = active_wishhList($data->id);
                            @endphp
                            <a href="{{route('front.wishlist.add',$data->id)}}" class="product_details_wishlist {{$active_wishhList?'active':''}}">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M17.3666 3.84319C16.941 3.41736 16.4356 3.07956 15.8794 2.84909C15.3232 2.61862 14.727 2.5 14.1249 2.5C13.5229 2.5 12.9267 2.61862 12.3705 2.84909C11.8143 3.07956 11.3089 3.41736 10.8833 3.84319L9.99994 4.72652L9.1166 3.84319C8.25686 2.98344 7.0908 2.50045 5.87494 2.50045C4.65908 2.50045 3.49301 2.98344 2.63327 3.84319C1.77353 4.70293 1.29053 5.86899 1.29053 7.08485C1.29053 8.30072 1.77353 9.46678 2.63327 10.3265L3.5166 11.2099L9.99994 17.6932L16.4833 11.2099L17.3666 10.3265C17.7924 9.90089 18.1302 9.39553 18.3607 8.83932C18.5912 8.2831 18.7098 7.68693 18.7098 7.08485C18.7098 6.48278 18.5912 5.88661 18.3607 5.33039C18.1302 4.77418 17.7924 4.26882 17.3666 3.84319Z"
                                        stroke="black" stroke-width="1.2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                <span class="product_d_wishlist">Wish list</span>
                            </a>
                        </div>
                        <form action="{{route('front.product.add.to.cart')}}" method="post" id="myForm">
                            <div class="product_color">
                                @csrf
                                <div class="product_info_form">
                                    <label for="#" class="form_colo_label">AVAILABLE COLOUR</label>
                                    <div class="product_info_form_color">
                                        @if($availableColor)
                                        @foreach($availableColor as $itemKey =>$color)
                                        <input style="background-color: {{$color->code}};" class="form-check-input form_color_input" type="radio" name="choose_color" id="inline_Radio{{$color->id}}" value="{{$color->id}}" onclick="getColorId('{{$color->id}}','{{$data->id}}')" {{ $selectedColorId == $color->id ? 'checked' : '' }} {{$itemKey==0?"checked":""}}>
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="product_size_info">
                                    <label for="#" class="form_colo_label">AVAILABLE SIZE</label>
                                    @if($primaryColorSizes)
                                     <div id="form_product_size">
                                        @foreach($primaryColorSizes['sizes'] as $k=> $size)
                                            <div class="form-check form-check-inline form_product_size" onclick="form_color_input_size('{{$size->price}}', '{{$size->offer_price}}', '{{$size->id}}')">
                                                <input class="form-check-input form_color_input_size" type="radio"
                                                    name="size_name" id="inlineRadio{{$k+1}}" value="{{$size->size}}" {{$k==0?"checked":""}}>
                                                    <label for="inlineRadio{{$k+1}}">{{$size->size_name}}</label>
                                            </div>
                                        @endforeach
                                     </div>
                                    @endif
                                </div>
                            </div>
                            <div class="product_counter">
                                <div class="product-1">
                                    <input type="hidden"  name="productId" value="{{$data->id}}">
                                    <input type="hidden"  name="productName" value="{{$data->name}}">
                                    <input type="hidden"  name="productStyleNo" value="{{$data->style_no}}">
                                    <input type="hidden"  name="productImage" value="{{$data->image}}">
                                    <input type="hidden"  name="productSlug" value="{{$data->slug}}">
                                    <input type="hidden" id="variationId"  name="variationId" value="{{ count($primaryColorSizes['sizes'])>0 ? $primaryColorSizes['sizes'][0]->id : '' }}">
                                    <input type="hidden" id="price"  name="price" value="{{ count($primaryColorSizes['sizes'])>0 ? $primaryColorSizes['sizes'][0]->price : '' }}">
                                    <input type="hidden" id="offer_price"  name="offer_price" value="{{ count($primaryColorSizes['sizes'])>0 ? $primaryColorSizes['sizes'][0]->offer_price : '' }}">
                                    <div class="quantity-row">
                                        <button type="button"   class="product_counter_btn product_counter1">-</button>
                                        <input  type="text" class="input_text"  id="quantity" name="quantity"  value="1" min="1" />
                                        <button type="button" class="product_counter_btn product_counter2">+</button>
                                    </div>
                                    <button type="submit" href="#" data-id="1" data-quantity="1" class="add-to-cart ajax product-1" id="checkout_button">Add to
                                        Cart</button>
                                           
                                </div>
                            </div>
                        </form>
                        <p id="Error_show" class="text-danger test-sm"></p>
                       <h4 class="product_details_h4">Product Details</h4>
                        @if ($data->short_desc)
                        <div class="product_detail_desc">{!! $data->short_desc !!}</div>
                        @else
                        <div class="product_detail_desc">{!! $data->desc !!}</div>
                        @endif
                        <div class="product_info">
                            @if($data->fabric)
                            <h6>Fabric:<span>{{$data->fabric}}</span></h6>
                            @endif
                            @if($data->wash_care)
                            <h6>Wash Care:<span>{!!$data->wash_care!!}</span></h6>
                            @endif
                            @if($data->pattern)
                            <h6>Pattern:<span>{{$data->pattern}}</span></h6>
                            @endif
                        </div>
                        <h5>*No returns or exchanges permitted. <a class="term_c" href="{{route('front.terms.conditions')}}">T&C apply</a></h5>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="product_listing_sec">
        <div class="container">
            <div class="product_listing_text">
                <h3>You may also like</h3>
            </div>
            <div class="row">
                @if($categoryWiseProducts)
                @foreach($categoryWiseProducts as $product)
                <div class="col-lg-3 col-md-4 col-6 mb-3">
                        <div class="swiper_slide_deal_product_p">
                            <div class="swiper_slide_deal_product">
                                <figure class="deal_img">
                                    <a href="{{route('front.product.details',$product->slug)}}" class="deal_img_anch">
                                        <img src="{{$base_url.'/'.$product->image}}" alt="">
                                    </a>
                                    
                                    @php
                                        $active_wishhList = active_wishhList($product->id);
                                    @endphp
                                    <a href="{{route('front.wishlist.add',$product->id)}}" class="product_details_wishlist blue_heart {{$active_wishhList?'active':''}}">
                                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M26.05 5.76088C25.4116 5.12213 24.6535 4.61543 23.8192 4.26973C22.9849 3.92403 22.0906 3.74609 21.1875 3.74609C20.2844 3.74609 19.3902 3.92403 18.5558 4.26973C17.7215 4.61543 16.9635 5.12213 16.325 5.76088L15 7.08588L13.675 5.76088C12.3854 4.47126 10.6363 3.74676 8.81253 3.74676C6.98874 3.74676 5.23964 4.47126 3.95003 5.76088C2.66041 7.05049 1.93591 8.79958 1.93591 10.6234C1.93591 12.4472 2.66041 14.1963 3.95003 15.4859L5.27503 16.8109L15 26.5359L24.725 16.8109L26.05 15.4859C26.6888 14.8474 27.1955 14.0894 27.5412 13.2551C27.8869 12.4207 28.0648 11.5265 28.0648 10.6234C28.0648 9.72027 27.8869 8.82601 27.5412 7.99168C27.1955 7.15736 26.6888 6.39932 26.05 5.76088Z"
                                                stroke="#631096" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </a>
                                </figure>
                                <div class="swiper_deal_text">
                                    <a href="{{route('front.product.details',$product->slug)}}" class="product_text">
                                        <h4>{{ Str::limit($product->name,50)}}</h4>
                                    </a>
                                    @php
                                    $discount_percentage = 0;
                                    @endphp
                                    
                                    <div class="swiper_deal_flex">
                                        @if($product->offer_price>0 && $product->price!=$product->offer_price)
                                        @php
                                         $discount_percentage = (($product->price - $product->offer_price) / $product->price) * 100;
                                        @endphp
                                        <h5>₹{{$product->offer_price}}<span>₹{{$product->price}}</span>  @if($discount_percentage > 0)<span class="discount-tag">({{ (int) $discount_percentage }}%  off)</span> @endif</h5>
                                        @else
                                        <h5>₹{{$product->price}}</h5>
                                        @endif
                                        <a href="{{route('front.product.details',$product->slug)}}" class="swiper_deal_btn"><svg width="20" height="20" viewBox="0 0 20 20"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_53_867)">
                                                    <path
                                                        d="M7.50002 18.3346C7.96026 18.3346 8.33335 17.9615 8.33335 17.5013C8.33335 17.0411 7.96026 16.668 7.50002 16.668C7.03978 16.668 6.66669 17.0411 6.66669 17.5013C6.66669 17.9615 7.03978 18.3346 7.50002 18.3346Z"
                                                        stroke="white" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path
                                                        d="M16.6666 18.3346C17.1269 18.3346 17.5 17.9615 17.5 17.5013C17.5 17.0411 17.1269 16.668 16.6666 16.668C16.2064 16.668 15.8333 17.0411 15.8333 17.5013C15.8333 17.9615 16.2064 18.3346 16.6666 18.3346Z"
                                                        stroke="white" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path
                                                        d="M0.833313 0.832031H4.16665L6.39998 11.9904C6.47618 12.374 6.6849 12.7187 6.9896 12.9639C7.2943 13.2092 7.67556 13.3395 8.06665 13.332H16.1666C16.5577 13.3395 16.939 13.2092 17.2437 12.9639C17.5484 12.7187 17.7571 12.374 17.8333 11.9904L19.1666 4.9987H4.99998"
                                                        stroke="white" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_53_867">
                                                        <rect width="20" height="20" fill="white" />
                                                    </clipPath>
                                                </defs>
                                            </svg>

                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                @endforeach
                @endif
              
            </div>
        </div>
    </section>
 
    @endsection
   
   @section('script')

   <script>
    function getColorId(colorId,productId) {
        // console.log("Selected Color ID:", colorId);
        // console.log("Selected Color ID:", productId);
        $.ajax({
            type: 'GET',
            url: "{{$base_url}}color-wise-size",
            data: {
                colorId: colorId,
                productId: productId,
            },
            success: function(response) {
                if(response.status == 200){
                    // console.log(response);
                
                    // $('#price_moodule').html("");
                    $('#form_product_size').html("");
                    response.data.forEach(function(size_data) {
                        const sizeHtml = `<div class="form-check form-check-inline form_product_size" onclick="form_color_input_size('${size_data.price}', '${size_data.offer_price}', '${size_data.id}')">
                        <input class="form-check-input form_color_input_size" type="radio" 
                                name="size_name" id="sizeOption${size_data.id}" value="${size_data.size}">
                            <label class="form-check-label" for="sizeOption${size_data.id}">
                                ${size_data.size_name}
                            </label>
                        </div>`;
                        
                        $('#form_product_size').append(sizeHtml);
                    });

                    $('#color_wise_image').html("");
                        var baseUrl = "{{$base_url}}";
                        var isFirstImage = true; // Flag to track if it's the first image
                        const countImages = response.images.length;
                        response.images.forEach(function(image_data, index) {
                            const imageUrl = baseUrl + '/' + image_data.image; // Construct the image URL
                            let slideClass;
                            if (index === 0) {
                                index += 1;
                                slideClass = 'swiper-slide-active';
                            } else if(index===1){
                                index += 1;
                                slideClass = 'swiper-slide-next';
                            }else{
                                index += 1;
                                slideClass = '';
                            }

                            const imageHtml = `<div class="swiper-slide ${slideClass}" role="group" aria-label="${index} / ${countImages}">
                                <img src="${imageUrl}" alt=""></div>`;
                            
                            $('#color_wise_image').append(imageHtml);
                        });

                        
                    $('#color_wise_slider_images').html("");
                    var baseUrl = "{{ $base_url}}";
                    response.images.forEach(function(image_data, index) {
                                // console.log(image_data);
                                const imageUrl = baseUrl + '/' + image_data.image; // Construct the image URL
                                let slideClass;
                                if (index === 0) {
                                    index += 1;
                                    slideClass = 'swiper-slide-active swiper-slide-thumb-active';
                                } else if(index===1){
                                    index += 1;
                                    slideClass = 'swiper-slide-next';
                                }else{
                                    index += 1;
                                    slideClass = '';
                                }

                                const imageHtml = `<div class="swiper-slide swiper-slide-visible swiper-slide-fully-visible ${slideClass}" role="group" aria-label="${index} / ${countImages}" style="width: 149px;">
                                    <div class="product_d_slider_img">
                                        <img src="${imageUrl}" alt="">
                                    </div>
                                </div>`;
                                
                                $('#color_wise_slider_images').append(imageHtml);
                            
                        });



                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            },
        });

    }
    function form_color_input_size(price, offer_price, variation_id){
        $('#variationId').val(variation_id);
        $('#price').val(price);
        $('#offer_price').val(offer_price);
        // alert(price)
        // alert(offer_price)
        if (price > 0 && offer_price > 0 && price != offer_price) { // Check if both prices exist and are greater than 0
            const price_data = `<h4>₹${offer_price}<span>₹${price}</span></h4>`;
            $('#price_module').html(price_data); // Use html() instead of append() to replace existing content
        } else if (price == offer_price && price > 0) { // Check if only offer_price exists and is equal to price
            const price_data = `<h4>₹${price}</h4>`;
            $('#price_module').html(price_data); // Use html() instead of append() to replace existing content
        } else if (price > 0 && offer_price == 0) { // Check if only price exists and offer_price is 0
            const price_data = `<h4>₹${price}</h4>`;
            $('#price_module').html(price_data); // Use html() instead of append() to replace existing content
        } else {
            // Handle case where neither price nor offer_price exists or they are both 0
            $('#price_module').empty(); // Clear the price module
        }
    }
    $(document).ready(function() {
        $('#checkout_button').on('click', function(){
            // Perform form validation here
            
            var choose_color = $('input[name="choose_color"]:checked').val();
            var size_name = $('input[name="size_name"]:checked').val();
            var quantity = $('input[name="quantity"]').val();

            $('#Error_show').empty();

            // Check if choose_color is not selected
            if (!choose_color) {
                $('#Error_show').text("Please select a color.").show();
                setTimeout(function() {
                    $('#Error_show').hide();
                }, 3000);
                return false; // Prevent further execution
            }

            // Check if size_name is not selected
            if (!size_name) {
                $('#Error_show').text("Please select a size.").show();
                setTimeout(function() {
                    $('#Error_show').hide();
                }, 3000);
                return false; // Prevent further execution
            }

            // Check if quantity is not provided or is not a valid number or is not within the range of 1 to 5
            if (!quantity || isNaN(quantity) || parseInt(quantity) < 1 || parseInt(quantity) > 5) {
                $('#Error_show').text("Please provide a valid quantity between 1 and 5.").show();
                setTimeout(function() {
                    $('#Error_show').hide();
                }, 3000);
                return false; // Prevent further execution
            }

            // If all validations pass, proceed with checkout
            // You can perform further actions here, such as AJAX request to submit the form
            // Example:
            // $('#myForm').submit();
            // or
            // $.ajax({ ... });
        });
    });
    // $(document).ready(function() {
    //     // Update href attributes of all <a> tags
    //     $('a').each(function() {
    //         var href = $(this).attr('href');
    //         if (href && href.startsWith('http:')) {
    //             $(this).attr('href', href.replace('http:', 'https:'));
    //         }
    //     });

    //     // Update action attributes of all <form> tags
    //     $('form').each(function() {
    //         var action = $(this).attr('action');
    //         if (action && action.startsWith('http:')) {
    //             $(this).attr('action', action.replace('http:', 'https:'));
    //         }
    //     });
    // });
</script>
   
@endsection --}}
