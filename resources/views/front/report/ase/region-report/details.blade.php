@extends('layouts.app')

@section('page', 'Dashboard')

@section('content')
<div class="col-sm-12">
    <div class="profile-card">
        <h3>Region wise Report</h3>
        <section class="store_listing">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <ul class="nav nav-pills mt-3" id="pills-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="pills-current-tab" data-toggle="pill" href="#pills-current" role="tab" aria-controls="pills-current" aria-selected="true">Primary Sales</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-past-tab" data-toggle="pill" href="#pills-past" role="tab" aria-controls="pills-past" aria-selected="false">Secondary Sales</a>
                                    </li>
                                </ul>
                            </div>
                                <div class="date-formatter">
                                    <form action="" method="get" class="row justify-content-end">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="dateFrom"><h5 class="small text-muted mb-0">Date from</h5></label>
                                                <input type="date" name="from" id="dateFrom" class="form-control form-control-sm" value="{{ (request()->input('from')) ? request()->input('from') : date('Y-m-01') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="dateTo"><h5 class="small text-muted mb-0">Date to</h5></label>
                                                <input type="date" name="to" id="dateTo" class="form-control form-control-sm" value="{{ (request()->input('to')) ? request()->input('to') : date('Y-m-d') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="pincode"><h5 class="small text-muted mb-0">Pincode</h5></label>
                                                <input type="text" name="pincode" id="pincode" class="form-control form-control-sm" value="{{ (request()->input('pincode')) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="collection"><h5 class="small text-muted mb-0">Range</h5></label>
                                                <select class="form-control" name="collection">
                                                    <option value="">Select</option>
                                                    @foreach ($collection as $index => $item)
                                                    <option value="{{ $item->id }}" {{ ($request->collection == $item->id) ? 'selected' : '' }}>{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="category"><h5 class="small text-muted mb-0">Category</h5></label>
                                                <select class="form-control" name="category">
                                                    <option value="">Select</option>
                                                    @foreach ($category as $index => $item)
                                                         <option value="{{ $item->id }}" {{ ($request->category == $item->id) ? 'selected' : '' }}>{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="product"><h5 class="small text-muted mb-0">Product Style No</h5></label>
                                                <input type="text" name="product" id="product" class="form-control form-control-sm" value="{{ $request->style_no }}">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="dateTo" style="visibility: hidden;"><h5 class="small text-muted mb-0">Date to</h5></label>
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <button type="submit" class="btn btn-sm btn-danger">Apply</button>
                                                    <a type="button" href="{{ route('front.sales.report.detail.updated', ['ase' => request()->input('ase'), 'state' => request()->input('state')]) }}" class="btn btn-sm btn-light border" data-toggle="tooltip" data-placement="top" title="Remove filter">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                            <hr>

                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-current" role="tabpanel" aria-labelledby="pills-current-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            @if (request()->input('from') || request()->input('to'))
                                                <p class="text-dark">Distributor wise report from <strong>{{ date('j F, Y', strtotime(request()->input('from'))) }}</strong> - <strong>{{ date('j F, Y', strtotime(request()->input('to'))) }}</strong></p>
                                            @else
                                                <p class="text-dark">Distributor wise daily report of <strong>{{date('j F, Y')}}</strong></p>
                                            @endif
                                        </div>

                                        <table class="table table-sm table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Pcs</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($distributors as $distributor)

                                                    <tr>
                                                        <td>
                                                            @if (request()->input('from') || request()->input('to'))
                                                                <a href="{{ route('front.sales.report.distributor.detail', ['distributor' => $distributor->distributor_name, 'from' => request()->input('from'), 'to' => request()->input('to')]) }}">
                                                            @else
                                                                <a href="{{ route('front.sales.report.distributor.detail', ['distributor' => $distributor->distributor_name, 'date' => date('Y-m-d')]) }}">
                                                            @endif
                                                                {{ $distributor->distributor_name }}
                                                            </a>
                                                        </td>
                                                        <td>
                                                            {{-- <p class="amount">
                                                                Rs {{ ($report[0]->amount == null) ? 0 : $report[0]->amount }}
                                                            </p> --}}

                                                            <p class="qty">
                                                                {{ ($report[0]->qty == null) ? 0 : $report[0]->qty }}
                                                            </p>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-past" role="tabpanel" aria-labelledby="pills-past-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            @if (request()->input('from') || request()->input('to'))
                                                <p class="text-dark">Retailer wise report from <strong>{{ date('j F, Y', strtotime(request()->input('from'))) }}</strong> - <strong>{{ date('j F, Y', strtotime(request()->input('to'))) }}</strong></p>
                                            @else
                                                <p class="text-dark">Retailer wise daily report of <strong>{{date('j F, Y')}}</strong></p>
                                            @endif
                                        </div>

                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Pcs</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($retailers as $retailer)

                                                    <tr>
                                                        <td>
                                                            <a href="{{ route('front.user.order', ['store' => $retailer->id]) }}">
                                                                {{ ($retailer->store_name == null) ? 'NA' : $retailer->store_name }}
                                                            </a>
                                                        </td>
                                                        <td>
                                                            {{-- <p class="amount">
                                                                Rs {{ ($report[0]->amount == null) ? 0 : $report[0]->amount }}
                                                            </p> --}}

                                                            <p class="qty">
                                                                {{ ($report[0]->qty == null) ? 0 : $report[0]->qty }}
                                                            </p>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>



                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@section('script')
    <script>

    </script>
@endsection
