@extends('admin.layouts.app')

@section('page', 'Edit Designation')

@section('content')

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">

        <div class="card">
          <div class="card-footer d-flex justify-content-end">
            <a href="{{ route('admin.designation.index') }}" class="btn btn-sm btn-danger">
              <i class="menu-icon tf-icons ri-arrow-left-line"></i></i> Back
            </a>
          </div>

          <div class="card-body">
            <form action="{{ route('admin.designation.update') }}" method="POST">
              @csrf
              @method('POST')

              {{-- Row 1: Name, Emp_ID, Type --}}
              <div class="row mb-3">
                <div class="col-md-3">
                  <div class="form-floating form-floating-outline">
                    <input type="text" name="name" class="form-control" placeholder="Designation Name" value="{{ old('name', ucwords($data->name)) }}">
                    <label>Designation Name</label>
                    @error('name') <p class="text-danger small">{{ $message }}</p> @enderror
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

        </div>

      </div>
    </div>
  </div>
</section>

@endsection