@extends('admin.layouts.app')

@section('page', 'Create Designation')

@section('content')


<div class="card p-4">
  <div class="card-footer d-flex justify-content-end">
    <a href="{{ route('admin.designation.index') }}" class="btn btn-danger">
      <i class="ri-arrow-left-line"></i> Back
    </a>
  </div>

  <div class="card-body">
    <form action="{{ route('admin.designation.store') }}" method="POST">
      @csrf
      <div class="row mb-3">
        <div class="col-md-4">
          <div class="form-floating form-floating-outline">
            <input type="text" name="name" class="form-control" placeholder="Designation Name" value="{{ old('name') }}">
            <label>Designation Name<span class="text-danger">*</span></label>
            @error('name') <p class="text-danger small">{{ $message }}</p> @enderror
          </div>
        </div>
        <input type="hidden" name="user_type" value="Employee">
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-primary px-4 py-2">Create</button>
      </div>
      
    </form>
  </div>

</div>

@endsection