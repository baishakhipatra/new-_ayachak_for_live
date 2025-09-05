@extends('admin.layouts.app')

@section('page', 'Tags')

@section('content')

<div class="card">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card-footer d-flex justify-content-end">
            <a href="{{ route('admin.tags.create') }}" class="btn btn-primary mb-3">Add New Tag</a>
    </div>
</div>

<div class="card-body">
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Projects Count</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tags as $tag)
                    <tr>
                        <td>{{ ucwords($tag->name) }}</td>
                        <td>{{ $tag->csrProjects->count() }}</td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Action Buttons">
                                <div>
                                    <a href="{{ route('admin.tags.edit', $tag->id) }}" class="btn btn-sm btn-icon btn-outline-dark"                     
                                        data-bs-toggle="tooltip"  title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </div>


                                <div>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-icon btn-outline-danger" onclick="deleteUser({{ $tag->id }})"     
                                        data-bs-toggle="tooltip" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3">No tags found.</td></tr>
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
                url: "{{ route('admin.tags.delete')}}",
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