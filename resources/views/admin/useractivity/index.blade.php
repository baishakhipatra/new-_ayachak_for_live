@extends('admin.layouts.app')

@section('page', 'User Activity')

@section('content')
<section>
    <div class="row">
        <form action="" method="get">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Date</label>
                        <input type="text" name="date" class="form-control" value="{{ (request()->input('date')) ? request()->input('date') : date('Y-m-d ') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Time</label>
                        <input type="text" name="time" class="form-control" value="{{ (request()->input('year_to')) ? request()->input('year_to') : date('H:i:s') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Person</label>
                        <select name="user_id" class="form-control">
                            <option value="">--- Select  ---</option>
                            @foreach ($users as $item)
                            <option value="{{$item->id}}" {{ ($item->name == request()->input('name')) ? 'selected' : '' }}>{{ $item->name }}</option>

                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Designation</label>
                       <select id="user_type" name="user_type" class="form-control">
                            <option value="">--- Select  ---</option>

                            <option value="1">VP</option>
                            <option value="2">RSM</option>
                            <option value="3">ASM</option>
                            <option value="4">ASE</option>
                            <option value="5">Distributor</option>
                            <option value="6">Retailer</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="" style="visibility: hidden;">save</label>
                        <br>
                        <button type="submit" class="btn btn-danger">Filter</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="col-md-3">
        <a href="{{ route('admin.useractivity.index',['export_all'=>'true']) }}" onclick="htmlToCSV()" class="btn btn-outline-danger btn-sm float-right"><i class="fa fa-cloud-download"></i> CSV Export</a></div>

        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <table class="table" id="example5">
                        <thead>
                            <tr>
                                <th>Sl No</th>
                                <th>User Type</th>
                                <th>User Name</th>
                                <th>Activity</th>
                                 <th>Date</th>
                                <th>Time</th>
                                <th>Comment</th>
                                <th>Location</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $index => $item)
                            <tr>
                                <td>{{ $index+1 }}</td>
                                <td> @if($item->users ? $item->users->user_type ==1 : '') <span class="badge bg-success">VP </span>@elseif($item->users ? $item->users->user_type ==2 : '') <span class="badge bg-danger">RSM </span>@elseif($item->users ? $item->users->user_type ==3 : '') <span class="badge bg-primary">ASM </span>@elseif ($item->users ? $item->users->user_type ==4 : '')<span class="badge bg-secondary">RSE </span>@elseif ($item->users ? $item->users->user_type==5 : '')<span class="badge bg-warning text-dark">Distributor</span> @elseif ($item->users ? $item->users->user_type ==6 : '')<span class="badge bg-dark">Retailer </span>@endif</td>
                                <td> {{$item->users ? $item->users->fname : ''}} {{ $item->users ? $item->users->lname : ''}} </td>
                                <td>{{$item->type}}</td>
                                <td>{{ $item->date }} </td>
                                <td> {{ $item->time }}</td>
                                <td> {{ $item->comment }}</td>
                                <td> {{ $item->location }}</td>
                                <td> {{ $item->lat }}</td>
                                <td> {{ $item->lng }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="100%" class="small text-muted">No data found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end">
                        {{ $data->links() }}
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

        data.push("SRNO.,UserType,UserName,Activity,Date,Time,Comment,Location,Latitude,Longitude");

        for (var i = 0; i < rows.length; i++) {
            var row = [],
                cols = rows[i].querySelectorAll("td");

            for (var j = 0; j < cols.length; j++) {

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

        downloadCSVFile(data.join("\n"), 'UserActivity.csv');
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
                    window.location.href = "{{ route('admin.useractivity.index') }}";
                </script>
            @endif
@endsection
