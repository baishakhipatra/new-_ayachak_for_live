@extends('front.layout.app')
@section('page-title', 'login')
@section('content')
<section class="main">
    <div class="container">
        <div class="login-stack">
            <div class="row justify-content-between">
                <div class="col-lg-5 mb-4 mb-md-5 mb-lg-0">
                    @if (session('error'))
                        <div class="alert alert-danger text-center" id="message_div">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="login-wrap">
                        <h2 class="section-heading mb-4">Login</h2>
                        <form action="{{route('front.check')}}" method="post">
                            @csrf
                            <div class="form-group"> 
                                <input type="mobile" class="form-control input-style" type="tel" placeholder=" " value="{{old('mobile')}}" id="mobile" name="mobile">
                                @error('mobile')
                                <div class="text-small text-danger">{{$message}}</div>
                                @enderror
                                <label class="placeholder-text">Enter Mobile</label>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control input-style password-input" placeholder=" " id="password" name="password">
                                <label class="placeholder-text">Password</label>
                                <span class="toggle-password" data-toggle="#password">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </span>
                            </div>

                            <a href="{{route('front.forgot.password')}}" class="forget-pass mb-4">Forgot your password?</a>

                            <input type="submit" class="bton btn-fill" value="Login">
                        </form>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="register-wrap">
                        <h2 class="section-heading mb-4">Register</h2>
                        <p>
                            Sign up for early Sale access plus tailored new arrivals, 
                            trends and promotions. To opt out, click unsubscribe in our emails.
                        </p>
                        <a href="{{route('front.register')}}" class="bton btn-fill">Register</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
//     $(document).on('click', '.toggle-password', function () {
//     console.log('Clicked!');
// });

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