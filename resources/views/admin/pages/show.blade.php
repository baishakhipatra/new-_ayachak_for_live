@extends('admin.layouts.app')
@section('page', 'View Page')

@section('content')

<div class="card">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card-footer d-flex justify-content-end">
        <a href="{{ route('admin.pages.index') }}" class="btn btn-primary mb-3">Back</a>
    </div>
</div>

<div class="card-body">
    <h3>{{ ucwords($page->title) }}</h3>
    <p><strong>Slug:</strong> {{ $page->slug }}</p>
    <p><strong>Content:</strong></p>
    <div>{!! $page->content !!}</div>
    <p><strong>Meta Title:</strong> {{ ucwords($page->meta_title) }}</p>
    <p><strong>Meta Description:</strong> {{ ucwords($page->meta_description) }}</p>
    <p><strong>Created At:</strong> {{ $page->created_at->format('d-m-Y H:i') }}</p>
</div>

@endsection