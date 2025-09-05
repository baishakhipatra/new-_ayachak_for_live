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
            <div class="col-3 mb-3">
                <a href="{{ route('front.distributor.store.orders.index') }}">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone-call"><path d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                <div class="texts">
                                    <h5 class="card-title">Product Orders</h5>
                                    {{-- <p class="card-text">Orders from store...</p> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            {{-- <div class="col-3 mb-3">
                <a href="{{ route('front.store.order.index') }}">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone-call"><path d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                <div class="texts">
                                    <h5 class="card-title">Store Orders</h5>
                                    <p class="card-text">Orders from store...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div> --}}
            <div class="col-3 mb-3">
                <a href="{{ route('front.distributor.order.place.index') }}">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
                                <div class="texts pl-4">
                                    <h5 class="card-title">Place Order</h5>
                                    <p class="card-text">Place order to company...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-3 mb-3">
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
            <div class="col-3 mb-3">
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
    </div>
</section>
@endsection

@section('script')
    <script>
        
    </script>
@endsection