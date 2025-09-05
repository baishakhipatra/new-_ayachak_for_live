@extends('admin.layouts.app')

@section('page', 'Target')

@section('content')
<section>
    <div class="row">
        <form action="" method="get">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Select Collection</label>
                        <select name="collection_id" class="form-control">
                            <option value="">--- Select  ---</option>
                            @foreach ($collections as $item)
                                <option value="{{$item->name}}" {{ ($item->name == request()->input('name')) ? 'selected' : '' }}>{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Year from</label>
                        <input type="text" name="year_from" class="form-control" value="{{ (request()->input('year_from')) ? request()->input('year_from') : date('Y') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Year to</label>
                        <input type="text" name="year_to" class="form-control" value="{{ (request()->input('year_to')) ? request()->input('year_to') : date('Y', strtotime('+1 year')) }}">
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
        <div class="col-sm-12">
            <div class="col-auto">

                <div class="col-auto">
                    <a type="button" href="{{ route('admin.target.create') }}" class="btn btn-outline-danger btn-sm float-right">Add</a>
                </div>
            </div>

        </div>
            <div class="card">
                <div class="card-body">

                    <div class="search__filter">
                        <div class="row align-items-center justify-content-between">
                            <div class="col">
                                <ul>
                                    <li class="active"><a href="{{ route('admin.target.index') }}">All <span class="count">({{$data->count()}})</span></a></li>
                                    @php
                                    $activeCount = $inactiveCount = 0;
                                    foreach ($data as $catKey => $catVal) {
                                    if ($catVal->status == 1) $activeCount++;
                                    else $inactiveCount++;
                                    }
                                    @endphp
                                    <li><a href="{{ route('admin.target.index', ['status' => 'active'])}}">Active <span class="count">({{$activeCount}})</span></a></li>
                                    <li><a href="{{ route('admin.target.index', ['status' => 'inactive'])}}">Inactive <span class="count">({{$inactiveCount}})</span></a></li>
                                </ul>
                            </div>
                        </div></div>
                        <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Collection</th>
                                <th>Person Name</th>
                                <th>Amount</th>
                                <th>Year</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($target as $index => $item)
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
                            <td>{{$item->id}}</td>
                            <td>
                                {{$item->title}}
                                <div class="row__action">
                                    <a href="{{ route('admin.target.edit', $item->id) }}">Edit</a>
                                    <a href="{{ route('admin.target.view', $item->id) }}">View</a>
                                    <a href="{{ route('admin.target.status', $item->id) }}">{{($item->status == 1) ? 'Active' : 'Inactive'}}</a>
                                    <a href="{{ route('admin.target.delete', $item->id) }}" class="text-danger">Delete</a>
                                </div>
                            </td>
                            <td>{{$item->collection->name}}</td>
                            <td>{{$item->user->name}}(@if($item->user_type==1) VP @elseif($item->user->user_type==2) RSM @elseif($item->user->user_type==3)ASM @elseif ($item->user->user_type==4)RSE @elseif ($item->user->user_type==5)Distributor @elseif ($item->user->user_type==6)Retailer @endif)</td>
                            <td>{{$item->amount}}</td>
                            <td>{{$item->year_from}} -<br>{{$item->year_to}}</td>
                            <td><span class="badge bg-{{($item->status == 1) ? 'success' : 'danger'}}">{{($item->status == 1) ? 'Active' : 'Inactive'}}</span></td>

                            </tr>
                            @empty
                            <tr><td colspan="100%" class="small text-muted">No data found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>
</section>
@endsection
