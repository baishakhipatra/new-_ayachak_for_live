@extends('admin.layouts.app')
@section('page', 'Distributor MOM')

@section('content')
    <section>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="search__filter">
                            <div class="row align-items-center justify-content-between">
                                <div class="col">
                                    <ul>
                                        <li class="active"><a href="{{ route('admin.distributor.directory') }}">All <span class="count">({{$data->total()}})</span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <form action="{{ route('admin.distributor.directory') }}">
                                <div class="row g-3 align-items-center">
                                    <div class="col-auto">
                                        <input type="search" name="term" id="term" class="form-control" placeholder="Search here.." value="{{app('request')->input('term')}}" autocomplete="off">
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Search Distributor MOM </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User Name</th>
                                    <th>Distributor Name</th>
                                    <th>Comment</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $index => $item)
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td> {{$item->user->fname.' '.$item->user->lname}}</td>
                                        <td>{{ $item->distributor_name}}</td>
                                        <td>{{ $item->comment }}</td>
                                        <td>{{ $item->created_at  }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="small text-muted">No data found</td>
                                    </tr>
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

