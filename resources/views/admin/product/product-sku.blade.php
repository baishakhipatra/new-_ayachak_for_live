@extends('admin.layouts.app')

@section('page', 'Product SKU Codes')

@section('content')
<section>
    <div class="search__filter mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <form method="GET" action="{{ route('admin.product.sku_list') }}">
                    <div class="input-group">
                        <input type="text" name="term" class="form-control" placeholder="Search by Product No. or Name" value="{{ request('term') }}">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="{{ route('admin.product.sku_list') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
            <div class="col-md-6 text-end">
                {{-- <a href="{{route('admin.product.sku_list.export', ['search' => request('search')])}}" class="btn btn-sm btn-primary">Export</a> --}}
                <a href="{{ route('admin.product.sku_list.export', request()->query()) }}" 
                    class="btn btn-sm btn-primary">
                    Export
                </a>

                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#importWeightVariationModal">
                    Import Weight Variation
                </button>
            </div>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>#SR</th>
                <th>SKU Code</th>
                <th>Product Name</th>
                <th>Product No.</th>
                <th>Weight</th>
                <th>Position</th>
                <th>Price</th>
                <th>Offer Price</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($products as $index => $item)
            <tr>
                <td>{{ $index + $products->firstItem() }}</td>
                <td>{{ $item->code }}</td>
                <td> 
                    {{ ucwords($item->product->name ?? '') }}
                    {{-- <p class="small mb-0">{{ $item->product->style_no ?? '' }}</p> --}}
                </td> 
                <td>{{ $item->product->style_no }}</td>
                <td>{{ $item->weight}}</td>
                <td>{{ $item->position }}</td>
                <td>{{ number_format($item->price, 2) }}</td>
                <td>{{ number_format($item->offer_price, 2) }}</td>
                <td>
                    <div class="form-check form-switch" data-bs-toggle="tooltip" title="Toggle status">
                        <input class="form-check-input ms-auto" type="checkbox"
                        id="customSwitch{{ $item->id }}"
                        {{ $item->status ? 'checked' : '' }}
                        onclick="statusToggle('{{ route('admin.product.variation.status', $item->id) }}', this)">
                        <label class="form-check-label" for="customSwitch{{ $item->id }}"></label>
                    </div>
                </td>
                <td>
                    <a href="javascript:void(0);" 
                        class="btn btn-sm btn-icon btn-outline-info viewImagesBtn"
                        data-id="{{ $item->id }}"
                        data-sku="{{ $item->code }}"
                        data-bs-toggle="tooltip" title="View Images">
                        <i class="fa fa-eye"></i>
                    </a>

                    <a href="javascript:void(0);" 
                        class="btn btn-sm btn-icon btn-outline-info uploadImagesBtn" 
                        data-id="{{ $item->id }}" 
                        data-sku="{{ $item->code }}" 
                        data-bs-toggle="modal" 
                        data-bs-target="#uploadImagesModal"
                        title="Upload Images">
                        <i class="fa fa-image"></i>
                    </a>

                    <a href="javascript:void(0);" 
                        class="btn btn-sm btn-icon btn-outline-primary editBtn"
                        data-id="{{ $item->id }}" data-bs-toggle="tooltip" title="Edit">
                        <i class="fa fa-edit"></i>
                    </a>

                    <a href="javascript:void(0);" class="btn btn-sm btn-icon btn-outline-danger"
                        onclick="deleteUser({{ $item->id }})" data-bs-toggle="tooltip" title="Delete">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="small text-muted text-center">No data found</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="modal fade" id="editVariationModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="editVariationForm">
                @csrf
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Variation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-md-6">
                            <label>SKU Code</label>
                            <input type="text" class="form-control" name="code" id="edit_code">
                        </div>
                        <div class="col-md-6">
                            <label>Weight</label>
                            <input type="text" class="form-control" name="weight" id="edit_weight">
                        </div>
                        <div class="col-md-6">
                            <label>Price</label>
                            <input type="text" class="form-control" name="price" id="edit_price">
                        </div>
                        <div class="col-md-6">
                            <label>Offer Price</label>
                            <input type="text" class="form-control" name="offer_price" id="edit_offer_price">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update Variation</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
    <div class="modal fade" id="importWeightVariationModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.product.variation.import') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Weight Variation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p>
                    <strong>Sample Format:</strong> <br>
                    <code>material_code,weight,code,price,offer_price</code>
                </p>

                <a href="{{ asset('sample/weight_variation_sample.xlsx') }}" class="text-decoration-underline" download>
                    Download sample file
                </a>

                <div class="mb-3">
                    <label for="csv_file" class="form-label">Upload CSV File</label>
                    <input type="file" name="csv_file" id="csv_file" class="form-control" accept=".csv" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Import</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
            </form>
        </div>
    </div>

    <!-- Upload Images Modal -->
    <div class="modal fade" id="uploadImagesModal" tabindex="-1" aria-labelledby="uploadImagesLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.product.variation.uploadImages') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="product_variation_id" id="uploadProductId">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Upload Images for <span id="skuCodeText"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="images" class="form-label">Choose Images</label>
                            <input type="file" class="form-control" name="images[]" multiple required>
                            <small class="text-muted">You can upload multiple images.</small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Upload</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- View Images Modal -->
    <div class="modal fade" id="viewImagesModal" tabindex="-1" aria-labelledby="viewImagesLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Images for <span id="viewSkuCodeText"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row" id="imageGallery">
                        {{-- Images will be appended here by JS --}}
                    </div>
                </div>
            </div>
        </div>
    </div>



    {{$products->appends($_GET)->links()}}

</section>
@endsection

@section('script')
<script>

    $(document).on('click', '.uploadImagesBtn', function () {
        const productId = $(this).data('id');
        const skuCode = $(this).data('sku');

        $('#uploadProductId').val(productId);
        $('#skuCodeText').text(skuCode);
    });

    $(document).ready(function () {
        $('.editBtn').on('click', function () {
            let id = $(this).data('id');
            let url = "{{ route('admin.product.variation.edit', ':id') }}".replace(':id', id);

            $.get(url, function (res) {
                $('#edit_id').val(res.id);
                $('#edit_code').val(res.code);
                $('#edit_weight').val(res.weight);
                $('#edit_price').val(res.price);
                $('#edit_offer_price').val(res.offer_price);
                $('#editVariationModal').modal('show');
            });
        });

        $('#editVariationForm').on('submit', function (e) {
            e.preventDefault();
            let formData = $(this).serialize();

            $.ajax({
                url: "{{ route('admin.product.variation.update') }}",
                method: "POST",
                data: formData,
                success: function (res) {
                    if (res.status === 200) {
                        $('#editVariationModal').modal('hide');
                        toastFire('success', res.message);
                        location.reload();
                    } else {
                        toastFire('error', res.message);
                    }
                }
            });
        });
    });

    $(document).ready(function () {
        $('.viewImagesBtn').click(function () {
            const id = $(this).data('id');
            const sku = $(this).data('sku');
            $('#viewSkuCodeText').text(sku);
            $('#imageGallery').html('<p>Loading...</p>');
            $('#viewImagesModal').modal('show');

            $.get("{{ route('admin.product.variation.getImages', ':id') }}".replace(':id', id), function (data) {
                let html = '';

                if (data.length === 0) {
                    html = '<p class="text-center">No images found.</p>';
                } else {
                    data.forEach(img => {
                        html += `
                            <div class="col-md-3 mb-3 text-center">
                                <img src="/${img.image_path}" class="img-fluid mb-2" style="max-height: 150px;">
                                <button class="btn btn-sm btn-danger deleteImageBtn" data-id="${img.id}">Delete</button>
                            </div>
                        `;
                    });
                }

                $('#imageGallery').html(html);
            });
        });

        $(document).on('click', '.deleteImageBtn', function () {
            const btn = $(this);
            const id = btn.data('id');


            let deleteUrl = "{{ route('admin.product.variation.deleteImage', ':id') }}".replace(':id', id);
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function (response) {
                    if (response.success) {
                        btn.closest('.col-md-3').remove();
                        toastFire('success', response.message || 'Image deleted successfully!');
                    } else {
                        toastFire('error', response.message || 'Something went wrong.');
                    }
                },
                error: function () {
                    toastFire('error', 'Failed to delete image.');
                }
            });
        });
    });



  function deleteUser(userId) {
    Swal.fire({
        icon: 'warning',
        title: "Are you sure you want to delete this?",
        text: "You won't be able to revert this!",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Delete",
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('admin.product.variation.delete')}}",
                type: 'POST',
                data: {
                    "id": userId,
                    "_token": '{{ csrf_token() }}',
                },
                success: function (data){
                    if (data.status != 200) {
                        toastFire('error', data.message);
                    } else {
                        toastFire('success', data.message);
                        location.reload();
                    }
                }
            });
        }
    });
  }

    function statusToggle(url, checkbox) {
        fetch(url, {
            method: 'GET',
            headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 200) {
            toastFire('success', data.message);
            } else {
            toastFire('error', data.message);
            }
        })
        .catch(err => {
            toastFire('error', 'Something went wrong!');
            console.error(err);
        });
    }
</script>
@endsection