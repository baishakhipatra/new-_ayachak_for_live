@extends('admin.layouts.app')

@section('page', 'SKU sync Report')

@section('content')
<section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <a class="btn btn-dark border btn-sm" href="{{ route('admin.product.sku_list.sync.all') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>
                            Go back
                        </a>
                    </div>

                    <div class="sku-sync-contents my-4">
                        <div class="d-flex">
                            <div class="onn">
                                <img src="{{ asset('img/onn.png') }}" alt="">
                            </div>
                            <div class="sync">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-cw sync-svg"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                            </div>
                            <div class="uni">
                                <img src="{{ asset('img/uni.png') }}" alt="">
                            </div>
                        </div>
                    </div>

                    <div class="table">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>#SR</th>
                                    <th>Total SKUs scan</th>
                                    <th>Report</th>
                                    <th>Time</th>
                                    <th>IP address</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->skus_scanned }}/{{ $item->total_skus }}</td>
                                        <td>
                                            <a href="{{ route('admin.product.sku_list.sync.all.report.detail', $item->id) }}" class="badge bg-primary">View Detailed Report</a>
                                        </td>
                                        <td>
                                            <p class="small text-muted mb-1">
                                                <span class="text-dark">Starts:</span>
                                                {{ date('j F, Y - g:i a', strtotime($item->start_time)) }}
                                            </p>
                                            <p class="small text-muted mb-1">
                                                @if($item->end_time)
                                                    <span class="text-dark">Ends:</span>
                                                    {{ date('j F, Y - g:i a', strtotime($item->end_time)) }}
                                                @endif
                                            </p>
                                        </td>
                                        <td>{{ $item->ip }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-muted text-center">No records found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
    <script>
        
    </script>
@endsection