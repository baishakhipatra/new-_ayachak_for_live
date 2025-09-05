@extends('admin.layouts.app')
@section('page', 'Distributor List')
@section('content')
<section>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                <div class="row align-items-center justify-content-between">
                    <div class="col">
                        <ul>
                            <li class="active"><a href="{{ route('admin.distributor.index') }}">All <span class="count">({{$data->total()}})</span></a></li>
                            {{-- @php
                            $activeCount = $inactiveCount = 0;
                            foreach ($data as $catKey => $catVal) {
                            if ($catVal->status == 1) $activeCount++;
                            else $inactiveCount++;
                            }
                            @endphp
                            <li><a href="{{ route('admin.user.index', ['status' => 'active'])}}">Active <span class="count">({{$activeCount}})</span></a></li>
                            <li><a href="{{ route('admin.user.index', ['status' => 'inactive'])}}">Inactive <span class="count">({{$inactiveCount}})</span></a></li> --}}
                        </ul>
                    </div>
                    <div class="col-auto">
                            <form action="{{ route('admin.distributor.index')}}">
                                <div class="row g-3 align-items-center">
                                    <div class="col-auto">
                                        <input type="search" name="term" id="term" class="form-control" placeholder="Search here.." value="{{app('request')->input('term')}}" autocomplete="off">
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Search </button>
                                    </div>
                                </div>
                            </form>
                            <div class="col-auto">
                                <a type="button" href="{{ route('admin.distributor.create') }}" class="btn btn-outline-danger btn-sm float-right">Add</a>
                            </div>
                     </div>
                </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>VP</th>
                                <th>RSM</th>
                                <th>ASM</th>
                                <th>ASE</th>
                                <th>Distributor</th>
                                <th>Retailer</th>
                                <th>State</th>
                                <th>Area</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $index => $item)
                            <tr>
                                <td>{{ $item->vp }}</td>
                                <td>{{ $item->rsm }}</td>
                                <td>{{ $item->asm }}</td>
                                <td>{{ $item->ase }}</td>
                                <td>
                                    {{$item->distributor_name}}
                                    {{-- <div class="row__action">
                                        <a href="{{ route('admin.distributor.edit', $item->id) }}">Edit</a>
                                        <a href="{{ route('admin.distributor.view', $item->id) }}">View</a>
                                        <a href="{{ route('admin.distributor.status', $item->id) }}">{{($item->is_active == 1) ? 'Active' : 'Inactive'}}</a>
                                        <a href="{{ route('admin.distributor.delete', $item->id) }}" class="text-danger">Delete</a>

                                    </div> --}}
                                    </td>
                                    <td>{{ $item->retailer }}</td>
                                <td>{{ $item->state }}</td>
                                <td>{{ $item->area }}</td>
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
