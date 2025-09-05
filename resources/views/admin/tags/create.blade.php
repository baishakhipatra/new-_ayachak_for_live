@extends('admin.layouts.app')

@section('page', 'Create Tag')

@section('content')

<div class="card p-4">
    <div class="card-footer d-flex justify-content-end">
        <a href="{{ route('admin.tags.index') }}" class="btn btn-danger">
        <i class="ri-arrow-left-line"></i> Back
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.tags.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <button class="btn btn-success">Save</button>
        </form>
    </div>
</div>

@endsection