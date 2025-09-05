@extends('admin.layouts.app')

@section('page', 'User')

@section('content')
<style>
    .badge.bg-success {
        background-color: #8bc34a !important;
    }
    .badge.bg-warning {
        background-color: #ffc107 !important;
    }
    .badge.bg-danger {
        color: #fff;
        background-color: #ff4a21 !important;
    }
</style>

<section>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="search__filter">
                        <div class="row align-items-center justify-content-between">
                            <div class="col">
                                <ul>
                                    <li class="active"><a href="{{ route('admin.user.index') }}">All <span class="count">({{$data->count()}})</span></a></li>
                                    @php
                                    $activeCount = $inactiveCount = 0;
                                    foreach ($data as $catKey => $catVal) {
                                    if ($catVal->status == 1) $activeCount++;
                                    else $inactiveCount++;
                                    }
                                    @endphp
                                    <li><a href="{{ route('admin.user.index', ['status' => 'active'])}}">Active <span class="count">({{$activeCount}})</span></a></li>
                                    <li><a href="{{ route('admin.user.index', ['status' => 'inactive'])}}">Inactive <span class="count">({{$inactiveCount}})</span></a></li>
                                </ul>
                            </div>
                            <div class="col-auto">
                                <form action="{{ route('admin.user.index') }}" method="GET">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-auto">
                                            <select id="user_type" name="user_type" class="form-control">
                                                <option value=""> Select User type</option>
                                                <option value="1" {{(request()->input('user_type') == 1) ? 'selected' : ''}}>VP</option>
                                                <option value="2" {{(request()->input('user_type') == 2) ? 'selected' : ''}}>RSM</option>
                                                <option value="3" {{(request()->input('user_type') == 3) ? 'selected' : ''}}>ASM</option>
                                                <option value="4" {{(request()->input('user_type') == 4) ? 'selected' : ''}}>ASE</option>
                                                <option value="5" {{(request()->input('user_type') == 5) ? 'selected' : ''}}>Distributor</option>
                                                <option value="6" {{(request()->input('user_type') == 6) ? 'selected' : ''}}>Retailer</option>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <input type="search" name="keyword" class="form-control" placeholder="Search here.." id="term" value="{{ request()->input('keyword') }}" autocomplete="off">
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Filter</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-auto">
                                <a type="button" href="{{ route('admin.user.create') }}" class="btn btn-outline-danger btn-sm float-right">Add</a>
                            </div>
                            <div class="col-auto">
                            <a href="{{ route('admin.user.index',['export_all'=>'all']) }}" onclick="htmlToCSV()" class="btn btn-outline-danger btn-sm float-right"><i class="fa fa-cloud-download"></i> CSV Export</a>
                            </div>
                        </div>
                    </div>
                    <table class="table" id="example5">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Designation</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Account Verification</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $index => $item)
                            @php
                            if (!empty($_GET['status'])) {
                                if ($_GET['status'] == 'active') {
                                    if ($item->status == 0) continue;
                                } else {
                                    if ($item->status == 1) continue;
                                }
                            }
                            @endphp
                            <tr>
                                <td>
                                    {{$item->fname.' '.$item->lname}}
                                    <div class="row__action">
                                        <a href="{{ route('admin.user.edit', $item->id) }}">Edit</a>
                                        <a href="{{ route('admin.user.view', $item->id) }}">View</a>
                                        <a href="{{ route('admin.user.status', $item->id) }}">{{($item->status == 1) ? 'Active' : 'Inactive'}}</a>
                                        <a href="{{ route('admin.user.delete', $item->id) }}" class="text-danger">Delete</a>
                                        <a href="{{ route('admin.user.verification', $item->id) }}">{{($item->is_verified == 1) ? 'Verified' : 'Not verified'}}</a>
                                    </div>
                                </td>
                                <td>
                                    @if($item->user_type==1)<span class="badge bg-success">VP </span>
                                    @elseif($item->user_type==2)<span class="badge bg-danger">RSM </span>
                                    @elseif($item->user_type==3)<span class="badge bg-primary">ASM </span>
                                    @elseif($item->user_type==4)<span class="badge bg-secondary">ASE </span>
                                    @elseif($item->user_type==5)<span class="badge bg-warning text-dark">Distributor</span>
                                    @elseif($item->user_type==6)<span class="badge bg-dark">Retailer </span>@endif
                                </td>
                                <td>{{ $item->email }} <br> {{ $item->mobile }}</td>
                                <td><span class="badge bg-{{($item->status == 1) ? 'success' : 'danger'}}">{{($item->status == 1) ? 'Active' : 'Inactive'}}</span></td>
                                <td><span class="badge bg-{{($item->is_verified == 1) ? 'success' : 'danger'}}">{{($item->is_verified == 1) ? 'Verified' : 'Not verified'}}</span></td>
                            </tr>
                            @empty
                                <tr><td colspan="100%" class="small text-muted">No data found</td></tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-end">
                        {{ $data->appends($_GET)->links() }}
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
@section('script')
<script>
    function htmlToCSV() {
        var data = [];
        var rows = document.querySelectorAll("#example5 tbody tr");
        @php
            if (!request()->input('page')) {
                $page = '1';
            } else {
                $page = request()->input('page');
            }
        @endphp

        var page = "{{ $page }}";

        data.push("Name,Designation,Contact,Status,AccountVerification");

        for (var i = 0; i < rows.length; i++) {
            var row = [],
                cols = rows[i].querySelectorAll("td");

            for (var j = 0; j < cols.length - 1; j++) {
                var text = cols[j].innerText.split(' ');
                var new_text = text.join('-');
                if (j == 2)
                    var comtext = new_text.replace(/\n/g, "-");
                else
                    var comtext = new_text.replace(/\n/g, ";");
                row.push(comtext);
            }
            data.push(row.join(","));
        }

        downloadCSVFile(data.join("\n"), 'User.csv');
    }

    function downloadCSVFile(csv, filename) {
        var csv_file, download_link;

        csv_file = new Blob([csv], {
            type: "text/csv"
        });

        download_link = document.createElement("a");

        download_link.download = filename;

        download_link.href = window.URL.createObjectURL(csv_file);

        download_link.style.display = "none";

        document.body.appendChild(download_link);

        download_link.click();
    }


</script>
 @if (request()->input('export_all') == true)
                <script>
                    htmlToCSV();
                    window.location.href = "{{ route('admin.user.index') }}";
                </script>
            @endif
@endsection
