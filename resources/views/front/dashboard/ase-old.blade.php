@extends('layouts.app')

@section('page', 'Dashboard')

@section('content')
<section class="store_listing">
    <div class="container">
        <div class="row">
            <div class="col-12 mb-4">
                <h5 class="display-4" style="font-size: 1.5rem;">Welcome, {{ auth()->guard('web')->user()->name }}</h5>
                <h5 class="small text-muted mb-0">{{$userTypeDetail}}</h5>
            </div>
        </div>

        <div class="row">
            @if(\Auth::guard('web')->user()->user_type != 1 && \Auth::guard('web')->user()->user_type != 2 && \Auth::guard('web')->user()->user_type != 3)
            <div class="col-3">
                <a href="{{ route('front.store.order.call.index') }}">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone-call"><path d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                <div class="texts">
                                    <h5 class="card-title">Order on call</h5>
                                    <p class="card-text">Order from store...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-3">
                <a href="{{ route('front.store.index') }}">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
                                <div class="texts">
                                    <h5 class="card-title">Store visit</h5>
                                    <p class="card-text">Visit stores from...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endif
            <div class="col-3">
                <a href="{{ route('front.catalouge.download.index') }}">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                <div class="texts">
                                    <h5 class="card-title">Catalogue</h5>
                                    <p class="card-text">Find catalogues...</p>
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
                                    <p class="card-text">Sales report on...</p>
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

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <canvas id="stateReportDiv" width="400" height="220"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Distributor report</h5>
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($distributorReport as $item)
                                    @php
                                        $stateReportNameArray[] = ($item->name == null) ? 'NA' : $item->name;
                                        $stateReportValueArray[] = ($item->value == null) ? 0 : $item->value;
                                    @endphp
                                    <tr>
                                        <td>
                                            <a href="{{ route('front.sales.report.detail', ['distributor' => ($item->name == null) ? 'NA' : $item->name, 'state' => $loggedInUserState]) }}">
                                                {{ ($item->name == null) ? 'NA' : $item->name }}
                                            </a>
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

        @php
            $retailerReportNameArray = $retailerReportValueArray = [];
        @endphp

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Retailer report</h5>
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($retailerReport as $item)
                                    @php
                                        $retailerReportNameArray[] = ($item->name == null) ? 'NA' : $item->name;
                                        $retailerReportValueArray[] = ($item->value == null) ? 0 : $item->value;
                                    @endphp
                                    <tr>
                                        <td>
                                            <a href="{{ route('front.user.order', ['store' => $item->store_id, 'store_name' => ($item->name == null) ? 'NA' : $item->name]) }}">
                                                {{ ($item->name == null) ? 'NA' : $item->name }}
                                                ({{ ($item->distributorName == null) ? 'NA' : $item->distributorName }})
                                            </a>
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
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <canvas id="retailerReportDiv" width="400" height="220"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js"></script>

    <script>
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
                    label: 'Distributor report',
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


        // Retailer report
        var labelValues1 = [];
        var dataValues1 = [];
        labelValues1 = <?php echo json_encode($retailerReportNameArray); ?>;
        dataValues1 = <?php echo json_encode($retailerReportValueArray); ?>;

        // console.log(labelValues1);

        const ctx1 = document.getElementById('retailerReportDiv').getContext('2d');
        const retailerReportDiv = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: labelValues1,
                datasets: [{
                    label: 'Retailer report',
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
    </script>
@endsection
