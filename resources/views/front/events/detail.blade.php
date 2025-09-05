@extends('front.layout.app')
@section('page-title', 'Donation')
@section('content')


<section class="main">
    <div class="blog-contain">
        <div class="blog-container">
            <div class="blog-single-header">
                <div class="single-breadcrumg">
                    <span><a href="{{ route('front.home') }}">Home</a></span>
                    {{ $event->title }}
                </div>

                <h1 class="single-blog-title">{{ ucwords($event->title) }}</h1>

                <div class="meta-blocks">
                    <div class="meta-blocks-stack">
                        <span><i class="fa-solid fa-calendar-days"></i></span>
                        <div class="stack">
                            <h6>Event Date</h6>
                            {{ \Carbon\Carbon::parse($event->start_time)->format('d M Y') }}
                            @if($event->end_time)
                                to {{ \Carbon\Carbon::parse($event->end_time)->format('d M Y') }}
                            @endif
                        </div>
                        <span><i class="fa-solid fa-home"></i></span>
                        <div class="stack">
                            <h6>Event Venue</h6>
                            {{ucwords($event->venue)}}
                        </div>  
                    </div>
                </div>
            </div>

            <div class="single-thumb-image">
                @php
                    $imagePath = optional($event->eventImage)->image_path;
                @endphp

                @if(!empty($imagePath) && file_exists(public_path($imagePath)))
                    <img src="{{ asset($imagePath) }}" alt="{{ $event->title }}">
                @else
                    <img src="{{ asset('assets/images/no-image.png') }}" alt="Default">
                @endif
            </div>

            <div class="blog-single-content-wrapper">
                <div class="site-single-post-content">
                    {!! ucwords($event->description) !!}
                </div>
            </div>
        </div>
    </div>

    @if($event->relatedEventDetails->count())
        <div class="container">
            <div class="related-product-stack mb-5">
                <div class="heading-group">
                    <h2 class="section-heading">Related Events</h2>
                    <div class="navi-slide">
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>

                <div class="relared-event-stack swiper">
                    <div class="swiper-wrapper">
                        @foreach($event->relatedEventDetails as $related)
                            <div class="swiper-slide">
                                <div class="inner-grid">
                                    <a href="{{ route('front.event.details', $related->slug) }}">
                                        <figure>
                                            @php
                                                $relatedImage = optional($related->eventImage)->image_path;
                                            @endphp
                                            @if($relatedImage && file_exists(public_path($relatedImage)))
                                                <img src="{{ asset($relatedImage) }}" alt="{{ $related->title }}">
                                            @else
                                                <img src="{{ asset('assets/images/no-image.png') }}" alt="Default">
                                            @endif
                                        </figure>
                                        <figcaption>
                                            <h3>{{ Str::limit($related->title, 50) }}</h3>
                                            <div class="event-date">
                                                <img src="{{ asset('assets/images/calender.svg') }}">
                                                <span>{{ \Carbon\Carbon::parse($related->start_time)->format('M d, Y') }}</span>
                                            </div>
                                            <div class="event-venue">
                                                <span>{{ ucwords($event->venue) }}</span>
                                            </div>
                                        </figcaption>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
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


        
    } );

    // quantity jquery
    // document.addEventListener("DOMContentLoaded", () => {
    //     const input = document.getElementById("quantity");
    //     document.querySelector(".increment").addEventListener("click", (e) => {
    //         e.preventDefault(); // Prevent form submission
    //         input.stepUp();
    //     });
    //     document.querySelector(".decrement").addEventListener("click", (e) => {
    //         e.preventDefault(); // Prevent form submission
    //         input.stepDown();
    //     });
    // });


    document.addEventListener("DOMContentLoaded", () => {
        // Handle increment buttons
        document.querySelectorAll(".increment").forEach(button => {
            button.addEventListener("click", (e) => {
                e.preventDefault();
                const input = button.closest('.number-input').querySelector(".quantity");
                input.stepUp();
            });
        });

        // Handle decrement buttons
        document.querySelectorAll(".decrement").forEach(button => {
            button.addEventListener("click", (e) => {
                e.preventDefault();
                const input = button.closest('.number-input').querySelector(".quantity");
                input.stepDown();
            });
        });
    });
    
</script>

@endsection