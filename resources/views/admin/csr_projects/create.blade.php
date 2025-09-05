@extends('admin.layouts.app')

@section('page', 'CSR Project')

@section('content')

<div class="card p-4">
    <div class="card-footer d-flex justify-content-end">
        <a href="{{ route('admin.csr_projects.index') }}" class="btn btn-danger">
        <i class="ri-arrow-left-line"></i> Back
        </a>
    </div>


    <div class="card-body">
        <form action="{{ route('admin.csr_projects.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required>
                @error('title') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" id="description-editor" class="form-control" rows="6"></textarea>
                @error('description') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label>Tags</label><br>
                @foreach($tags as $tag)
                    <label>
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}"> {{ ucwords($tag->name) }}
                    </label>
                @endforeach
                @error('tags') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label>Image</label>
                <input type="file" name="image" class="form-control">
                @error('image') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label>File</label>
                <input type="file" name="file" class="form-control">
                @error('file') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <button class="btn btn-success">Save</button>
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
</script>
@endsection