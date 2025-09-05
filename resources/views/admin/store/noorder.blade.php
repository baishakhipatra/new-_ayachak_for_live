@extends('admin.layouts.app')
@section('page', 'No Order Reason')
@section('content')
<section>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="search__filter">
                        <div class="row align-items-center justify-content-between">
                            <div class="col">
                            </div>
                            <div class="col-auto">
                                <form action="{{ route('admin.offer.index')}}">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-auto">
                                            <input type="search" name="term" id="term" class="form-control" placeholder="Search here.." value="{{app('request')->input('term')}}" autocomplete="off">
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>

                                <th>Name</th>
                                <th>Store Name</th>
                                <th>Comment</th>
                                <th>Location</th>
                                <th>Date</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $index => $item)
                            {{-- {{ dd($item->user) }} --}}
                                <tr>

                                    <td>
                                        {{$item->user ? $item->user->name : ''}}
                                    </td>
                                    <td>
                                        {{$item->store ? $item->store->store_name : ''}}
                                    </td>
                                    <td>
                                        {{$item->comment}}
                                    </td>
                                    <td>
                                        {{$item->location}}
                                    </td>
                                    <td>{{date('d M Y', strtotime($item->date))}} {{ $item->time}}</td>
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
