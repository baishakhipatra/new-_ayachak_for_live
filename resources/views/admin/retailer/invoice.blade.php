@extends('admin.layouts.app')

@section('page', 'Invoice')

@section('content')
<section>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">

                    <div class="search__filter">
                        <div class="row align-items-center justify-content-between">
                            <div class="col">
                                {{-- <ul>
                                    <li class="active"><a href="{{ route('admin.retailer.invoice.index',$data->id) }}">All <span class="count">({{$data->count()}})</span></a></li>
                                    @php
                                        $activeCount = $inactiveCount = 0;
                                        foreach ($data as $key => $collValue) {
                                            if($collValue->status == 1) $activeCount++;
                                            else $inactiveCount++;
                                        }

                                    @endphp
                                    <li><a href="{{ route('admin.retailer.invoice.index', ['status' => 'active'])}}">Active <span class="count">{{$activeCount}}</span></a></li>
                                    <li><a href="{{ route('admin.retailer.invoice.index', ['status' => 'inactive'])}}">Inactive <span class="count">{{$inactiveCount}}</span></a></li>
                                </ul> --}}
                            </div>
                            {{-- <div class="col-auto">
                                <form action="{{ route('admin.retailer.invoice.index',$data->id)}}">
                                <div class="row g-3 align-items-center">
                                    <div class="col-auto">
                                        <input type="search" name="term" id="term" class="form-control" placeholder="Search here.." value="{{app('request')->input('term')}}" autocomplete="off">
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Search Invoice</button>
                                    </div>
                                </div>
                                </form>
                            </div> --}}
                        </div>
                    </div>


                        <table class="table">
                            <thead>
                                <tr>

                                    <th><span class="text-dark mx-3">#</span></th>
                                    <th>Store Name</th>
                                    <th>Images</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $index => $item)
                                {{-- {{ dd($item->retailer) }} --}}
                                <tr>
                                    <td class="text-dark mx-3">{{ $index + 1 }}</td>
                                    <td>
                                        <h3 class="text-dark"></h3>

                                    </td>
                                    <td><img src="{{ asset('uploads/invoice/' . $item->image) }}" width="120px" height="120px"></td>
                                    <td>Rs.{{ number_format($item->amount) }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>Published<br/>{{date('d M Y', strtotime($item->created_at))}}</td>

                                </tr>
                                @empty
                                <tr><td colspan="100%" class="small text-muted">No data found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>


    </div>
</section>
@endsection
