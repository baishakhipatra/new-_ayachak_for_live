@extends('admin.layouts.app')

@section('page', 'Order')

@section('content')
<section>
    <div class="search__filter">
        <div class="row align-items-center justify-content-between">
        <div class="col">

        </div>
        <div class="col-auto">
            <form action="{{ route('admin.order.index')}}" method="GET">
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

    <table class="table">
        <thead>
        <tr>
            <th class="check-column">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault"></label>
                </div>
            </th>
            <th>Name</th>
            <th>Store Name</th>
            <th>Amount</th>
            <th>Order time</th>

            <th>Status</th>
        </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $item)
            <tr>
                <td class="check-column">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault"></label>
                </div>
                <td>
                    <p class="small text-dark mb-1">#{{$item->order_no}}</p>

                    <p class="small text-dark mb-1">{{$item->fname.' '.$item->lname}}</p>
                    <p class="small text-muted mb-0">{{$item->email.' | '.$item->mobile}}</p>
                    <div class="row__action">
                        <a href="{{ route('admin.order.view', $item->id) }}">View</a>
                    </div>
                </td>
                <td>
                    <p class="small text-muted mb-1">Rs {{$item->final_amount}}</p>
                </td>
                <td>
                    <p class="small">{{date('j M Y g:i A', strtotime($item->created_at))}}</p>
                </td>
                <td>
                    <p class="small text-muted mb-2">{{$item->billing_address.' | '.$item->billing_landmark.' | '.$item->billing_pin.' | '.$item->billing_city.' | '.$item->billing_state.' | '.$item->billing_country}}</p>
                    <div class="btn-group" role="group" aria-label="Basic outlined example">
                        <a href="{{ route('admin.order.status', [$item->id, 1]) }}" type="button" class="btn btn-outline-primary btn-sm {{($item->status == 1) ? 'active' : ''}}">New</a>
                        <a href="{{ route('admin.order.status', [$item->id, 2]) }}" type="button" class="btn btn-outline-primary btn-sm {{($item->status == 2) ? 'active' : ''}}">Confirm</a>
                        <a href="{{ route('admin.order.status', [$item->id, 3]) }}" type="button" class="btn btn-outline-primary btn-sm {{($item->status == 3) ? 'active' : ''}}">Shipped</a>
                        <a href="{{ route('admin.order.status', [$item->id, 4]) }}" type="button" class="btn btn-outline-success btn-sm {{($item->status == 4) ? 'active' : ''}}">Delivered</a>
                        <a href="{{ route('admin.order.status', [$item->id, 5]) }}" type="button" class="btn btn-outline-danger btn-sm {{($item->status == 5) ? 'active' : ''}}">Cancelled</a>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="100%" class="small text-muted">No data found</td></tr>
            @endforelse
        </tbody>
    </table>
</section>
@endsection
