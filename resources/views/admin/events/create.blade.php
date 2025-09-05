@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Create New Event</h4>

    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.events.index') }}" class="btn btn-danger">
        <i class="ri-arrow-left-line"></i> Back
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                    @error('title')
                        <p class="small text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label">Venue <span class="text-danger">*</span></label>
                    <input type="text" name="venue" class="form-control" value="{{ old('venue') }}">
                    @error('venue')
                        <p class="small text-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" id="content-editor" class="form-control" rows="5">{{ old('description') }}</textarea>
                @error('description')
                    <p class="small text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="row">
                <div class="mb-3 col-md-6">
                    <label class="form-label">Start Time<span class="text-danger">*</span></label>
                    <input type="datetime-local" name="start_time" class="form-control" value="{{ old('start_time') }}">
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label">End Time</label>
                    <input type="datetime-local" name="end_time" class="form-control" value="{{ old('end_time') }}">
                </div>
            </div>

            <div class="row">
                <div class="mb-3 col-md-6">
                    <label class="form-label">Has Registration</label>
                    <select name="has_registartion" class="form-select">
                        <option value="1" {{ old('has_registartion') == 1 ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ old('has_registartion') == 0 ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label">Related Events</label>
                    <select name="related_events[]" class="form-select select2" multiple>
                        @foreach($relatedEvents as $id => $title)
                            <option value="{{ $id }}" {{ in_array($id, old('related_events', [])) ? 'selected' : '' }}>
                                {{ $title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Event Image</label>
                <input type="file" name="event_image" class="form-control">
                @error('event_image')
                    <p class="small text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">Create Event</button>
            </div>
        </form>
    </div>
    
</div>
@endsection

@section('scripts')
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

    
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: 'Select related events',
            allowClear: true
        });
    });
    

</script>
@endsection
