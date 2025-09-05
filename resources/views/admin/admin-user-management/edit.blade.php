@extends('admin.layouts.app')

@section('page', 'Edit Admin User')

@section('content')

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">

        <div class="card">
          <!-- Card Header -->
          <div class="card-footer d-flex justify-content-end">
            <a href="{{ route('admin.admin-user-management.index') }}" class="btn btn-sm btn-danger">
              <i class="menu-icon tf-icons ri-arrow-left-line"></i></i> Back
            </a>
          </div>

          <!-- Card Body -->
          <div class="card-body">
            <form action="{{ route('admin.admin-user-management.update') }}" method="POST">
              @csrf
              @method('POST')

              {{-- Row 1: Name, Emp_ID, Type --}}
              <div class="row mb-3">
                <div class="col-md-3">
                  <div class="form-floating form-floating-outline">
                    <input type="text" name="name" class="form-control" placeholder="Full Name" value="{{ old('name', ucwords($data->name)) }}">
                    <label>Full Name</label>
                    @error('name') <p class="text-danger small">{{ $message }}</p> @enderror
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-floating form-floating-outline">
                    <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('name', $data->email) }}">
                    <label>Email</label>
                    @error('name') <p class="text-danger small">{{ $message }}</p> @enderror
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-floating form-floating-outline">
                    <input type="number" name="phone" class="form-control" placeholder="Phone" value="{{ old('phone', $data->phone) }}">
                    <label>Phone</label>
                    @error('phone') <p class="text-danger small">{{ $message }}</p> @enderror
                  </div>
                </div>
              </div>
              <div class="text-end">
                <input type="hidden" name="id" value="{{$data->id}}">
                <button type="submit" class="btn btn-primary px-4 py-2">
                  Update
                </button>
              </div>
            </form>
          </div>


          <!-- End Card Body -->

        </div>

      </div>
    </div>
  </div>
</section>

@endsection