@extends('front.layout.app')
@section('page-title', 'Change-Password')
@section('content')

<section class="main">
    <div class="container">
        <div class="profile-wrapper">
            <div class="row">
                <div class="col-lg-3 mb-4 mb-md-5 mb-lg-0">
                    @include('front/sidebar_profile')
                </div>
                <div class="col-lg-9">
                    <div class="profile-right">
                        <h2>Change Password</h2>
                        <div class="row">
                            <div class="col-lg-8">
                                <form action="{{ route('front.password.update') }}" method="POST">
                                    @csrf
                                    <div class="form-group"> 
                                        <input type="password" class="form-control input-style password-input" placeholder=" " id="old_password" name="old_password">
                                        <label class="placeholder-text">Old Password</label>
                                        <span class="toggle-password" toggle="#password">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </span>
                                        @error('old_password')
                                                <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group"> 
                                        <input type="password" class="form-control input-style password-input" placeholder=" " id="new_password" name="new_password">
                                        <label class="placeholder-text">New Password</label>
                                        <span class="toggle-password" toggle="#password">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </span>
                                        @error('new_password')
                                                <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group"> 
                                        <input type="password" class="form-control input-style password-input" placeholder=" " id="confirm_password" name="confirm_password">
                                        <label class="placeholder-text">Confirm Password</label>
                                        <span class="toggle-password" toggle="#password">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </span>
                                        @error('confirm_password')
                                                <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <input type="submit" class="bton btn-fill" value="Change Password">

                                </form>
                            </div>
                        </div>
                    </div>
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

    $(document).on('click', '.toggle-password', function () {
        let input = $($(this).attr('toggle'));
        let icon = $(this).find('i');

        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
</script>

@endsection


































{{-- @extends('front.profile.layouts.app')

@section('profile-content')
    <div class="col-sm-7">
        <div class="profile-card">
            <form class="createField" action="{{ route('front.user.password.update') }}" method="POST">
                @csrf
                <h3>Change Password</h3>
                <div class="col-sm-9">
                    <div class="form-group">
                        <label for="form-label">Old Password<span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="old_password" placeholder="Old password">
                    </div>
                    @error('old_password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-sm-9">
                    <div class="form-group">
                        <label for="form-label">New Password<span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="new_password" placeholder="New password">
                    </div>
                    @error('new_password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-sm-9">
                    <div class="form-group">
                        <label for="form-label">Confirm Password<span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="confirm_password"
                            placeholder="Confirm new password">
                    </div>
                    @error('confirm_password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-sm-9">
                    <button type="submit" class="btn btn-danger">Update Password</button>
                </div>
            </form>
        </div>
    </div>
@endsection --}}
