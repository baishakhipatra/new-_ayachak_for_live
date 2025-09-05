@extends('front.layout.app')
@section('page-title', 'Events')
@section('content')

<section class="inner-banner">
    <div class="container">
        <div class="inner-heading-group">
            <h2>Latest Events</h2>
            <ul class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li>Events</li>
            </ul>
        </div>
    </div>
</section>
<section class="event-stack">
    <div class="container">
        <div class="search-events">
            <div class="search-stack">
                <form action="{{ route('front.event.index') }}" method="GET" class="position-relative">
                    <div class="form-group position-relative">
                        <input type="search" name="term" value="{{ request('term') }}" placeholder=" "
                            class="search-input form-control input-style pe-5">
                        <label class="placeholder-text">Search Events</label>
                        <span class="search-icon position-absolute top-50 end-0 translate-middle-y me-5">
                            <img src="{{ asset('assets/images/search.svg') }}" alt="">
                        </span>

                        @if(request('term'))
                            <a href="{{ route('front.event.index') }}" 
                            class="position-absolute top-50 end-0 translate-middle-y me-2 text-decoration-none text-dark">
                                <i class="fa fa-times"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>


        <div class="event-stack-listing">
            <ul class="event-list">
                @forelse ($events as $event)
                    <li>
                        <div class="inner-grid">
                            <a href="{{ route('front.event.details', $event->slug) }}">
                                <figure>
                                    @php
                                        $imagePath = optional($event->eventImage)->image_path;
                                    @endphp

                                    @if (!empty($imagePath) && file_exists(public_path($imagePath)))
                                        <img src="{{ asset($imagePath) }}" alt="{{ $event->title }}">
                                    @else
                                        <img src="{{ asset('assets/images/no-image.png') }}" alt="Default Image">
                                    @endif
                                </figure>
                                <figcaption>
                                    <h3>{{ ucwords($event->title) }}</h3>
                                    <div class="event-date">
                                        <img src="{{ asset('assets/images/calender.svg') }}">
                                        <span>{{ \Carbon\Carbon::parse($event->start_time)->format('M d, Y') }}</span>
                                    </div>
                                    <div class="event-venue">
                                        <span>{{ ucwords($event->venue) }}</span>
                                    </div>
                                </figcaption>
                            </a>
                        </div>
                    </li>
                @empty
                    <li class="text-muted">No events found.</li>
                @endforelse
            </ul>

            <div class="pagination-stack">
                {{ $events->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
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