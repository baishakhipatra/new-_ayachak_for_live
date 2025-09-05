@extends('front.layout.app')
@section('page-title', 'Donation')
@section('content')

<section class="main">
    <div class="container">
        <div class="cart-wrap">
            <h2 class="section-heading">Donation</h2>
        </div>

        <div class="donation-stack">
            <div class="row justify-content-between">
                <div class="col-lg-6 mb-4 mb-md-5 mb-lg-0">
                    <div class="dinate-image">
                        <img src="{{asset('assets/images/donation-img.jpg')}}" alt="">
                        <div class="image-caption">
                            The Multiversity Donation
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <form action="{{ route('front.donation.store') }}" method="POST">
                        @csrf

                        <div class="form-group"> 
                            <input type="text" class="form-control input-style" placeholder=" " name="full_name" value="{{ old('full_name', $user ? $user->name : '') }}">
                            <label class="placeholder-text">Full Name*</label>
                            @error('full_name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group"> 
                                    <input type="email" class="form-control input-style" placeholder=" " name="email" value="{{ old('email', $user ? $user->email : '') }}">
                                    <label class="placeholder-text">Email*</label>
                                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group"> 
                                    <input type="tel" class="form-control input-style" placeholder=" " name="phone_number" value="{{ old('phone_number', $user ? $user->mobile : '') }}">
                                    <label class="placeholder-text">Phone Number*</label>
                                    @error('phone_number') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group"> 
                                    <input type="text" class="form-control input-style" placeholder=" " name="pan_number" value="{{ old('pan_number') }}">
                                    <label class="placeholder-text">Enter Your PAN Number</label>
                                    @error('pan_number') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group"> 
                                    <input type="text" class="form-control input-style" placeholder=" " name="address" value="{{ old('address') }}">
                                    <label class="placeholder-text">Enter Your Address*</label>
                                    @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group"> 
                                    <input type="text" class="form-control input-style" placeholder=" " name="city_village" value="{{ old('city_village') }}">
                                    <label class="placeholder-text">Enter City/Village*</label>
                                    @error('city_village') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group"> 
                                    <input type="text" class="form-control input-style" placeholder=" " name="district" value="{{ old('district') }}">
                                    <label class="placeholder-text">Enter District*</label>
                                    @error('district') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group"> 
                                    <input type="text" class="form-control input-style" placeholder=" " name="state" value="{{ old('state') }}">
                                    <label class="placeholder-text">Enter State*</label>
                                    @error('state') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group"> 
                                    <input type="text" class="form-control input-style" placeholder=" " name="zipcode" value="{{ old('zipcode') }}">
                                    <label class="placeholder-text">Enter Zipcode*</label>
                                    @error('zipcode') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group"> 
                            <input type="text" class="form-control input-style" placeholder=" " name="country" value="India" readonly>
                            <label class="placeholder-text">Country*</label>
                            @error('country') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group"> 
                            <input type="number" class="form-control input-style" placeholder=" " name="amount" value="{{ old('amount') }}">
                            <label class="placeholder-text">Enter Amount*</label>
                            @error('amount') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <input type="submit" class="bton btn-full-pink" value="Donate Now">
                    </form>
                </div>
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