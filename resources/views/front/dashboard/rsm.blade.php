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
            <div class="col-3">
                <a href="{{ route('front.directory.index') }}">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
                                <div class="texts">
                                    <h5 class="card-title">Distributor</h5>
                                    <p class="card-text">Distributor MOM...</p>
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
            <div class="col-3">
                <a href="{{ route('front.target.index') }}">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clipboard"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg>
                                <div class="texts">
                                    <h5 class="card-title">Target</h5>
                                    <p class="card-text">Find target for ASM...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        @php
            $asmReportNameArray = $asmReportValueArray = [];
        @endphp

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <canvas id="asmReportDiv" width="400" height="220"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">ASM report</h5>
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($asmReport as $item)
                                    @php
                                        $asmReportNameArray[] = ($item->name == null) ? 'NA' : $item->name;
                                        $asmReportValueArray[] = ($item->value == null) ? 0 : $item->value;
                                    @endphp
                                    <tr>
                                        <td>
                                            <a href="{{ route('front.sales.report.detail', ['asm' => ($item->name == null) ? 'NA' : $item->name, 'state' => $loggedInUserState]) }}">
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
            $aseReportNameArray = $aseReportValueArray = [];
        @endphp

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">ASE report</h5>
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($aseReport as $item)
                                    @php
                                        $aseReportNameArray[] = ($item->name == null) ? 'NA' : $item->name;
                                        $aseReportValueArray[] = ($item->value == null) ? 0 : $item->value;
                                    @endphp
                                    <tr>
                                        <td>
                                            <a href="{{ route('front.sales.report.detail', ['ase' => ($item->name == null) ? 'NA' : $item->name, 'state' => $loggedInUserState]) }}">
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
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <canvas id="aseReportDiv" width="400" height="220"></canvas>
                    </div>
                </div>
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
                <div class="card h-100" id="distributorCard" style="max-height: 360px;overflow:hidden">
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
                                @foreach ($distributorReport as $distributorKey => $item)
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
                                    @if ($distributorKey == 6)
                                    <tr>
                                        <td colspan="100%" class="text-right">
                                            <a href="javascript: void(0)" id="distributorShowMore">Show more</a>
                                        </td>
                                    </tr>
                                    @endif
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
        $('#distributorShowMore').on('click', function() {
            $(this).parent().parent().hide();
            $('#distributorCard').css('maxHeight', '100%');
        });

        // Distributor report
        var labelValues0 = [];
        var dataValues0 = [];
        labelValues0 = <?php echo json_encode($stateReportNameArray); ?>;
        dataValues0 = <?php echo json_encode($stateReportValueArray); ?>;

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



        // ASE report
        var labelValues2 = [];
        var dataValues2 = [];
        labelValues2 = <?php echo json_encode($aseReportNameArray); ?>;
        dataValues2 = <?php echo json_encode($aseReportValueArray); ?>;

        const ctx2 = document.getElementById('aseReportDiv').getContext('2d');
        const aseReportDiv = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: labelValues2,
                datasets: [{
                    label: 'ASE report',
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



        // ASM report
        var labelValues3 = [];
        var dataValues3 = [];
        labelValues3 = <?php echo json_encode($asmReportNameArray); ?>;
        dataValues3 = <?php echo json_encode($asmReportValueArray); ?>;

        const ctx3 = document.getElementById('asmReportDiv').getContext('2d');
        const asmReportDiv = new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: labelValues3,
                datasets: [{
                    label: 'ASM report',
                    data: dataValues3,
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
