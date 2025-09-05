@extends('admin.layouts.app')

@section('page', 'Change Password')

@section('content')
<section>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
              <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.update.password') }}">
                    @csrf
                        <h4 class="page__subtitle">Change Password - {{Auth::guard('admin')->user()->name}}</h4>
                        <div class="form-group mb-3">
                            <label class="label-control">Old Password <span class="text-danger">*</span> </label>
                            <input type="password" name="old_password" placeholder="" class="form-control" value="{{old('old_password')}}">
                            @error('old_password') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">New password <span class="text-danger">*</span> </label>
                            <input type="password" name="new_password" placeholder="" class="form-control" value="{{old('new_password')}}">
                            @error('new_password') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Confirm new password <span class="text-danger">*</span> </label>
                            <input type="password" name="confirm_new_password" placeholder="" class="form-control" value="{{old('confirm_new_password')}}">
                            @error('confirm_new_password') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-danger">Update password</button>
                        </div>
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