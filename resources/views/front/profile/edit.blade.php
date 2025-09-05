@extends('front.profile.layouts.app')

@section('profile-content')
<div class="col-sm-7">

    {{-- @if (Session::get('success'))
        <div class="alert alert-success"> {{Session::get('success')}} </div>
    @endif
    @if (Session::get('failure'))
        <div class="alert alert-danger"> {{Session::get('failure')}} </div>
    @endif --}}

    <div class="profile-card">
        <form method="POST" action="{{route('front.user.manage.update')}}">@csrf
            <h3>Edit Profile</h3>
            <div class="row">
                <div class="col-sm-12 col-lg-6">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="First Name" name="fname" value="{{Auth::guard('web')->user()->fname}}">
                        <label class="floating-label">First Name</label>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-6">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Last Name" name="lname" value="{{Auth::guard('web')->user()->lname}}">
                        <label class="floating-label">Last Name</label>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-6">
                    <div class="form-group">
                        <input type="tel" class="form-control" placeholder="Email Address" value="{{Auth::guard('web')->user()->email}}" readonly disabled>
                        <label class="floating-label">Email Address</label>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-6">
                    <div class="form-group">
                        <input type="tel" class="form-control" placeholder="Mobile No" name="mobile" value="{{Auth::guard('web')->user()->mobile}}">
                        <label class="floating-label">Mobile No</label>
                    </div>
					@error('mobile') <p class="small text-danger">{{$message}}</p> @enderror
                </div>
            </div>

            <div class="profile-card-footer">
                <button type="submit" class="btn checkout-btn">Update Details</button>
            </div>
        </form>
    </div>

    <div class="profile-card">
        <form method="POST" action="{{route('front.user.password.update')}}">@csrf
            <h3>Change Password</h3>
            <div class="row">
                <div class="col-sm-12 col-lg-6">
                    <div class="form-group">
                        <input type="password" class="form-control" name="old_password" placeholder="Old Password">
                        <label class="floating-label">Old Password</label>
                    </div>
                    @error('old_password')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-lg-6">
                    <div class="form-group">
                        <input type="password" class="form-control" name="new_password" placeholder="New Password">
                        <label class="floating-label">New Password</label>
                    </div>
                    @error('new_password')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                </div>
                <div class="col-sm-12 col-lg-6">
                    <div class="form-group">
                        <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password">
                        <label class="floating-label">Confirm Password</label>
                    </div>
                    @error('confirm_password')<p class="small text-danger mb-0">{{$message}}</p>@enderror
                </div>
            </div>
            <div class="profile-card-footer">
                <button type="submit" class="btn checkout-btn">Update Password</button>
            </div>
        </form>
    </div>
</div>
@endsection