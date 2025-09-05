@extends('admin.layouts.app')

@section('page', 'Edit CSR Project')

@section('content')

<div class="card p-4">
    <div class="card-footer d-flex justify-content-end">
        <a href="{{ route('admin.csr_projects.index') }}" class="btn btn-danger">
        <i class="ri-arrow-left-line"></i> Back
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.csr_projects.update', $project) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')

            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="{{ $project->title }}" required>
                @error('title') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" id="description-editor" class="form-control">{{ $project->description }}</textarea>
                @error('description') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label>Tags</label><br>
                @foreach($tags as $tag)
                    <label>
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                            {{ $project->tags->contains($tag->id) ? 'checked' : '' }}> {{ $tag->name }}
                    </label>
                @endforeach
                @error('tags') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label>Current Image</label><br>
                @if($project->image)
                    <img src="{{ asset('storage/' . $project->image) }}" width="150">
                @endif
                <input type="file" name="image" class="form-control mt-2">
            </div>

            <div class="mb-3">
                <label>Current File</label><br>
                @if($project->file)
                    <a href="{{ asset('storage/' . $project->file) }}" target="_blank">Download Current File</a>
                @endif
                <input type="file" name="file" class="form-control mt-2">
            </div>

            <button class="btn btn-primary">Update</button>
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
</script>
@endsection