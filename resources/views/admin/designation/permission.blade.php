@extends('admin.layouts.app')

@section('page', 'Permission - List')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="fw-bold mb-0">Manage Permissions for: {{ ucwords($designation->name)}}</h4>
            <a href="{{ route('admin.designation.index') }}" class="btn btn-sm btn-danger">
                <i class="menu-icon tf-icons ri-arrow-left-line"></i>
                Back
            </a>
        </div>
        <form method="POST" action="{{ route('admin.designation.permissions.update') }}">
            @csrf
            <input type="hidden" name="designation_id" value="{{ $designation->id }}">
    
            @foreach($permissions->groupBy('parent_name') as $group => $groupPermissions)
                @php
                   $parentPermissionId = App\Models\Permission::where('parent_name', $group)->where('name',$group)->first();
                @endphp 
                <div class="card-header" style="background: #cedaff;">
                    @if($parentPermissionId)
                        <input type="checkbox" class="form-check-input" value="{{ $parentPermissionId->id }}" id="perm_{{ $parentPermissionId->id }}" {{ in_array($parentPermissionId->id, $assignedPermissions) ? 'checked' : '' }} onchange="updatePermissionAjax(this, {{ $designation->id }})">
                    @endif
                    <h5 class="text-primary mb-0">{{ ucwords(str_replace('_', ' ', $group)) }}</h5>
                </div>

                <div class="card-body mt-2">
                    <div class="row">
                        @php $chunked = $groupPermissions->chunk(8); 
                        @endphp
                        @foreach($chunked as $chunk)
                            <div class="col-md-6">
                                @foreach($chunk as $permission)
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" value="{{ $permission->id }}" id="perm_{{ $permission->id }}"
                                            {{ in_array($permission->id, $assignedPermissions) ? 'checked' : '' }}
                                            onchange="updatePermissionAjax(this, {{ $designation->id }})">

                                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                                            {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
            <div class="card-footer text-end">
                
            </div>
        </form>
    </div>
@endsection
@section('script')
<script>
    function updatePermissionAjax(checkbox, designationId) {
        const permissionId = checkbox.value;
        const isChecked = checkbox.checked;
        
        fetch("{{ route('admin.designation.permissions.ajax')}}", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                designation_id: designationId,
                permission_id: permissionId,
                checked: isChecked
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                toastFire('success','Permission updated successfully');
            } else {
                toastFire(data.message || 'error','Failed to update permission');
                checkbox.checked = !isChecked; // revert checkbox
            }
        })
        .catch(() => {
            toastFire('error','Error occurred while updating permission');
            checkbox.checked = !isChecked; // revert checkbox
        });
    }

      // Parent checkbox "check all" logic
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.card-header .form-check-input').forEach(parentCheckbox => {
            parentCheckbox.addEventListener('change', function () {
                const designationId = {{ $designation->id }};
                const cardBody = this.closest('.card-header').nextElementSibling; // get the related body

                if (cardBody) {
                    const childCheckboxes = cardBody.querySelectorAll('.form-check-input');
                    childCheckboxes.forEach(child => {
                        child.checked = this.checked;
                        updatePermissionAjax(child, designationId); // call existing AJAX update
                    });
                }
            });
        });
    });

</script>
@endsection
