{{-- 
<body>
    <main>
		<section class="register-wrapper">
            <div class="register-right">
                 <div class="register-logo">
                    <a href="{{route('front.home')}}"><img src="{{asset('img/footer-logo.png')}}"></a>
                </div>
                <div class="container">
                    <div class="row m-0 justify-content-center">
                        <div class="col-12 col-lg-5 p-0">
                            <form method="POST" class="register-block" action="{{route('password.email')}}">@csrf
                                <h3>Forgot Password</h3>
                                <h4>Password reset link will be sent to your Email id</h4>

                                @if (session('status'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif
            
                                <div class="register-card">
                                    <div class="register-group">
                                        <input type="email" class="register-box" name="email" placeholder="Email id" value="{{old('email')}}" autofocus>
                                        <label class="floating-label">Email id</label>
                                        @error('email') <p class="small text-danger mb-0">{{$message}}</p> @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center justify-content-center text-center">
                                    <div class="col-12">
                                        <button type="submit">{{ __('Send Password Reset Link') }}</button>
										<a href="{{route('front.user.login')}}">Back to login</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="{{ asset('node_modules/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('js/plugin.js') }}"></script>
    <script src="{{ asset('node_modules/bootstrap/dist/js/bootstrap.js') }}"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="{{ asset('node_modules/gsap/dist/gsap.min.js') }}"></script>
    <script src="{{ asset('node_modules/gsap/dist/ScrollTrigger.min.js') }}"></script>
    <script src="{{ asset('node_modules/waypoints/lib/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('node_modules/counterup/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('node_modules/lightbox2/dist/js/lightbox.min.js') }}"></script>
    <script src="{{ asset('node_modules/select2/dist/js/select2.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.16.0/TweenMax.min.js"></script>
    <script src="{{ asset('node_modules/scrollmagic/scrollmagic/minified/ScrollMagic.min.js') }}"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.3/plugins/animation.gsap.min.js'></script>
    <script src="{{ asset('node_modules/scrollmagic/scrollmagic/minified/plugins/debug.addIndicators.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
        // sweetalert fires | type = success, error, warning, info, question
        function toastFire(type = 'success', title, body = '') {
            const Toast = Swal.mixin({
				toast: true,
				position: 'top-end',
				showConfirmButton: false,
				timer: 3000,
				// timerProgressBar: true,
                showCloseButton: true,
				didOpen: (toast) => {
					toast.addEventListener('mouseenter', Swal.stopTimer)
					toast.addEventListener('mouseleave', Swal.resumeTimer)
				}
			});

			Toast.fire({
				icon: type,
				title: title
			});
        }

        // on session toast fires
        @if (Session::has('success'))
            toastFire('success', '{{ Session::get('success') }}');
        @elseif (Session::has('failure'))
            toastFire('warning', '{{ Session::get('failure') }}');
        @endif
    </script>
</body>

</html> --}}

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
                            <form method="POST" class="register-block" action="{{route('front.user.forgot.password.check')}}">@csrf
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
                                        <button type="submit">Reset Password</button>
										<a href="{{route('front.user.login')}}">Back to login</a>
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