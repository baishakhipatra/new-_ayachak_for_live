@extends('front.layout.app')

@section('page-title', 'Register')

@section('content')
<section class="main">
    <div class="container">
        <div class="login-stack">
            <div class="row justify-content-center">
                <div class="col-lg-5 mb-4 mb-md-5 mb-lg-0">
                    <div class="login-wrap">
                        <h2 class="section-heading mb-4">Register</h2>
                        <form action="{{route('front.create')}}" method="post">
                            @csrf
                            <div class="form-group"> 
                                <input type="text" class="form-control input-style" placeholder=" " id="name" name="name" value="{{ old('name') }}">
                                <label class="placeholder-text">Full Name</label>
                                @error('name')
                                    <div class="text-danger text-small">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group"> 
                                <input type="email" class="form-control input-style" placeholder=" " id="email" name="email" value="{{ old('email') }}">
                                <label class="placeholder-text">Enter Email</label>
                                @error('email')
                                    <div class="text-danger text-small">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group"> 
                                <input type="tel" class="form-control input-style" placeholder=" " id="mobile" name="mobile" value="{{ old('mobile') }}">
                                <label class="placeholder-text">Enter mobile</label>
                                @error('mobile')
                                    <div class="text-danger text-small">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control input-style password-input" placeholder=" " id="password" name="password">
                                <label class="placeholder-text">Password</label>
                                <span class="toggle-password">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </span>
                                @error('password')
                                    <div class="text-danger text-small">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control input-style password-input" placeholder=" " id="confirm_password" name="confirm_password">
                                <label class="placeholder-text">Confirm Password</label>
                                <span class="toggle-password">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </span>
                                @error('confirm_password')
                                    <div class="text-danger text-small">{{$message}}</div>
                                @enderror
                            </div>

                            <p class="form-text">Sign up for early Sale access plus tailored new arrivals, trends and promotions. To opt out, click unsubscribe in our emails.</p>

                            <input type="submit" class="bton btn-fill" value="Register">
                            <a href="{{ route('front.login') }}" class="bton btn-fill">Login</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

