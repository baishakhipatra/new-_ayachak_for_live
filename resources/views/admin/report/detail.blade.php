@extends('admin.layouts.app')

@section('page', '')

@section('content')
<div class="col-sm-12">
    <div class="profile-card">
        <h3>
        @if (request()->input('rsm'))
            RSM : {{request()->input('rsm')}}
        @elseif (request()->input('asm'))
            ASM : {{request()->input('asm')}}
        @elseif (request()->input('ase'))
            ASE : {{request()->input('ase')}}
        @elseif (request()->input('distributor'))
            Distributor : {{request()->input('distributor')}}
        @endif
        </h3>

        <section class="store_listing">
            <div class="row">
                {{-- <div class="col-12 mb-4">
                    <div class="d-flex justify-content-between">
                        @if (request()->input('rsm'))
                            RSM : {{request()->input('rsm')}}
                        @elseif (request()->input('asm'))
                            ASM : {{request()->input('asm')}}
                        @elseif (request()->input('ase'))
                            ASE : {{request()->input('ase')}}
                        @elseif (request()->input('distributor'))
                            Distributor : {{request()->input('distributor')}}
                        @endif
                        <a href="{{route('front.sales.report.index')}}" class="btn btn-danger">Back to sales report</a>
                    </div>
                </div> --}}

                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                @if (request()->input('rsm'))
                                    <h5>Area Sales Manager report</h5>
                                @elseif (request()->input('asm'))
                                    <h5>Area Sales Executive report</h5>
                                @elseif (request()->input('ase'))
                                    <h5>Distributor report</h5>
                                @elseif (request()->input('distributor'))
                                    <h5>Retailer report</h5>
                                @endif
                            </h5>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>
                                                @if (request()->input('rsm'))
                                                    <a href="{{ route('admin.sales.report.detail', ['asm' => ($item->name == null) ? 'NA' : $item->name, 'state' => request()->input('state')]) }}">{{ ($item->name == null) ? 'NA' : $item->name }}</a>
                                                @elseif (request()->input('asm'))
                                                    <a href="{{ route('admin.sales.report.detail', ['ase' => ($item->name == null) ? 'NA' : $item->name, 'state' => request()->input('state')]) }}">{{ ($item->name == null) ? 'NA' : $item->name }}</a>
                                                @elseif (request()->input('ase'))
                                                    <a href="{{ route('admin.sales.report.detail', ['distributor' => ($item->name == null) ? 'NA' : $item->name, 'state' => request()->input('state')]) }}">{{ ($item->name == null) ? 'NA' : $item->name }}</a>
                                                @elseif (request()->input('distributor'))
                                                    <a href="{{ route('admin.order.index', ['store' => $item->store_id, 'store_name' => ($item->name == null) ? 'NA' : $item->name]) }}">{{ ($item->name == null) ? 'NA' : $item->name }}</a>
                                                    {{-- {{ ($item->name == null) ? 'NA' : $item->name }} --}}
                                                @endif
                                            </td>
                                            <td>Rs {{number_format($item->value)}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Retailer report</h5>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Store</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($businessReport as $businessReportItem)
                                        <tr>
                                            <td>{{$businessReportItem->bussiness_name}}</td>
                                            <td>Rs {{number_format($businessReportItem->value)}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> --}}
            </div>
        </section>
    </div>
</div>
@endsection
