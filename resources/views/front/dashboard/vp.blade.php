@extends('layouts.app')

@section('page', 'Dashboard')

@section('content')
<section class="store_listing">
    <div class="container">
        <div class="row mb-4">
            <div class="col-6">
                <h5 class="display-4" style="font-size: 1.5rem;">Welcome, {{ auth()->guard('web')->user()->name }}</h5>
                <h5 class="small text-muted mb-0">{{$userTypeDetail}}</h5>
            </div>
            <div class="col-6 text-right">
                <form action="" method="get" class="row justify-content-end">
                    {{-- <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Select state</label>
                            @php
                                $loggedInUserType = \Auth::guard('web')->user()->user_type;
                                $loggedInUserState = \Auth::guard('web')->user()->state;
                            @endphp
                            @if (count($vp_states) != 0)
                                <select name="state" class="form-control form-control-sm">
                                    @foreach ($vp_states as $state)
                                        <option value="{{$state->state}}"
                                        @if (request()->input('state'))
                                            @if ($state->state == request()->input('state'))
                                                {{'selected'}}
                                            @endif
                                        @else
                                            @if ($state->state == $loggedInUserState)
                                                {{'selected'}}
                                            @endif
                                        @endif
                                        >{{$state->state}}</option>
                                    @endforeach
                                </select>
                            @else
                                <select name="state" class="form-control form-control-sm">
                                    <option value="{{$loggedInUserState}}">{{$loggedInUserState}}</option>
                                </select>
                            @endif
                        </div>
                    </div> --}}
                    {{-- <div class="col-md-4">
                        <div class="form-group">
                            <label for="dateFrom"><h5 class="small text-muted mb-0">Date from</h5></label>
                            <input type="date" name="from" id="dateFrom" class="form-control form-control-sm" value="{{ (request()->input('from')) ? request()->input('from') : date('Y-m-01') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="dateTo"><h5 class="small text-muted mb-0">Date to</h5></label>
                            <input type="date" name="to" id="dateTo" class="form-control form-control-sm" value="{{ (request()->input('to')) ? request()->input('to') : date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="" style="visibility: hidden;">save</label>
                            <br>
                            <button type="submit" class="btn btn-sm btn-danger">Apply</button>
                            <a href="{{route('front.dashboard.index')}}" class="btn btn-sm btn-light border" data-toggle="tooltip" data-placement="top" title="Remove filter">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                            </a>
                        </div>
                    </div> --}}
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-3">
                <a href="{{ route('front.offer.index') }}">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tag"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                                <div class="texts">
                                    <h5 class="card-title">Schemes</h5>
                                    <p class="card-text">Current & past Sche...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-3">
                <a href="{{ route('front.catalouge.download.index') }}">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                <div class="texts">
                                    <h5 class="card-title">Catalogue</h5>
                                    <p class="card-text">Find catalogues for...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-3">
                <a href="{{ route('front.sales.report.index') }}">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clipboard"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg>
                                <div class="texts">
                                    <h5 class="card-title">Sales report</h5>
                                    <p class="card-text">Sales report of VP...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-3">
                <a href="{{ route('front.target.index') }}">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clipboard"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg>
                                <div class="texts">
                                    <h5 class="card-title">Target</h5>
                                    <p class="card-text">Yearly Target view...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        @php
            $stateReportNameArray = $stateReportValueArray = [];
        @endphp

        @if(\Auth::guard('web')->user()->user_type == 1)
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <canvas id="stateReportDiv" width="400" height="220"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">States report</h5>
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stateWiseReport as $item)
                                    @php
                                        $stateReportNameArray[] = ($item->name == null) ? 'NA' : $item->name;
                                        $stateReportValueArray[] = ($item->value == null) ? 0 : $item->value;
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ ($item->name == null) ? 'NA' : $item->name }}
                                            {{-- <a href="{{ route('front.sales.report.detail', ['rsm' => ($item->name == null) ? 'NA' : $item->name, 'state' => $item->state]) }}">{{ ($item->name == null) ? 'NA' : $item->name }}</a> --}}
                                        </td>
                                        <td>Rs {{number_format($item->value)}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="{{ route('front.sales.report.index') }}" class="btn btn-sm btn-danger float-right">View complete report</a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="row mt-4">
            @php
                $regionWiseReportAreaArray = $regionWiseReportValueArray = [];
            @endphp
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Region report</h5>

                        <form action="" method="get" class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Select state</label>
                                    @php
                                        $loggedInUserType = \Auth::guard('web')->user()->user_type;
                                        $loggedInUserState = \Auth::guard('web')->user()->state;

                                        // (count($vp_states) != 0) ? $vp_states = $vp_states : $vp_states = $loggedInUserState;
                                    @endphp
                                    @if (count($vp_states) != 0)
                                        <select name="state" class="form-control form-control-sm">
                                            @foreach ($vp_states as $state)
                                                <option value="{{$state->state}}"
                                                @if (request()->input('state'))
                                                    @if ($state->state == request()->input('state'))
                                                        {{'selected'}}
                                                    @endif
                                                @else
                                                    @if ($state->state == $loggedInUserState)
                                                        {{'selected'}}
                                                    @endif
                                                @endif
                                                >{{$state->state}}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <select name="state" class="form-control form-control-sm">
                                            <option value="{{$loggedInUserState}}">{{$loggedInUserState}}</option>
                                        </select>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Date from</label>
                                    <input type="date" name="from" class="form-control form-control-sm" value="{{ (request()->input('from')) ? request()->input('from') : date('Y-m-01') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Date to</label>
                                    <input type="date" name="to" class="form-control form-control-sm" value="{{ (request()->input('to')) ? request()->input('to') : date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="" style="visibility: hidden;">save</label>
                                    <br>
                                    <button type="submit" class="btn btn-sm btn-danger">Apply</button>
                                    <a href="{{route('front.dashboard.index')}}" class="btn btn-sm btn-light border" data-toggle="tooltip" data-placement="top" title="Remove filter">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                    </a>
                                </div>
                            </div>
                        </form>

                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Region</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($regionWiseReport as $item)
                                    @php
                                        $regionWiseReportAreaArray[] = $item->area;
                                        $regionWiseReportValueArray[] = ($item->value == null) ? 0 : $item->value;
                                    @endphp
                                    <tr>
                                        <td>{{$item->area}}</td>
                                        <td>Rs {{number_format($item->value)}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="{{ route('front.sales.report.index') }}" class="btn btn-sm btn-danger float-right">View complete report</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <canvas id="regionReportDiv" width="400" height="220"></canvas>
                    </div>
                </div>
            </div>
        </div>

        @php
            $RSMwiseReportNameArray = $RSMwiseReportValueArray = [];
        @endphp

        @if(\Auth::guard('web')->user()->user_type != 4)
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <canvas id="rsmReportDiv" width="400" height="220"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Regional sales manager report</h5>
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($RSMwiseReport as $item)
                                    @php
                                        $RSMwiseReportNameArray[] = ($item->name == null) ? 'NA' : $item->name;
                                        $RSMwiseReportValueArray[] = ($item->value == null) ? 0 : $item->value;
                                    @endphp
                                    <tr>
                                        <td>
                                            {{-- {{ ($item->name == null) ? 'NA' : $item->name }} --}}
                                            <a href="{{ route('front.sales.report.detail', ['rsm' => ($item->name == null) ? 'NA' : $item->name, 'state' => $item->state]) }}">{{ ($item->name == null) ? 'NA' : $item->name }}</a>
                                        </td>
                                        <td>Rs {{number_format($item->value)}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="{{ route('front.sales.report.index') }}" class="btn btn-sm btn-danger float-right">View complete report</a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js"></script>

    <script>
        @if(\Auth::guard('web')->user()->user_type == 1)
        // State report
        var labelValues0 = [];
        var dataValues0 = [];
        labelValues0 = <?php echo json_encode($stateReportNameArray); ?>;
        dataValues0 = <?php echo json_encode($stateReportValueArray); ?>;

        // console.log(labelValues0);

        const ctx0 = document.getElementById('stateReportDiv').getContext('2d');
        const stateReportDiv = new Chart(ctx0, {
            type: 'bar',
            data: {
                labels: labelValues0,
                datasets: [{
                    label: 'State report',
                    data: dataValues0,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        @endif

        // Region report
        var labelValues1 = [];
        var dataValues1 = [];
        labelValues1 = <?php echo json_encode($regionWiseReportAreaArray); ?>;
        dataValues1 = <?php echo json_encode($regionWiseReportValueArray); ?>;

        const ctx = document.getElementById('regionReportDiv').getContext('2d');
        const regionReportDiv = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labelValues1,
                datasets: [{
                    label: '{{$loggedInUserState}} state report',
                    data: dataValues1,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // RSM report
        var labelValues2 = [];
        var dataValues2 = [];
        labelValues2 = <?php echo json_encode($RSMwiseReportNameArray); ?>;
        dataValues2 = <?php echo json_encode($RSMwiseReportValueArray); ?>;

        const ctx2 = document.getElementById('rsmReportDiv').getContext('2d');
        const rsmReportDiv = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: labelValues2,
                datasets: [{
                    label: 'Regional sales manager report',
                    data: dataValues2,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
