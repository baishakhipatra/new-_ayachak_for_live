@extends('admin.layouts.app')

@section('page', 'Static Page Management')

@section('content')

<div class="card">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card-footer d-flex justify-content-end">
        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary mb-3">Add New Page</a>
    </div>
</div>

<div class="card-body">
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pages as $index => $page)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ ucwords($page->title) }}</td>
                        <td>{{ $page->slug }}</td>
                        <td>                 
                            <div class="form-check form-switch" data-bs-toggle="tooltip" title="Toggle status">
                                <input class="form-check-input ms-auto" type="checkbox" id="customSwitch{{$page->id}}"
                                {{ $page->status ? 'checked' : ''}} onclick="statusToggle('{{route('admin.pages.status', $page->id)}}', this)">
                                <label class="form-check-label" for="customSwitch{{$page->id}}"></label>
                            </div>
                        </td>
                        <td>{{ $page->created_at->format('d-m-Y') }}</td>
                        <td> 
                            <div class="btn-group" role="group" aria-label="Action Buttons">
                                <div>
                                    <a href="{{ route('admin.pages.show', $page->id) }}" class="btn btn-sm btn-icon btn-outline-primary"
                                        data-bs-toggle="tooltip" title="View">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </div>
                                <div>
                                    <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-sm btn-icon btn-outline-dark"                     
                                        data-bs-toggle="tooltip"  title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </div>
                                
                                <div>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-icon btn-outline-danger" onclick="deleteUser({{ $page->id }})"     
                                        data-bs-toggle="tooltip" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div> 
                            </div>   
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No pages found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('script')

<script>
    function deleteUser(userId) {
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
                url: "{{ route('admin.pages.delete')}}",
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
