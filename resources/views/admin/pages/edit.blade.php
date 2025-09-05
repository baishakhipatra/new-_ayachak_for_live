@extends('admin.layouts.app')

@section('page', 'Edit Page Content')

@section('content')

<div class="card p-4">
    <div class="card-footer d-flex justify-content-end">
        <a href="{{ route('admin.pages.index') }}" class="btn btn-danger">
        <i class="ri-arrow-left-line"></i> Back
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.pages.update', $page->id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="title">Page Title</label>
                <input type="text" name="title" id="title"
                    value="{{ old('title', $page->title) }}"
                    class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Slug</label>
                <input type="text" name="slug" id="slug" class="form-control"
                    value="{{ old('slug', $page->slug) }}" required>
                @error('slug')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="content">Page Content</label>
                <textarea name="content" id="content-editor" class="form-control" rows="6">{{ old('content', $page->content) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label d-block">Status:</label>
                <div class="form-check form-switch">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="status"
                        id="statusSwitch"
                        {{ old('status', $page->status) ? 'checked' : '' }}>
                    <label class="form-check-label" for="statusSwitch"></label>
                </div>
            </div>

            <div class="mb-3">
                <label for="meta_title">Meta Title</label>
                <input type="text" name="meta_title"
                    value="{{ old('meta_title', $page->meta_title) }}"
                    class="form-control">
            </div>

            <div class="mb-3">
                <label for="meta_description">Meta Description</label>
                <textarea name="meta_description"
                    class="form-control"
                    rows="3">{{ old('meta_description', $page->meta_description) }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">Update Page</button>
        </form>
    </div>
</div>

@endsection

@section('script')
<script>
    ClassicEditor
        .create(document.querySelector('#content-editor'), {
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                'blockQuote', 'insertTable', 'undo', 'redo'
            ],
            height: '400px'
    })
    .catch(error => {
        console.error(error);
    });


    const title = document.getElementById('title');
    const slug = document.getElementById('slug');

    let slugManuallyChanged = false;

    // If user edits the slug field directly, stop auto-generating
    slug.addEventListener('input', function() {
        slugManuallyChanged = true;
    });

    title.addEventListener('keyup', function() {
        if (!slugManuallyChanged) {
            let val = this.value.trim().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
            slug.value = val;
        }
    });
</script>
@endsection