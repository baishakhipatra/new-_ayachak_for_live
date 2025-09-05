@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Update Event</h4>

    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.events.index') }}" class="btn btn-danger">
        <i class="ri-arrow-left-line"></i> Back
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.events.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $event->id }}">

            <div class="row">
                <div class="mb-3 col-md-6">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $event->title) }}">
                    @error('title') <p class="small text-danger">{{ $message }}</p> @enderror
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label">Venue</label>
                    <input type="text" name="venue" class="form-control" value="{{ old('venue', $event->venue) }}">
                    @error('venue') <p class="small text-danger">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" id="editor" class="form-control" rows="5">{{ old('description', $event->description) }}</textarea>
                @error('description') <p class="small text-danger">{{ $message }}</p> @enderror
            </div>

            <div class="row">
                <div class="mb-3 col-md-6">
                    <label class="form-label">Start Time</label>
                    <input type="datetime-local" name="start_time" class="form-control"
                        value="{{ old('start_time', $event->start_time ? date('Y-m-d\TH:i', strtotime($event->start_time)) : '') }}">
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label">End Time</label>
                    <input type="datetime-local" name="end_time" class="form-control"
                        value="{{ old('end_time', $event->end_time ? date('Y-m-d\TH:i', strtotime($event->end_time)) : '') }}">
                </div>
            </div>

            <div class="row">
                <div class="mb-3 col-md-6">
                    <label class="form-label">Has Registration</label>
                    <select name="has_registartion" class="form-select">
                        <option value="1" {{ old('has_registartion', $event->has_registartion) == 1 ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ old('has_registartion', $event->has_registartion) == 0 ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label">Related Events</label>
                    <select name="related_events[]" class="form-select select2" multiple>
                        @foreach($relatedEvents as $id => $title)
                            <option value="{{ $id }}" 
                                {{ in_array($id, old('related_events', json_decode($event->related_events ?? '[]', true))) ? 'selected' : '' }}>
                                {{ $title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- <div class="mb-3">
                <label class="form-label">Event Image</label>
                <input type="file" name="event_image" class="form-control">
                    @if($event->event_image)
                        <img src="{{ public_path('uploads/events/'.$event->event_image) }}" alt="" width="100" class="mt-2">
                    @endif
                @error('event_image') <p class="small text-danger">{{ $message }}</p> @enderror
            </div> --}}

            <div class="mb-3">
                <label class="form-label">Event Image</label>

                @if($event->eventImage)
                    @php
                        $imagePath = public_path($event->eventImage->image_path);
                        $assetPath = asset($event->eventImage->image_path);
                    @endphp
                    @if(file_exists($imagePath))
                        <div class="mt-2 mb-2">
                            <img src="{{ $assetPath }}" alt="Event Image" class="img-thumbnail" width="150">
                        </div>
                    @endif
                @endif
                <input type="file" name="event_image" class="form-control">
                @error('event_image') 
                    <p class="small text-danger">{{ $message }}</p> 
                @enderror
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">Update Event</button>
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