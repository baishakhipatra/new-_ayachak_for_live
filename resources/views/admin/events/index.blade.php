@extends('admin.layouts.app')

@section('page', 'Event List')

@section('content')


<section class="event-stack">
    <div class="container">
        {{-- <h2 class="mb-4">Event Listing</h2> --}}
        <form method="GET" action="{{ route('admin.events.index') }}" class="row g-3 mb-4">
            <div class="row g-3 align-items-center">
                <div class="col-auto">
                    <label for="term" class="form-label">Search:</label>
                    <input type="keyword" name="term" id="term" class="form-control"
                        placeholder="Search Title or Venue"
                        value="{{ request('term') }}"
                        autocomplete="off">
                </div>

                <div class="col-auto">
                    <label for="term" class="form-label" style="color: transparent;">Action:</label>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-outline-danger btn-sm">Search</button>
                        <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fi fi-br-cube"></i> Remove filters
                        </a>
                    </div>
                </div>

                <div class="col-auto">
                    <label for="add-event" class="form-label" style="color: transparent;">Action:</label>
                    <div class="col-auto">
                        <a href="{{ route('admin.events.create') }}" class="btn btn-outline-success btn-sm" id="add-event">
                            <i class="fas fa-plus"></i> Add Event
                        </a>
                    </div>
                </div>
            </div>
        </form>


        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Image</th>
                        <th>Title</th>                       
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        <tr>
                            <td width="80">
                                @php
                                    $firstImagePath = optional($event->eventImage)->image_path;
                                @endphp

                                @if (!empty($firstImagePath) && file_exists(public_path($firstImagePath)))
                                    <img src="{{ asset($firstImagePath) }}" class="img-fluid rounded" alt="Event Image">
                                @else
                                    <img src="{{ asset('assets/images/no-image.png') }}" class="img-fluid rounded" alt="Default Image">
                                @endif
                            </td>
                            
                            <td>{{ ucwords($event->title) }}</td>
                            <td>{{date('d-m-Y h:i A',strtotime($event->start_time))}}</td>
                            <td>{{date('d-m-Y h:i A',strtotime($event->end_time))}}</td>
                            <td> 
                                <div class="form-check form-switch" data-bs-toggle="tooltip" title="Toggle status">
                                    <input class="form-check-input ms-auto" type="checkbox" id="customSwitch{{$event->id}}"
                                    {{ $event->status ? 'checked' : ''}} onclick="statusToggle('{{route('admin.events.status', $event->id)}}', this)">
                                    <label class="form-check-label" for="customSwitch{{$event->id}}"></label>
                                </div>
                            </td>
                            <td>
                                {{-- <a href="{{ route('front.event.details', $event->slug) }}" class="btn btn-sm btn-primary">View</a> --}}
                                <!-- You can add Edit/Delete here for admin -->
                                <div>
                                    <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-sm btn-icon btn-outline-dark"                     
                                        data-bs-toggle="tooltip"  title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <div>
                                        <a href="javascript:void(0);" class="btn btn-sm btn-icon btn-outline-danger" onclick="deleteEvent({{ $event->id }})"     
                                            data-bs-toggle="tooltip" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div> 
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No events found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $events->links() }}
        </div>
    </div>
</section>

@endsection

@section('script')
<script>
    function deleteEvent(userId) {
        Swal.fire({
            icon: 'warning',
            title: "Are you sure you want to delete this?",
            text: "You won't be able to revert this!",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Delete",
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('admin.events.delete')}}",
                    type: 'POST',
                    data: {
                        "id": userId,
                        "_token": '{{ csrf_token() }}',
                    },
                    success: function (data){
                        if (data.status != 200) {
                            toastFire('error', data.message);
                        } else {
                            toastFire('success', data.message);
                            location.reload();
                        }
                    }
                });
            }
        });
    }
</script>
@endsection