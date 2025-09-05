@extends('layouts.app')

@section('content')
    <div class="b2blogin_wrap">
        <div class="register-logo">
            <a href="#"><img src="https://onninternational.com/img/footer-logo.png"></a>
        </div>
        <div class="b2blogin">
            <div class="b2blogin_header">{{ __('OTP Verification') }}</div>

            <div class="b2blogin_body">
                <form method="POST" action="{{ route('front.user.login.mobile.otp') }}">
                    @csrf

                    <div class="form-group row">

                        <div class="col-12">
                            <label for="mobile" class="form-label">{{ __('Mobile number') }}</label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M17 2H7C5.89543 2 5 2.89543 5 4V20C5 21.1046 5.89543 22 7 22H17C18.1046 22 19 21.1046 19 20V4C19 2.89543 18.1046 2 17 2Z"
                                                stroke="#EB1C26" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M12 18H12.01" stroke="#EB1C26" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                                <input id="mobile" placeholder="Enter Mobile No" type="text"
                                    class="form-control @error('mobile') is-invalid @enderror" name="mobile"
                                    value="{{ request()->input('mobile') }}" required autocomplete="mobile">

                                @error('mobile')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-12">
                            <label for="otp" class="form-label">{{ __('Please enter the OTP') }}</label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <svg width="21px" height="21px" viewBox="0 0 15 15" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M6 5.5H9M7.5 5.5V10M10.5 10V7.5M10.5 7.5V5.5H11.5C12.0523 5.5 12.5 5.94772 12.5 6.5C12.5 7.05228 12.0523 7.5 11.5 7.5H10.5ZM4.5 6.5V8.5C4.5 9.05228 4.05228 9.5 3.5 9.5C2.94772 9.5 2.5 9.05228 2.5 8.5V6.5C2.5 5.94772 2.94772 5.5 3.5 5.5C4.05228 5.5 4.5 5.94772 4.5 6.5ZM1.5 0.5H13.5C14.0523 0.5 14.5 0.947715 14.5 1.5V13.5C14.5 14.0523 14.0523 14.5 13.5 14.5H1.5C0.947716 14.5 0.5 14.0523 0.5 13.5V1.5C0.5 0.947716 0.947715 0.5 1.5 0.5Z"
                                                stroke="#c10909" />
                                        </svg>
                                    </div>
                                </div>
                                <input id="otp" placeholder="Enter OTP" type="text"
                                    class="form-control @error('otp') is-invalid @enderror" name="otp" required
                                    autofocus>

                                @error('otp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary loginbtn">
                                {{ __('Verify and Login') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
