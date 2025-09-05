@extends('front/layout.app')
@section('content')

<section class="banner">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-lg-6">
                <div class="banner-content">
                    <div class="group-image">
                        <img src="./assets/images/group-flower.png" alt="">
                    </div>
                    <h2 class="banner-sub-heading">{{ $banner->sub_title }}</h2>
                    <h1 class="banner-heading">{{ $banner->title }}</h1>
                    <p>{{ $banner->description }}</p>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="banner-image">
                    <div class="single-image">
                        <img src="{{asset('assets/images/sinle-flower.png')}}" alt="">
                    </div>
                    <div class="image-holder">
                        <img src="{{ asset($banner->banner_image) }}" alt="">
                    </div>
                    <div class="play-btm" data-bs-toggle="modal" data-bs-target="#videoModal">
                        <img src="{{asset('assets/images/play.svg')}}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="circle-bottom">
        <img src="{{asset('assets/images/circle-image.svg')}}" alt="">
    </div>
    <div class="circle-top">
        <img src="{{asset('assets/images/circle-image.svg')}}" alt="">
    </div>
    <div class="overlay-bg"></div>
</section>

<section class="about-section">
    <div class="container">
        <div class="heading-group">
            <figure>
                <img src="./assets/images/divider.svg" alt="">
            </figure>
            <h3 class="section-sub-heading">Introduction</h3>
            <h2 class="section-heading">{{$page_heading}}</h2>
        </div>
        <p>{{$short_description}}</p>
        <a href="{{ route('front.about-us.index') }}" class="bton btn-fill">Read More</a>
    </div>
</section>

<section class="guru-section">
    <div class="container">
       <div class="content-holder-stack">
            <div class="row align-items-center justify-content-between">
                <div class="col-md-12 col-lg-5 mb-4 mb-md-5 mb-lg-0">
                    <div class="section-image-holder">
                        <div class="section-image-holder image-holder-big radious-bottom-right">
                            <img src="{{asset('assets/images/babamoni.jpg')}}" alt="">
                        </div>
                        <div class="image-group">
                            <img src="{{asset('assets/images/double-flower.png')}}" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6">
                    <div class="heading-group">
                        <figure>
                            <img src="{{asset('assets/images/divider.svg')}}" alt="">
                        </figure>
                        <h2 class="section-heading">{{$babamoni_heading}}</h2>
                        <h3 class="section-sub-heading">{{$babamoni_sub_heading}}</h3>
                    </div>
                    <div class="section-content-place">
                        <p>{{$babamoni_short_description}}</p>
                    </div>
                        
                    <a href="{{route('front.babamoni.index')}}" class="bton btn-fill">Read More</a>
                </div>
            </div>
       </div>
    </div>
    <div class="section-overlay"></div>
</section>

<section class="content-image-section pb-4">
    <div class="container">
        <div class="content-holder-stack">
            <div class="row align-items-center justify-content-between">
                <div class="col-md-12 col-lg-6 md-lg-6 order-lg-1 order-2">
                    <div class="heading-group">
                        <figure>
                            <img src="{{asset('assets/images/divider.svg')}}" alt="">
                        </figure>
                        <h2 class="section-heading">Uniqueness of Principles of Sri Sri Babamoni</h2>
                    </div>
                    <ul class="content-list">
                        <li>
                            <figure>
                                <img src="{{asset('assets/images/icon.svg')}}" alt="">
                            </figure>
                            <figcaption>
                                <h4>{{$abhiksha_heading}}</h4>
                                <p>{{$abhiksha_short_description}}</p>
                            </figcaption>
                        </li>
                    </ul>
                    <a href="{{route('front.abhiksha.index')}}" class="bton btn-fill">Read More</a>
                    <ul class="content-list">
                        <li>
                            <figure>
                                <img src="{{asset('assets/images/icon.svg')}}" alt="">
                            </figure>
                            <figcaption>
                                <h4>{{$morality_campaign_heading}}</h4>
                                <p>{{$morality_campaign_short_description}}</p>
                            </figcaption>
                        </li>
                    </ul>
                    <a href="{{route('front.morality-compaign.index')}}" class="bton btn-fill">Read More</a>
                </div>
                <div class="col-md-12 col-lg-5 mb-4 mb-md-5 mb-lg-0 order-lg-2 order-1">
                    <div class="section-image-holder">
                        <div class="section-image-holder image-holder-medium radious-bottom-left">
                            <img src="{{asset('assets/images/dummy-image.jpg')}}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section-circle-image">
        <img src="{{asset('assets/images/circle-divider.svg')}}" alt="">
    </div>
</section>

<section class="flower-divider">
    <div class="container">
        <img src="{{asset('assets/images/double-flower.png')}}" alt="">
    </div>
</section>

<section class="image-content-section ">
    <div class="container">
        <div class="content-holder-stack">
            <div class="row align-items-center justify-content-between">
                <div class="col-md-12 col-lg-5 mb-4 mb-md-5 mb-lg-0">
                    <div class="section-image-holder">
                        <div class="section-image-holder image-holder-medium radious-top-right">
                            <img src="{{asset('assets/images/dummy-image.jpg')}}" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6">
                    <div class="heading-group">
                        <figure>
                            <img src="{{asset('assets/images/divider.svg')}}" alt="">
                        </figure>
                        <h2 class="section-heading">Institutions Founded by Sri Sri Babamoni</h2>
                    </div>
                    <ul class="content-list">
                        <li>
                            <figure>
                                <img src="{{asset('assets/images/icon.svg')}}" alt="">
                            </figure>
                            <figcaption>
                                <h4>{{$ayachak_ashram_heading}}</h4>
                                <p>{{$ayachak_ashram_short_description}}</p>
                            </figcaption>
                        </li>
                    </ul>
                    <a href="{{route('front.ayachak-ashram.index')}}" class="bton btn-fill">Read More</a>
                    <ul class="content-list">
                        <li>
                            <figure>
                                <img src="{{asset('assets/images/icon.svg')}}" alt="">
                            </figure>
                            <figcaption>
                                <h4>{{$the_multiversity_heading}}</h4>
                                <p>{{$the_multiversity_short_description}}</p>
                            </figcaption>
                        </li>
                    </ul>
                    <a href="{{route('front.the-multiversity.index')}}" class="bton btn-fill">Read More</a>
                </div>
            </div>
        </div>
    </div>
    <div class="section-circle-image">
        <img src="{{asset('assets/images/circle-divider.svg')}}" alt="">
    </div>
</section>

<section class="section-donation">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="heading-group">
                    <figure>
                        <img src="{{asset('assets/images/divider.svg')}}" alt="">
                    </figure>
                    <h2 class="section-heading">Donating to charity helps support those in need and makes a positive difference in the world.</h2>
                    <a href="{{route('front.donation.form')}}" class="bton btn-fill">Donate Now</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="three-grid-section">
    <div class="container">
        <div class="heading-group">
            <figure>
                <img src="{{asset('assets/images/divider.svg')}}" alt="">
            </figure>
            <h2 class="section-heading">Policy For Smooth Running Welfare Activities By <br> Following Principle of Abhiksha</h2>
        </div>

        <ul class="grid-list">
            <li>
                <div class="inner">
                    <figure>
                        <img src="{{asset('assets/images/icon2.svg')}}" alt="">
                    </figure>
                    <figcaption>
                        <h2>{{$ayurvedic_medicines_heading}}</h2>
                        <p>
                           {{$ayurvedic_medicines_short_description}}
                        </p>
                    </figcaption>
                    <a href="{{route('front.shop.list')}}" class="bton btn-fill">Shop Now</a>
                </div>
            </li>
            <li>
                <div class="inner">
                    <figure>
                        <img src="{{asset('assets/images/icon3.svg')}}" alt="">
                    </figure>
                    <figcaption>
                        <h2>{{$books_heading}}</h2>
                        <p>
                            {{$books_short_description}}
                        </p>
                    </figcaption>
                    <a href="{{ route('front.shop.list', ['category' => 'Book']) }}" class="bton btn-fill">Shop Now</a>
                </div>
            </li>
            <li>
                <div class="inner">
                    <figure>
                        <img src="{{asset('assets/images/icon1.svg')}}" alt="">
                    </figure>
                    <figcaption>
                        <h2>{{$voluntary_donations_heading}}</h2>
                        <p>
                           {{$voluntary_donations_short_description}}
                        </p>
                    </figcaption>
                    <a href="{{route('front.donation.form')}}" class="bton btn-fill">Donate Now</a>
                </div>
            </li>
        </ul>
    </div>
</section>

<section class="guru-section mamoni-section">
    <div class="container">
       <div class="content-holder-stack">
            <div class="row align-items-center justify-content-between">
                <div class="col-md-12 col-lg-6 order-lg-1 order-2">
                    <div class="heading-group">
                        <figure>
                            <img src="{{asset('assets/images/divider.svg')}}" alt="">
                        </figure>
                        <h2 class="section-heading">{{$mamoni_heading}}</h2>
                        <h3 class="section-sub-heading">{{$mamoni_sub_heading}}</h3>
                    </div>
                    <div class="section-content-place">
                        <p>{{$mamoni_short_description}}</p>
                    </div>
                        
                    <a href="{{route('front.mamoni.index')}}" class="bton btn-fill">Read More</a>
                </div>
                <div class="col-md-12 col-lg-5 mb-4 mb-md-5 mb-lg-0 order-lg-2 order-1">
                    <div class="section-image-holder">
                        <div class="section-image-holder image-holder-big radious-bottom-left">
                            <img src="{{asset('assets/images/mamoni.jpg')}}" alt="">
                        </div>
                        <div class="image-group">
                            <img src="{{asset('assets/images/double-flower.png')}}" alt="">
                        </div>
                    </div>
                </div>
            </div>
       </div>
    </div>
    <div class="section-overlay"></div>
</section>

<section class="single-heading-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h2 class="section-heading">Two Strong Pillars Without Whom Probably Ayachak Ashram Would Not Come Into Reality</h2>
            </div>
        </div>
        
    </div>
</section>

<section class="image-content-section pb-4">
    <div class="container">
        <div class="content-holder-stack">
            <div class="row align-items-center justify-content-between">
                <div class="col-md-12 col-lg-5 mb-4 mb-md-5 mb-lg-0">
                    <div class="section-image-holder">
                        <div class="section-image-holder image-holder-medium radious-bottom-right">
                            <img src="{{asset('assets/images/sadhana-devi.jpg')}}" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6">
                    <div class="heading-group">
                        <figure>
                            <img src="{{asset('assets/images/divider.svg')}}" alt="">
                        </figure>
                        <h2 class="section-heading">{{$sadhanaDevi_heading}}</h2>
                        <h3 class="section-sub-heading">{{$sadhanaDevi_sub_heading}}</h3>
                    </div>
                    <div class="section-content-place">
                        <p>{{$sadhanaDevi_short_description}}</p>
                    </div>
                    <a href="{{route('front.sadhanadevi.index')}}" class="bton btn-fill">Read More</a>
                </div>
            </div>
        </div>
    </div>
    <div class="section-circle-image">
        <img src="{{asset('assets/images/circle-divider.svg')}}" alt="">
    </div>
</section>

<section class="flower-divider">
    <div class="container">
        <img src="{{asset('assets/images/double-flower.png')}}" alt="">
    </div>
</section>

<section class="content-image-section pt-4">
    <div class="container">
        <div class="content-holder-stack">
            <div class="row align-items-center justify-content-between">
                <div class="col-md-12 col-lg-6 order-lg-1 order-2">
                    <div class="heading-group">
                        <figure>
                            <img src="{{asset('assets/images/divider.svg')}}" alt="">
                        </figure>
                        <h2 class="section-heading">{{$bhaida_heading}}</h2>
                        <h3 class="section-sub-heading">{{$bhaida_sub_heading}}</h3>
                    </div>
                    <div class="section-content-place">
                        <p>{{$bhaida_short_description}}</p>
                    </div>
                    <a href="{{route('front.bhaida.index')}}" class="bton btn-fill">Read More</a>
                </div>
                <div class="col-md-12 col-lg-5 mb-4 mb-md-5 mb-lg-0 order-lg-2 order-1">
                    <div class="section-image-holder">
                        <div class="section-image-holder image-holder-medium radious-top-left">
                            <img src="{{asset('assets/images/snehamoy.jpg')}}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section-circle-image">
        <img src="{{asset('assets/images/circle-divider.svg')}}" alt="">
    </div>
</section>

<section class="four-grid-section">
   <ul class="four-grid-list">
        <li>
            <div class="inner">
                <figure>
                    <img src="{{asset('assets/images/image1.jpg')}}" alt="">
                </figure>
                <figcaption>
                    <h3>{{$akhanda_heading}}</h3>
                    <p>{{$akhanda_short_description}}</p>
                    <a  href="{{route('front.akhanda.index')}}" class="text-arrow">
                        Read More
                        <img src="{{asset('assets/images/text-arrow.svg')}}" alt="">
                    </a>
                </figcaption>
            </div>
        </li>
        <li>
            <div class="inner">
                <figure>
                    <img src="{{asset('assets/images/image2.jpg')}}" alt="">
                </figure>
                <figcaption>
                    <h3>{{$omkar_heading}}</h3>
                    <p>{{$omkar_short_description}}</p>
                    <a  href="{{route('front.omkar.index')}}" class="text-arrow">
                        Read More
                        <img src="{{asset('assets/images/text-arrow.svg')}}" alt="">
                    </a>
                </figcaption>
            </div>
        </li>

        <li>
            <div class="inner">
                <figure>
                    <img src="{{asset('assets/images/image3.jpg')}}" alt="">
                </figure>
                <figcaption>
                    <h3>{{$Sangathan_heading}}</h3>
                    <p>{{$Sangathan_short_description}}</p>
                    <a  href="{{route('front.sangathan.index')}}" class="text-arrow">
                        Read More
                        <img src="{{asset('assets/images/text-arrow.svg')}}" alt="">
                    </a>
                </figcaption>
            </div>
        </li>
        <li>
            <div class="inner">
                <figure>
                    <img src="{{asset('assets/images/image4.jpg')}}" alt="">
                </figure>
                <figcaption>
                    <h3>{{$samabeta_upasana_heading}}</h3>
                    <p>{{$samabeta_upasana_short_description}}</p>
                    <a  href="{{route('front.samabeta_upasana.index')}}" class="text-arrow">
                        Read More
                        <img src="{{asset('assets/images/text-arrow.svg')}}" alt="">
                    </a>
                </figcaption>
            </div>
        </li>
   </ul>
</section>

{{-- shop by category section --}}
<section class="cat-section">
    <div class="container">
        <div class="heading-group">
            <h2 class="section-heading">Shop by Category</h2>
        </div>
        <ul class="cat-list">
            @foreach($categories as $category)
                <li>
                    <a href="{{ route('front.shop.list', ['category' => $category->slug ?? $category->name]) }}">
                        <figure>
                            <img src="{{ $category->icon_path 
                                        ? asset($category->icon_path) 
                                        : asset('assets/images/placeholder-category.png') }}" 
                                 alt="{{ $category->name }}">
                        </figure>
                        <h4>{{ ucwords($category->name) }}</h4>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>


<section class="product-section">
    <div class="container">
        <div class="heading-group">
            <h2 class="section-heading">Featured Products</h2>
        </div>

        <ul class="product-list">
            @forelse($featuredProducts as $product)
                <li>
                    <div class="pro-inner">
                        <figure>
                            <a href="{{ route('front.shop.details', $product->slug) }}">
                                <img src="{{ asset($product->image ?? 'assets/images/placeholder-product.jpg') }}" alt="{{ $product->name }}">
                            </a>
                        </figure>
                        <figcaption>
                            <a href="{{ route('front.shop.details', $product->slug) }}">
                                <h3>{{ $product->name }}</h3>
                            </a>
                            <div class="price-group">
                                <span class="original-price">₹{{ number_format($product->price, 2) }}</span>
                            </div>
                            <a href="{{ route('front.shop.details', $product->slug) }}" class="bton btn-fill">Shop Now</a>
                            
                        </figcaption>
                    </div>
                </li>
            @empty
                <li>No featured products found.</li>
            @endforelse
        </ul>
        <a href="{{route('front.shop.list')}}" class="bton btn-fill">View More</a>
    </div>
    <div class="overlay-pattern"></div>
</section>

<section class="event-section">
    <div class="container">
        <div class="heading-group">
            <h2 class="section-heading">Latest Events</h2>
        </div>
        <ul class="event-list">
            @foreach($latestEvents as $event)
            <li>
                <div class="inner-grid">
                    <a href="{{route('front.event.details', $event->slug)}}">
                        <figure>
                            @if($event->eventImage->count() > 0)
                                <img src="{{ asset($event->eventImage->image_path) }}" alt="">
                            @else
                                <img src="{{ asset('assets/images/default-event.jpg') }}" alt="No Image">
                            @endif
                        </figure>
                        <figcaption>
                            <h3>{{ ucwords($event->title) }}</h3>
                            <h6>{{ ucwords($event->venue )}}</h6>
                            <div class="event-date">
                                <img src="{{ asset('assets/images/calender.svg') }}">
                                <span>{{ \Carbon\Carbon::parse($event->start_time)->format('M d, Y') }}</span>
                            </div>
                        </figcaption>
                    </a>
                </div>
            </li>
            @endforeach
        </ul>
        <a href="{{route('front.event.index')}}" class="bton btn-fill">View More</a>
    </div>
</section>
@endsection