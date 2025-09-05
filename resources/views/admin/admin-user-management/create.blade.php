@extends('admin.layouts.app')

@section('page', 'Create Admin User')

@section('content')


<div class="card p-4">
  <div class="card-footer d-flex justify-content-end">
    <a href="{{ route('admin.admin-user-management.index') }}" class="btn btn-danger">
      <i class="ri-arrow-left-line"></i> Back
    </a>
  </div>

  <div class="card-body">
    <form action="{{ route('admin.admin-user-management.store') }}" method="POST">
      @csrf
      <div class="row mb-3">
        <div class="col-md-4">
          <div class="form-floating form-floating-outline">
            <input type="text" name="name" class="form-control" placeholder="Full Name" value="{{ old('name') }}">
            <label>Full Name<span class="text-danger">*</span></label>
            @error('name') <p class="text-danger small">{{ $message }}</p> @enderror
          </div>
        </div>
        <input type="hidden" name="user_type" value="Employee">
      </div>
      <div class="row mb-3">
        <div class="col-md-4">
          <div class="form-floating form-floating-outline">
            <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
            <label>Email<span class="text-danger">*</span></label>
            @error('email') <p class="text-danger small">{{ $message }}</p> @enderror
          </div>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-4">
          <div class="form-floating form-floating-outline">
            <input type="number" name="phone" class="form-control" placeholder="Phone" value="{{ old('phone') }}">
            <label>Phone<span class="text-danger">*</span></label>
            @error('phone') <p class="text-danger small">{{ $message }}</p> @enderror
          </div>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-4">
          <div class="form-password-toggle">
            <div class="input-group input-group-merge">
              <div class="form-floating form-floating-outline flex-grow-1">
                <input type="password" id="password" class="form-control" name="password" placeholder="********">
                <label for="password">Password<span class="text-danger">*</span></label>
                @error('password') <p class="small text-danger">{{ $message }}</p> @enderror
              </div>
              <span class="input-group-text cursor-pointer" onclick="togglePasswordVisibility(this)">
                <i class="fa fa-eye-slash fa-lg"></i>
              </span>
            </div>
          </div>
        </div>
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-primary px-4 py-2">Create</button>
      </div>
      
    </form>
  </div>

</div>

@endsection

@section('script')
<script>
    function togglePasswordVisibility(el) {
    const input = el.closest('.input-group').querySelector('input');
    const icon = el.querySelector('i');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    }
}
</script>
@endsection