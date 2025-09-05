@extends('admin.layouts.app')

@section('page', 'Order')

@section('content')
<section>
    @if (request()->input('fname'))
    <p class="text-muted">{{request()->input('fname')}}</p>
    @endif
    <div class="search__filter">
        <div class="row align-items-center justify-content-between">
        <div class="col">
            {{-- <ul>
            <li class="active"><a href="#">All <span class="count">({{$data->count()}})</span></a></li>
            <li><a href="#">Active <span class="count">(7)</span></a></li>
            <li><a href="#">Inactive <span class="count">(3)</span></a></li>
            </ul> --}}
        </div>
        <div class="col-auto">
            <form action="{{ route('admin.distributor.order.index')}}" method="GET">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                    <input type="search" name="term" id="term" class="form-control" placeholder="Search here.."
                    value="{{app('request')->input('term')}}"
                    autocomplete="off">
                    </div>
                    <div class="col-auto">
                    <button type="submit" class="btn btn-outline-danger btn-sm">Search</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.distributor.order.index',['export_all'=>'true']) }}"  class="btn btn-outline-danger btn-sm float-right"><i class="fa fa-cloud-download"></i> CSV Export</a></div>
        </div>
    </div>

    <div class="filter">
        <div class="row align-items-center justify-content-between">
        <div class="col">

        </div>
        <div class="col-auto">
            <p>{{$data->count()}} Items</p>
        </div>
        </div>
    </div>

    <table class="table" id="example5">
        <thead>
        <tr>
            <th>SL No</th>
            <th>Order No</th>
            <th>Name</th>
            <th>Amount</th>
            <th>Order time</th>

        </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $item)
            {{-- {{ dd($item->orderProducts[0]->qty) }} --}}
            <tr>
                <td>
                    {{ $index+1 }}
                 </td>
                <td>
                    <p class="small text-dark mb-1">{{$item->order_no}}</p>
                    <div class="row__action">
                        <a href="{{ route('admin.distributor.order.details', $item->id) }}">View</a>
                    </div>
                </td>
                <td>
                    <p class="small text-dark mb-1">{{$item->fname.' '.$item->lname}}</p>
                    <p class="small text-muted mb-0">{{$item->email.' | '.$item->mobile}}</p>

                </td>
                <td>
                    <p class="small text-muted mb-1">Rs {{$item->final_amount}}</p>
                </td>

                <td>
                    <p class="small">{{date('j M Y g:i A', strtotime($item->created_at))}}</p>
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

        data.push("SRNO,OrderNo/OrderPerson,Name,Amount,OrderTime");

        for (var i = 0; i < rows.length; i++) {
            var row = [],
                cols = rows[i].querySelectorAll("td");

            for (var j = 0; j < cols.length ; j++) {

                var text = cols[j].innerText.split(' ');
                var new_text = text.join('-');
                if (j == 3||j==5)
                    var comtext = new_text.replace(/\n/g, "-");
                else
                    var comtext = new_text.replace(/\n/g, ",");
                row.push(comtext);

            }
            data.push(row.join(","));

        }

        downloadCSVFile(data.join("\n"), 'DistributorOrder.csv');
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
                    window.location.href = "{{ route('admin.distributor.order.index') }}";
                </script>
            @endif
@endsection
