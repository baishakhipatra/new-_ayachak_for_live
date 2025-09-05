@extends('admin.layouts.app')

@section('page', 'Create Page')

@section('content')

<div class="card p-4">
    <div class="card-footer d-flex justify-content-end">
        <a href="{{ route('admin.pages.index') }}" class="btn btn-danger">
        <i class="ri-arrow-left-line"></i> Back
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.pages.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Page Title</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
            </div>

            <div class="mb-3">
                <label>Slug</label>
                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug') }}" required>
                @error('slug')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label>Page Content</label>
                <textarea name="content" id="content-editor" class="form-control" rows="6">{{ old('content') }}</textarea>
            </div>

            
            <div class="mb-3">
                <label class="form-label d-block">Status:</label>
                <div class="form-check form-switch">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="status"
                        id="statusSwitch"
                        {{ old('status', 1) ? 'checked' : '' }}>
                    <label class="form-check-label" for="statusSwitch"></label>
                </div>
            </div>



            <div class="mb-3">
                <label>Meta Title</label>
                <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title') }}">
            </div>

            <div class="mb-3">
                <label>Meta Description</label>
                <textarea name="meta_description" class="form-control" rows="3">{{ old('meta_description') }}</textarea>
            </div>

            <button class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
@endsection

<!-- CKEditor -->
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

    title.addEventListener('keyup', function() {
        let val = this.value.trim().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
        slug.value = val;
    });

    function statusToggle(url, checkbox) {
        const status = checkbox.checked ? 1 : 0;

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 200) {
                toastFire('success', data.message);
                location.reload();
            } else {
                toastFire('error', data.message);
            }
        })
        .catch(error => {
            toastFire('error', 'Something went wrong!');
            console.error(error);
        });
    }


</script>
@endsection