@extends('layouts.app')

@section('page', 'Dashboard')

@section('content')
<div class="col-sm-12">
    <div class="profile-card">
        <h3 class="mb-0">Team wise Report</h3>
        <section class="store_listing">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between mb-3">
                        <ul class="nav nav-pills mt-3" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link" id="pills-current-tab" data-toggle="pill" href="#pills-current" role="tab" aria-controls="pills-current" aria-selected="true">Primary Sales</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-past-tab" data-toggle="pill" href="#pills-past" role="tab" aria-controls="pills-past" aria-selected="false">Secondary Sales</a>
                            </li>
                        </ul>
                    </div>
                    <div class="date-formatter">
                        <form action="" method="get" class="row">
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
                                    <label for="collection"><h5 class="small text-muted mb-0">Range</h5></label>
                                    <select class="form-control form-control-sm" name="collection">
                                        <option value="" disabled>Select</option>
                                        <option value="all" selected>All</option>
                                        @foreach ($collection as $index => $item)
                                        <option value="{{ $item->id }}" {{ ($request->collection == $item->id) ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="category"><h5 class="small text-muted mb-0">Category</h5></label>
                                    <select class="form-control form-control-sm" name="category">
                                        <option value="" disabled>Select</option>
                                        <option value="all" selected>All</option>
                                        @foreach ($category as $index => $item)
                                            <option value="{{ $item->id }}" {{ ($request->category == $item->id) ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="pincode"><h5 class="small text-muted mb-0">Pincode</h5></label>
                                    <input type="text" name="pincode" id="pincode" class="form-control form-control-sm" value="{{ (request()->input('pincode')) }}" placeholder="eg: 700000">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="style_no"><h5 class="small text-muted mb-0">Product Style No</h5></label>
                                    <input type="text" name="style_no" id="style_no" class="form-control form-control-sm" value="{{ $request->style_no }}" placeholder="eg: 423">
                                </div>
                            </div>
                            {{-- <div class="col-md-3">
                                <label for="orderBy"><h5 class="small text-muted mb-0">Sort by</h5></label>
                                <select name="orderBy" id="orderBy" class="form-control form-control-sm">
                                    <option value="" disabled>Select</option>
                                    <option value="date_desc" {{ ($request->orderBy == "date_desc") ? 'selected' : '' }}>Purchase date Desc</option>
                                    <option value="date_asc" {{ ($request->orderBy == "date_asc") ? 'selected' : '' }}>Purchase date Asc</option>
                                    <option value="qty_asc" {{ ($request->orderBy == "qty_asc") ? 'selected' : '' }}>Quantity Asc</option>
                                    <option value="qty_desc" {{ ($request->orderBy == "qty_desc") ? 'selected' : '' }}>Quantity Desc</option>
                                </select>
                            </div> --}}
                            <div class="col-md-3"></div>
                            <div class="col-md-3 text-right">
                                <div class="form-group pt-4">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="submit" class="btn btn-sm btn-danger">Apply</button>

                                        <a type="button" href="{{ url()->current() }}" class="btn btn-sm btn-light border" data-toggle="tooltip" data-placement="top" title="Remove filter">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <hr>

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade" id="pills-current" role="tabpanel" aria-labelledby="pills-current-tab">
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
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($distributors as $distributor)
                                        @php
                                        if ( request()->input('from') || request()->input('to') || request()->input('collection') ||request()->input('category') ||request()->input('product') ) {
                                            // date from
                                            if (!empty(request()->input('from'))) {
                                                $from = request()->input('from');
                                            } else {
                                                $from = $first_day_this_month = date('Y-m-01');
                                            }

                                            // date to
                                            if (!empty(request()->input('to'))) {
                                                $to = date('Y-m-d', strtotime(request()->input('to'). '+1 day'));
                                            } else {
                                                $to = $current_day_this_month = date('Y-m-d', strtotime('+1 day'));
                                            }
                                            //collection
                                            if (!empty(request()->input('collection'))) {
                                                $collection = request()->input('collection');
                                            } else {
                                                $collection = request()->input('collection');
                                            }

                                            //category
                                            if (!empty(request()->input('category'))) {
                                                $category = request()->input('category');
                                            } else {
                                                $category = request()->input('category');
                                            }

                                            //product
                                            /* if (!empty(request()->input('product'))) {
                                                $product = request()->input('product');
                                            } else {
                                                $product = request()->input('product');
                                            } */

                                            $report = \DB::select("SELECT SUM(od.final_amount) AS amount, SUM(opd.qty) AS qty FROM `orders_distributors` AS od
                                            INNER JOIN order_products_distributors AS opd
                                            ON od.id = opd.order_id
                                            LEFT JOIN products AS p
                                            ON opd.product_id = p.id
                                            WHERE od.distributor_name = '".$distributor->distributor_name."' AND (od.created_at BETWEEN '".$from."' AND '".$to."') AND (p.collection_id  = '".$collection."' ) AND (p.cat_id  = '".$category."' )");
                                        } else {
                                            $report = \DB::select("SELECT SUM(od.final_amount) AS amount, SUM(opd.qty) AS qty FROM `orders_distributors` AS od
                                            INNER JOIN order_products_distributors AS opd
                                            ON od.id = opd.order_id
                                            LEFT JOIN products AS p
                                            ON opd.product_id = p.id
                                            WHERE od.distributor_name = '".$distributor->distributor_name."' AND DATE(od.created_at) = CURDATE() AND (p.collection_id  = '".$collection."' ) AND (p.cat_id  = '".$category."' )");
                                        }
                                        @endphp
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
                                                        {{  0 }}
                                                    </p>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade show active" id="pills-past" role="tabpanel" aria-labelledby="pills-past-tab">
                            <div class="row">
                                <div class="col-12">
                                    @if (request()->input('from') || request()->input('to'))
                                        <p class="text-dark">Retailer wise report from <strong>{{ date('j F, Y', strtotime(request()->input('from'))) }}</strong> - <strong>{{ date('j F, Y', strtotime(request()->input('to'))) }}</strong></p>
                                    @else
                                        <p class="text-dark">Retailer wise daily report of <strong>{{ date('01 F, Y') }}</strong> - <strong>{{ date('j F, Y') }}</strong></p>
                                    @endif
                                </div>

                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($retailers as $retailer)
                                        @php
                                        if ( request()->input('from') || request()->input('to') ) {
                                            // date from
                                            if (!empty(request()->input('from'))) {
                                                $from = request()->input('from');
                                            } else {
                                                $from = date('Y-m-01');
                                            }

                                            // date to
                                            if (!empty(request()->input('to'))) {
                                                $to = date('Y-m-d', strtotime(request()->input('to'). '+1 day'));
                                            } else {
                                                $to = date('Y-m-d', strtotime('+1 day'));
                                            }

                                            // collection
                                            if ($request->collection == 'all') {
                                                $collectionQuery = "";
                                            } else {
                                                $collectionQuery = " AND p.collection_id = ".$request->collection;
                                            }

                                            // category
                                            if ($request->category == 'all') {
                                                $categoryQuery = "";
                                            } else {
                                                $categoryQuery = " AND p.cat_id = ".$request->category;
                                            }

                                            // style no
                                            if(!empty($request->style_no)) {
                                                $styleNoQuery = " AND p.style_no LIKE '%".$request->style_no."%'";
                                            } else {
                                                $styleNoQuery = "";
                                            }

                                            // order by
                                            if ($request->orderBy == 'date_asc') {
                                                $orderByQuery = "op.id ASC";
                                            } elseif ($request->orderBy == 'qty_asc') {
                                                $orderByQuery = "qty ASC";
                                            } elseif ($request->orderBy == 'qty_desc') {
                                                $orderByQuery = "qty DESC";
                                            } else {
                                                $orderByQuery = "op.id DESC";
                                            }

                                            $report = DB::select("SELECT SUM(op.qty) AS qty FROM `orders` AS o
                                            INNER JOIN order_products AS op ON op.order_id = o.id
                                            INNER JOIN products p ON p.id = op.product_id
                                            WHERE o.store_id = '".$retailer->id."'
                                            ".$collectionQuery."
                                            ".$categoryQuery."
                                            ".$styleNoQuery."
                                            AND (date(o.created_at) BETWEEN '".$from."' AND '".$to."')
                                            ORDER BY ".$orderByQuery);
                                        } else {
                                            /* $report = DB::select("SELECT SUM(op.qty) AS qty FROM `order_products` op
                                            INNER JOIN products p ON p.id = op.product_id
                                            INNER JOIN orders o ON o.id = op.order_id
                                            INNER JOIN stores s ON s.id = o.store_id
                                            WHERE o.store_id = '".$retailer->id."'
                                            AND (DATE(op.created_at) BETWEEN '".date('Y-m-01')."' AND '".date('Y-m-d', strtotime('+1 day'))."')
                                            "); */

                                            $report = DB::select("SELECT SUM(op.qty) AS qty FROM `orders` AS o INNER JOIN order_products AS op ON op.order_id = o.id WHERE o.store_id = '".$retailer->id."' AND (date(o.created_at) BETWEEN '".date('Y-m-01')."' AND '".date('Y-m-d', strtotime('+1 day'))."')");
                                        }
                                        @endphp
                                            <tr>
                                                <td>
                                                    <a href="{{ route('front.user.order') }}">
                                                        {{ ($retailer->store_name == null) ? 'NA' : $retailer->store_name }}
                                                    </a>
                                                </td>
                                                <td>
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
        </section>
    </div>
</div>
@endsection

@section('script')
    <script>

    </script>
@endsection
