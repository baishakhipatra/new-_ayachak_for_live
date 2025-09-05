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

                    <div class="cont mt-4">
                        <div class="row">
                            <div class="col-md-4">
                                <p class="small text-muted">Displaying {{$data->firstItem()}} - {{$data->lastItem()}} out of {{$data->total()}} data</p>
                            </div>
                            <div class="col-md-8 text-end">
                                <form action="">
                                    <div class="d-flex justify-content-end">
                                        <div class="me-3">
                                            <div class="btn-group">
                                                <input type="radio" class="btn-check" name="type" id="typeAll" autocomplete="off" value="all"  {{ (!(request()->input('type')) || (request()->input('type') == "all") ) ? 'checked' : '' }}>
                                                <label class="btn btn-sm btn-outline-dark" for="typeAll">All</label>

                                                <input type="radio" class="btn-check" name="type" id="typeTrue" autocomplete="off" value="success" {{ (request()->input('type') == "success") ? 'checked' : '' }}>
                                                <label class="btn btn-sm btn-outline-dark" for="typeTrue">Success</label>

                                                <input type="radio" class="btn-check" name="type" id="typeFalse" autocomplete="off" value="fail" {{ (request()->input('type') == "fail") ? 'checked' : '' }}>
                                                <label class="btn btn-sm btn-outline-dark" for="typeFalse">Failure</label>
                                            </div>
                                        </div>
                                        <div class="me-3">
                                            <select name="order" id="order" class="form-select">
                                                <option value="" disabled>Sort by</option>
                                                <option value="id" {{ (request()->input('order') == "id") ? 'selected' : '' }}>Relevance</option>
                                                <option value="inventory" {{ (request()->input('order') == "inventory") ? 'selected' : '' }}>Inventory</option>
                                            </select>
                                        </div>
                                        <div class="me-3">
                                            <input type="search" name="keyword" id="keyword" class="form-control" placeholder="Search SKU..." value="{{ app('request')->input('keyword') }}" autocomplete="off">
                                        </div>
                                        <div>
                                            <div class="btn-group">
                                                <button type="submit" data-bs-toggle="tooltip" title="Search" class="btn btn-sm btn-danger"> <i class="fi fi-br-search"></i> </button>

                                                <a href="{{ route('admin.product.sku_list.sync.all.report.detail', $id) }}" class="btn btn-sm btn-light" data-bs-toggle="tooltip" title="Clear search"> <i class="fi fi-br-x"></i> </a>

                                                <a href="{{ route('admin.product.sku_list.sync.all.report.detail.export', $id) }}" data-bs-toggle="tooltip" class="btn btn-sm btn-danger" title="Export"> <i class="fi fi-br-download"></i> </a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="table-holder" style="word-break: break-word;">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#SR</th>
                                    <th style="width: 20%;">SKU code</th>
                                    <th style="width: 15%;">Inventory</th>
                                    <th style="width: 15%;">Status</th>
                                    <th style="width: 40%;">Response</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $index => $item)
                                    <tr>
                                        <td><p class="small mb-0 text-dark">{{ $index + $data->firstItem() }}</p></td>
                                        <td><p class="small mb-0 text-muted">{{ $item->sku_code }}</p></td>
                                        <td><p class="small mb-0 text-muted">{{ $item->inventory }}</p></td>
                                        <td><p class="small mb-0 text-muted">{{ ($item->status == 0) ? 'Failure' : 'Success' }}</p></td>
                                        <td>
                                            {{-- <p class="small mb-0 text-muted">{{ $item->api_resp }}</p> --}}

                                            <p class="small mb-0 text-muted">{{ substr($item->api_resp, 0, 100) }} @if(strlen($item->api_resp) > 100)<a href="javascript: void(0)" class="showMore" style="cursor: pointer">...more</a>@endif</p>
                                            <p class="small mb-0 text-muted" style="display: none;">{{ $item->api_resp }} @if(strlen($item->api_resp)>100)<a href="javascript: void(0)" class="showLess" style="cursor: pointer">less</a>@endif</p>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-muted text-center">No records found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $data->appends($_GET)->links() }}

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