@extends('admin.layouts.app')
@section('page', 'Retailer List')
@section('content')
<section>
    <div class="row">
        <div class="col-sm-12">
            <div class="col-auto">


            </div>

        </div>
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="col-auto">
                        <form action="{{ route('admin.retailer.index')}}">
                            <div class="row g-3 align-items-center">
                                <div class="col-auto">
                                    <input type="search" name="term" id="term" class="form-control" placeholder="Search here.." value="{{app('request')->input('term')}}" autocomplete="off">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Search </button>
                                </div>
                            </div>
                        </form>
                        <a type="button" href="{{ route('admin.retailer.create') }}" class="btn btn-outline-danger btn-sm float-right">Add</a>
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
                                <th>Status</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $index => $item)
                            {{-- {{ dd($data) }} --}}
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

                                <td><span class="badge bg-{{($item->is_active == 1) ? 'success' : 'danger'}}">{{($item->is_active == 1) ? 'Active' : 'Inactive'}}</span></td>

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
