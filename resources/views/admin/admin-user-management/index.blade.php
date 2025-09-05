@extends('admin.layouts.app')

@section('page', 'Admin-User-Management')

@section('content')


@if(session('success'))
    <div class="alert alert-success" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<!-- Basic Bootstrap Table -->
<div class="card">
  <div class="card-footer d-flex justify-content-end">
    <a href="{{ route('admin.admin-user-management.create') }}" class="btn btn-primary btn-sm">+ Add Admin User</a>
  </div>

  <div class="px-3 py-2">
    <form action="" method="get">
      <div class="row">
        <div class="col-md-6"></div>
          <div class="col-md-6">  
            <div class="d-flex justify-content-end">
              <div class="form-group me-2 mb-0">
                <input type="search" class="form-control form-control-sm" name="keyword" id="keyword" value="{{ request()->input('keyword') }}" placeholder="Search something...">
              </div>
              <div class="form-group mb-0">
                <div class="btn-group">
                  <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fa fa-filter"></i>
                  </button>
                  <a href="{{ url()->current() }}" class="btn btn-sm btn-light" data-toggle="tooltip" title="Clear filter">
                    <i class="fa fa-close"></i>
                  </a>
                  {{-- @if (hasPermissionByChild('export_employee_list')) --}}
                    <div>
                      <a href="{{ route('admin.admin-user-management.export', ['keyword' => request()->input('keyword')]) }}" 
                        class="btn buttons-collection btn-outline-secondary waves-effect" 
                        data-toggle="tooltip" title="Export Data">
                        Export<i class="fa fa-download"></i>
                      </a>
                    </div>
                  {{-- @endif --}}
                </div>
              </div>
            </div>
          </div>
      </div>
    </form>
  </div>

  <div class="card-body">
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            {{-- <th>Employee ID</th> --}}
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @foreach($admins as $item)
            <tr>
              {{-- <td>{{ $item->user_id }}</td> --}}
              <td>{{ ucfirst($item->name) }}</td>
              <td>{{ $item->email }}</td>
              <td>{{ $item->phone }}</td>
              <td>
                 <div class="form-check form-switch" data-bs-toggle="tooltip" title="Toggle status">
                    <input class="form-check-input ms-auto" type="checkbox" id="customSwitch{{$item->id}}"
                      {{ $item->status ? 'checked' : ''}} onclick="statusToggle('{{route('admin.admin-user-management.status', $item->id)}}', this)">
                    <label class="form-check-label" for="customSwitch{{$item->id}}"></label>
                  </div>
              </td>
              {{-- View, Edit and delete --}}
              <td>
                <div class="btn-group" role="group" aria-label="Action Buttons">
                  {{-- @if (hasPermissionByChild('edit_employee')) --}}
                    <div>
                      <a href="{{ route('admin.admin-user-management.edit', $item->id) }}" class="btn btn-sm btn-icon btn-outline-dark"                     
                        data-bs-toggle="tooltip"  title="Edit">
                        <i class="fa fa-edit"></i>
                      </a>
                    </div>
                  {{-- @endif --}}
                  
                  {{-- @if (hasPermissionByChild('delete_employee')) --}}
                    <div>
                      <a href="javascript:void(0);" class="btn btn-sm btn-icon btn-outline-danger" onclick="deleteUser({{ $item->id }})"     
                        data-bs-toggle="tooltip" title="Delete">
                        <i class="fa fa-trash"></i>
                      </a>
                    </div>    
                  {{-- @endif            --}}
                </div>
              </td>
 
            </tr>
          @endforeach         
        </tbody>
      </table>
      {{-- Pagination Links --}}
      <div class="pagination-container">
          {{$admins->links()}}
      </div>
    </div>
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
                url: "{{ route('admin.admin-user-management.delete')}}",
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