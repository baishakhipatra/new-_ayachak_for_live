@extends('admin.layouts.app')

@section('page', 'Size')

@section('content')
<section>
    <div class="row">
        <div class="col-xl-12">
            <div class="search__filter">
                <div class="row align-items-center justify-content-between">
                    <div class="col">
                        <ul>
                            @php
                                $activeCount = $inactiveCount = 0;
                                foreach ($data as $key => $collValue) {
                                    if($collValue->status == 1) $activeCount++;
                                    else $inactiveCount++;
                                }
                            @endphp
                            <li><a href="{{ route('admin.size.index', ['status' => 'active'])}}">Active <span class="count">({{$activeCount}})</span></a></li>
                            <li><a href="{{ route('admin.size.index', ['status' => 'inactive'])}}">Inactive <span class="count">({{$inactiveCount}})</span></a></li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <form action="{{ route('admin.size.index')}}">
                            <div class="row g-3 align-items-center">
                                <div class="col-auto">
                                    <input type="search" name="term" id="term" class="form-control" placeholder="Search here.." value="{{app('request')->input('term')}}" autocomplete="off">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Search Size</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.size.create') }}"  class="btn btn-outline-danger btn-sm float-right"> Create</a></div>
                    </div>
                    <div class="col-12 text-end">
                        @php
                        if (!empty($_GET['status'])) {
                            if ($_GET['status'] == 'active') {
                                ($activeCount>1) ? $itemShow = 'Items' : $itemShow = 'Item';
                                echo '<p class="mb-0 mt-3">'.$activeCount.' '.$itemShow.'</p>';
                            } elseif ($_GET['status'] == 'inactive') {
                                ($inactiveCount>1) ? $itemShow = 'Items' : $itemShow = 'Item';
                                echo '<p class="mb-0 mt-3">'.$inactiveCount.' '.$itemShow.'</p>';
                            }
                        } else {
                            ($data->count() > 1) ? $itemShow = 'Items' : $itemShow = 'Item';
                            echo '<p class="mb-0 mt-3">'.$data->count().' '.$itemShow.'</p>';
                        }
                        @endphp
                    </div>
                </div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Status</th>
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
                        <td>{{$index+1}}</td>
                        <td>
                            <h3 class="text-dark">{{$item->name}}</h3>
                            <div class="row__action">
                                <a href="{{ route('admin.size.edit', $item->id) }}">Edit</a>
                                <a href="{{ route('admin.size.view', $item->id) }}">View</a>
                                <a href="{{ route('admin.size.status', $item->id) }}">{{($item->status == 1) ? 'Active' : 'Inactive'}}</a>
                            </div>
                        </td>
                        <td>Published<br/>{{date('d M Y', strtotime($item->created_at))}}</td>
                        <td>
                            <span class="badge bg-{{($item->status == 1) ? 'success' : 'danger'}}">{{($item->status == 1) ? 'Active' : 'Inactive'}}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="100%" class="small text-muted">No data found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
