@extends('admin.layouts.app')
@section('page', 'Scan and Win')

@section('content')
<section>
    <div class="card card-body">
        <div class="search__filter mb-0">
            <div class="row align-items-center">
                <div class="col-12 text-end mb-3">
					<a href="#csvUploadModal" data-bs-toggle="modal" class="btn btn-danger btn-sm">Bulk upload</a>

                    <a href="{{ route('admin.gift.create') }}" class="btn btn-danger btn-sm">
                        Create New Gift
                    </a>
                </div>
                <div class="col-md-3">
                    <p class="small text-muted mt-1 mb-0">Showing {{$data->firstItem()}} - {{$data->lastItem()}} out of {{$data->total()}} Entries</p>
                </div>

                <div class="col-md-9 text-end">
                   
                </div>
            </div>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>#SR</th>
                <th>User count</th>
                <th>Gift name</th>
               
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $item)
			 @php
                                if (!empty($_GET['is_current'])) {
                                    if ($_GET['is_current'] == 'active') {
                                        if ($item->is_current == 0) continue;
                                    } else {
                                        if ($item->is_current == 1) continue;
                                    }
                                }
               @endphp
                <tr>
                    <td>{{ $index + $data->firstItem() }}</td>
                    <td>
                        {{$item->user_count}}
                    </td>
                    <td>
                       {{$item->gift_title}}
						<div class="row__action">
                            <a href="{{ route('admin.gift.edit', $item->id) }}">Edit</a>
                            <a href="{{ route('admin.gift.view', $item->id) }}">View</a>
							 <a href="{{ route('admin.gift.status', $item->id) }}">{{($item->is_current == 1) ? 'Active' : 'Inactive'}}</a>
                            <a href="{{ route('admin.gift.delete', $item->id) }}" class="text-danger">Delete</a>
                        </div>
                    </td>
                   
                   {{-- <td>
                        <p class="small text-muted mb-0">{{date('d M Y', strtotime($item->start_date))}} - {{date('d M Y', strtotime($item->end_date))}}</p>
                    </td> --}}
					<td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y g:i:s A')}}
                    </td>
                    <td>
                        <span class="badge bg-{{($item->status == 1) ? 'success' : 'danger'}}">{{($item->status == 1) ? 'Active' : 'Inactive'}}</span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="100%" class="small text-muted">No data found</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-end">
        {{$data->appends($_GET)->links()}}
    </div>

</section>

{{-- bulk upload variation modal --}}
<div class="modal fade" id="csvUploadModal" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Bulk Upload
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('admin.gift.csvupload') }}" enctype="multipart/form-data" id="borrowerCsvUpload">@csrf
                    <input type="file" name="file" class="form-control" accept=".csv">
                    <br>
                    <a href="{{ asset('backend_asset/gift.csv') }}">Download Sample CSV</a>
                    <br>
                    <button type="submit" class="btn btn-danger mt-3" id="csvImportBtn">Import <i class="fas fa-upload"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
 @if (session('csv'))
     <script>
         swal("Success!", "{{ session('csv') }}", "success");
     </script>
    @endif
@endsection
