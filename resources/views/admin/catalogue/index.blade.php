@extends('admin.layouts.app')
@section('page', 'Catalogue')
@section('content')
<section>
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="search__filter">
                        <div class="row align-items-center justify-content-between">
                            <div class="col">
                                <ul>
                                    <li class="active"><a href="{{ route('admin.catalogue.index') }}">All <span class="count">({{$data->total()}})</span></a></li>
                                    @php
                                    $activeCount = $inactiveCount = 0;
                                    foreach ($data as $catKey => $catVal) {
                                    if ($catVal->is_current == 1) $activeCount++;
                                    else $inactiveCount++;
                                    }
                                    @endphp
                                    <li><a href="{{ route('admin.catalogue.index', ['is_current' => 'active'])}}">Active <span class="count">({{$activeCount}})</span></a></li>
                                    <li><a href="{{ route('admin.catalogue.index', ['is_current' => 'inactive'])}}">Inactive <span class="count">({{$inactiveCount}})</span></a></li>
                                </ul>
                            </div>
                            <div class="col-auto">
                                <form action="{{ route('admin.catalogue.index')}}">
                                <div class="row g-3 align-items-center">
                                    <div class="col-auto">
                                        <input type="search" name="term" id="term" class="form-control" placeholder="Search here.." value="{{app('request')->input('term')}}" autocomplete="off">
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Search Catalogue</button>
                                    </div>
                                </div>
                                </form>

                            </div>
                            <div class="col-auto">
                            <a href="{{ route('admin.catalogue.index',['export_all'=>'true']) }}"  class="btn btn-outline-danger btn-sm float-right"><i class="fa fa-cloud-download"></i> CSV Export</a></div>
                        </div>
                    </div>
                        <table class="table" id="example5">
                            <thead>
                                <tr>
                                    <th>Sl NO</th>
                                    <th class="text-center"><i class="fi fi-br-picture"></i> Image</th>
                                    <th class="text-center"><i class="fi fi-br-picture"></i> Pdf</th>
                                    <th>Title</th>
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
                                    <td>{{ $index+1 }}</td>
                                    <td class="text-center column-thumb">
                                        <img src="{{ asset($item->image) }}">
                                    </td>
                                    <td class="text-center column-thumb">
                                        <a href="{{ asset($item->pdf) }}" target="_blank"><i class="app-menu__icon fa fa-download"></i>Pdf</a>
                                    </td>
                                    <td>
                                        <h3 class="text-dark">{{$item->title}}</h3>
                                        <div class="row__action">
                                            <a href="{{ route('admin.catalogue.view', $item->id) }}">Edit</a>
                                            <a href="{{ route('admin.catalogue.view', $item->id) }}">View</a>
                                            <a href="{{ route('admin.catalogue.status', $item->id) }}">{{($item->is_current == 1) ? 'Active' : 'Inactive'}}</a>
                                            <a href="{{ route('admin.catalogue.delete', $item->id) }}" class="text-danger">Delete</a>
                                        </div>
                                    </td>
                                    <td>{{$item->start_date}}-{{ $item->end_date}}</td>
                                    {{-- <td>Published<br/>{{date('d M Y', strtotime($item->created_at))}}</td> --}}
                                    <td>
                                        <span class="badge bg-{{($item->is_current == 1) ? 'success' : 'danger'}}">{{($item->is_current == 1) ? 'Active' : 'Inactive'}}</span>
                                    </td>
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
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.catalogue.store') }}" enctype="multipart/form-data">
                    @csrf
                        <h4 class="page__subtitle">Add New Catalogue</h4>
                        <div class="form-group mb-3">
                            <label class="label-control">Name <span class="text-danger">*</span> </label>
                            <input type="text" name="title" placeholder="" class="form-control" value="{{old('title')}}">
                            @error('title') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Start Date </label>
                            <input type="date" name="start_date" class="form-control">{{old('start_date')}}</textarea>
                            @error('start_date') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">End Date </label>
                            <input type="date" name="end_date" class="form-control">{{old('end_date')}}</textarea>
                            @error('end_date') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-12 col-md-6 col-xl-12">
                            <div class="row">
                                <div class="col-md-6 card">
                                    <div class="card-header p-0 mb-3">Image <span class="text-danger">*</span></div>
                                    <div class="card-body p-0">
                                        <div class="w-100 product__thumb">
                                            <label for="icon"><img id="iconOutput" src="{{ asset('backend_asset/images/placeholder-image.jpg') }}" /></label>
                                        </div>
                                        <input type="file" name="image" id="icon" accept="image/*" onchange="loadIcon(event)" class="d-none">
                                        <script>
                                            let loadIcon = function(event) {
                                                let iconOutput = document.getElementById('iconOutput');
                                                iconOutput.src = URL.createObjectURL(event.target.files[0]);
                                                iconOutput.onload = function() {
                                                    URL.revokeObjectURL(iconOutput.src) // free memory
                                                }
                                            };
                                        </script>
                                    </div>
                                    @error('image') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-6 card">
                                    <div class="card-header p-0 mb-3">Pdf <span class="text-danger">*</span></div>
                                    <div class="card-body p-0">
                                        <div class="w-100 product__thumb">
                                        </div>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="file" name="pdf" id="pdf">
                                       </div>
                                    </div>
                                    @error('pdf') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-danger">Add New Catalogue</button>
                        </div>
                    </form>
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

        data.push("SRNO,Image,Pdf,Title,Date,Status");

        for (var i = 0; i < rows.length; i++) {
            var row = [],
                cols = rows[i].querySelectorAll("td");

            for (var j = 0; j < cols.length; j++) {
                var text = cols[j].innerText.split(' ');
                var new_text = text.join('-');
                if (j == 3||j==4)
                    var comtext = new_text.replace(/\n/g, "-");
                else
                    var comtext = new_text.replace(/\n/g, ";");
                row.push(comtext);

            }
            data.push(row.join(","));
        }

        downloadCSVFile(data.join("\n"), 'Catalogue.csv');
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
                    window.location.href = "{{ route('admin.catalogue.index') }}";
                </script>
            @endif
@endsection
