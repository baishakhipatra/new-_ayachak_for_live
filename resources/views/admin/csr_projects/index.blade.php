@extends('admin.layouts.app')

@section('page', 'CSR Project')

@section('content')

<div class="card">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card-footer d-flex justify-content-end">
            <a href="{{ route('admin.csr_projects.create') }}" class="btn btn-primary mb-3">Add New Project</a>
    </div>
</div>

<div class="card-body">
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Tags</th>
                    <th>Image</th>
                    <th>File</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $project)
                    <tr>
                        <td>{{ ucwords($project->title) }}</td>
                        <td>
                            @foreach($project->tags as $tag)
                                <span class="badge bg-info">{{ ucwords($tag->name) }}</span>
                            @endforeach
                        </td>
                        <td>
                            @if($project->image)
                                <img src="{{ asset('storage/' . $project->image) }}" width="100">
                            @endif
                        </td>
                        <td>
                            @if($project->file)
                                <a href="{{ asset('storage/' . $project->file) }}" target="_blank">Download</a>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Action Buttons">
                                <div>
                                    <a href="{{ route('admin.csr_projects.edit', $project) }}" class="btn btn-sm btn-icon btn-outline-dark"                     
                                        data-bs-toggle="tooltip"  title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </div>


                                <div>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-icon btn-outline-danger" onclick="deleteUser({{ $project->id }})"     
                                        data-bs-toggle="tooltip" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No CSR Projects found.</td></tr>
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
                url: "{{ route('admin.csr_projects.delete')}}",
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