@extends('admin.layouts.app')

@section('page', 'Products')

@section('content')
<section>
    <div class="search__filter">
        <div class="row align-items-center justify-content-between">
            <div class="col">
                <ul>
                    <li class="active"><a href="{{ route('admin.product.index') }}">All <span class="count">({{ $data->count() }})</span></a></li>
                    @php
                        $activeCount = $inactiveCount = 0;
                        foreach ($data as $catKey => $catVal) {
                            if ($catVal->status == 1) {
                                $activeCount++;
                            } else {
                                $inactiveCount++;
                            }
                        }
                    @endphp
                    <li><a href="{{ route('admin.product.index', ['status' => 'active']) }}">Active <span class="count">({{ $activeCount }})</span></a></li>
                    <li><a href="{{ route('admin.product.index', ['status' => 'inactive']) }}">Inactive <span class="count">({{ $inactiveCount }})</span></a></li>
                </ul>
            </div>
            <div class="col-auto">
                <form action="{{ route('admin.product.index') }}">
                    <div class="row g-3 align-items-center">
                        {{-- <div class="col-auto">
                            <label for="range" class="form-label">Range</label>
                            <select name="range" id="range" onchange="updateRangeWiseCategory(this.value)"
                                class="form-control">
                                <option value="">Select</option>
                                @foreach ($ranges as $c)
                                    <option value="{{ $c->collection_id }}"
                                        {{ $c->collection_id == app('request')->input('range') ? 'selected' : '' }}>
                                        {{ $c->collection->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}
                        {{-- <div class="col-auto">
                            <label for="category" class="form-label">Category</label>
                            <select name="category" id="category" class="form-control">
                                {{-- <option value="">Select range first</option>
                                @foreach ($catagories as $c)
                                    <option value="{{ $c->cat_id }}"
                                        {{ $c->cat_id == app('request')->input('category') ? 'selected' : '' }}>
                                        {{ $c->category->name }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                        <div class="col-auto">
                            <label for="term" class="form-label">Search: </label>
                            <input type="search" name="term" id="term" class="form-control"
                                placeholder="Search Name and Style No." value="{{ app('request')->input('term') }}"
                                autocomplete="off">
                        </div>
                        <div class="col-auto">
                            <label for="term" class="form-label" style="color: transparent;">Action:</label>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-outline-danger btn-sm">Search Product</button>
                                <a href="{{ route('admin.product.index') }}"
                                    class="btn btn-outline-secondary btn-sm"><i class="fi fi-br-cube"></i> Remove filters</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.product.bulkDestroy') }}">
        <div class="filter">
            <div class="row align-items-center justify-content-between">
                {{-- <div class="col">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <select name="bulk_action" class="form-control">
                                <option value=" hidden selected">Bulk Action</option>
                                <option value="delete">Delete</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-outline-danger btn-sm">Apply</button>
                        </div>
                    </div>
                </div> --}}
                <div class="col-auto">
                    @php
                        if (!empty($_GET['status'])) {
                            if ($_GET['status'] == 'active') {
                                $activeCount > 1 ? ($itemShow = 'Items') : ($itemShow = 'Item');
                                echo '<p>' . $activeCount . ' ' . $itemShow . '</p>';
                            } elseif ($_GET['status'] == 'inactive') {
                                $inactiveCount > 1 ? ($itemShow = 'Items') : ($itemShow = 'Item');
                                echo '<p>' . $inactiveCount . ' ' . $itemShow . '</p>';
                            }
                        } else {
                            $data->count() > 1 ? ($itemShow = 'Items') : ($itemShow = 'Item');
                            echo '<p>' . $data->count() . ' ' . $itemShow . '</p>';
                        }
                    @endphp
                    {{-- <p>{{$data->count()}} {{ ($data->count() > 1) ? 'Items' : 'Item' }}</p> --}}
                </div>
            </div>
        </div>

    <a href="#csvUploadModal" data-bs-toggle="modal" class="btn btn-danger mt-2">Bulk upload</a>
    <a href="{{ route('admin.product.export.all') }}" class="btn btn-primary mt-2">Export</a>

    @if (Session::has('message'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>{{ Session::get('message') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th class="check-column">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="flexCheckDefault"
                            onclick="headerCheckFunc()">
                        <label class="form-check-label" for="flexCheckDefault"></label>
                    </div>
                </th>
                <th class="text-center"><i class="fi fi-br-picture"></i></th>
                <th>Name</th>
                <th>Style No.</th>
                {{-- <th>Ranges</th> --}}
                <th>Category</th>
                <th>Price</th>
                <th>Offer Price</th>
                <th>Action</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $item)
                @php
                    if (!empty($_GET['status'])) {
                        if ($_GET['status'] == 'active') {
                            if ($item->status == 0) {
                                continue;
                            }
                        } else {
                            if ($item->status == 1) {
                                continue;
                            }
                        }
                    }
                @endphp
                <tr>
                    <td class="check-column">
                        <input name="delete_check[]" class="tap-to-delete" type="checkbox" onclick="clickToRemove()"
                            value="{{ $item->id }}" @php
                                if (old('delete_check')) {
                                    if (in_array($item->id, old('delete_check'))) {
                                        echo 'checked';
                                    }
                                }
                            @endphp>
                    </td>
                    <td class="text-center column-thumb">
                        <img src="{{ asset($item->image) }}">
                    </td>
                    <td>
                        {{ $item->name }}
                        {{-- <a href="{{ route('admin.product.unicommerce.sync', $item->id) }}" data-bs-toggle="tooltip" title="Sync with unicommerce">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-cw"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                        </a> --}}
                        <div class="row__action">
                            <a href="{{ route('admin.product.edit', $item->id) }}">Edit</a>
                            <a href="{{ route('admin.product.view', $item->id) }}">View</a>
                            <a href="{{ route('admin.product.status', $item->id) }}">{{ $item->status == 1 ? 'Active' : 'Inactive' }}</a>
                            {{-- <a href="{{ route('admin.product.delete', $item->id) }}" class="text-danger">Delete</a> --}}
                        </div>
                    </td>
                    <td>{{ $item->style_no }}</td>
                    {{-- <td>
                        <a
                            href="{{ route('admin.collection.view', $item->collection->id) }}">{{ $item->collection ? $item->collection->name : '' }}</a>
                    </td> --}}
                    <td>
                        @if(!empty($item->category))
                        <a
                            href="{{ route('admin.category.view', $item->category->id) }}">{{ $item->category ? $item->category->name : '' }}</a>
                            @endif
                        {{-- > --}}
                        {{-- {{$item->subCategory ? $item->subCategory->name : 'NA'}} --}}
                    </td>
                    <td>
                         Rs. {{ $item->price }}
                    </td>
                    <td>
                         Rs. {{ $item->offer_price }}
                    </td>
                    <td>
                        {{-- <a href="{{ route('admin.product.sale', $item->id) }}" class="text-decoration-none">
                            @if ($item->saleDetails)
                                <span class="text-success fw-bold"> <i class="fi-br-check"></i> Sale</span>
                            @else
                                <span class="text-danger fw-bold single-line"> <i class="fi-br-plus"></i>
                                    Sale</span>
                            @endif
                        </a> --}}
                        {{-- <br>
                        <a href="{{ route('admin.product.trending', $item->id) }}" class="text-decoration-none">
                            @if ($item->is_trending == 1)
                                <span class="text-success fw-bold"> <i class="fi-br-check"></i> Trending</span>
                            @else
                                <span class="text-danger fw-bold single-line"> <i class="fi-br-plus"></i>
                                    Trending</span>
                            @endif
                        </a> --}}
                        {{-- <a href="{{ route('admin.product.hotdeal', $item->id) }}" class="text-decoration-none">
                            @if ($item->is_hotdeal == 1)
                                <span class="text-success fw-bold"> <i class="fi-br-check"></i> Hot-deal</span>
                            @else
                                <span class="text-danger fw-bold single-line"> <i class="fi-br-plus"></i>
                                Hot-deal</span>
                            @endif
                        </a> --}}
                        <a href="{{ route('admin.product.feature', $item->id) }}" class="text-decoration-none">
                            @if ($item->is_feature == 1)
                                <span class="text-success fw-bold"> <i class="fi-br-check"></i> Featured</span>
                            @else
                                <span class="text-danger fw-bold single-line"> <i class="fi-br-plus"></i>
                                Featured</span>
                            @endif
                        </a>
                        {{-- <a href="{{ route('admin.product.dealoftheday', $item->id) }}" class="text-decoration-none">
                            @if ($item->is_deal_of_the_day == 1)
                                <span class="text-success fw-bold"> <i class="fi-br-check"></i> Deal Of the day</span>
                            @else
                                <span class="text-danger fw-bold single-line"> <i class="fi-br-plus"></i>
                                Deal Of the day</span>
                            @endif
                        </a> --}}
                   
                    </td>
                    <td>Published<br />{{ date('j M Y', strtotime($item->created_at)) }}</td>
                    <td><span
                            class="badge bg-{{ $item->status == 1 ? 'success' : 'danger' }}">{{ $item->status == 1 ? 'Active' : 'Inactive' }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="small text-muted text-center">No data found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </form>
</section>

{{-- bulk upload variation modal --}}
<div class="modal fade" id="csvUploadModal" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Bulk Upload Existing Product Variation with SKU code, color & size
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('admin.product.csv.upload') }}" enctype="multipart/form-data" id="borrowerCsvUpload">@csrf
                    <input type="file" name="file" class="form-control" accept=".csv">
                    <br>
                    <a href="{{ asset('backend_asset/static/product-variation-sample.csv') }}">Download Sample CSV</a>
                    <br>
                    <button type="submit" class="btn btn-danger mt-3" id="csvImportBtn">Import <i class="fas fa-upload"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        function updateRangeWiseCategory(x) {
            $.ajax({
                url: "{{ route('admin.product.index') }}",
                type: "GET",
                data: {
                    collection_id: x,
                },
                success: function(res) {
                    $('#category').html('');
                    var response = JSON.parse(res);
                    let cat = "{{ app('request')->input('category') }}";

                    if (response.length > 0)
                        var categories = '<option value="">All</option>'
                    else
                        var categories = '<option value="">Select range first</option>'

                    response.forEach(element => {
                        if (element.cat_id == cat) {
                            categories += '<option value="' + element.cat_id + '" selected>' + element
                                .category
                                .name + '</option>';
                        } else {
                            categories += '<option value="' + element.cat_id + '">' + element.category
                                .name + '</option>';
                        }

                    });
                    $('#category').html(categories);
                },
            })
        }
        updateRangeWiseCategory("{{ app('request')->input('range') }}")
    </script>
@endsection
