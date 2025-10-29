@extends('front.layout.app')

@section('page-title', 'login')

@section('content')
<section class="main">
    <div class="container">
        <div class="login-stack">
            <div class="row justify-content-center">
                <div class="col-lg-5 mb-4 mb-md-5 mb-lg-0">
                    <div class="login-wrap">
                        <h2 class="section-heading mb-4">Forget Password</h2>
                            <form method="POST" class="register-block" action="{{route('front.forgot.password.check')}}">@csrf
                                <div class="form-group"> 
                                    <input type="tel" class="form-control input-style" placeholder=" " id="mobile" name="mobile">
                                    <label class="placeholder-text">Enter mobile</label>
                                    @error('mobile')
                                        <div class="text-danger text-small">{{$message}}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <input type="password" class="form-control input-style password-input" placeholder=" " id="password" name="password">
                                    <label class="placeholder-text">New Password</label>
                                    <span class="toggle-password" toggle="#password">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </span>
                                    @error('password')
                                        <div class="text-danger text-small">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control input-style password-input" placeholder=" " id="password_confirmation" name="password_confirmation">
                                    <label class="placeholder-text">Confirm Password</label>
                                    <span class="toggle-password" toggle="#password_confirmation">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </span>
                                    @error('password_confirmation')
                                        <div class="text-danger text-small">{{$message}}</div>
                                    @enderror
                                </div>

                                <div class="row align-items-center justify-content-center text-center">
                                    <div class="col-12">
                                        <button class="bton btn-fill" type="submit">Reset Password</button>
										<a href="{{route('front.login')}}">Back to login</a>
                                    </div>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
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