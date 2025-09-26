@extends('admin.layouts.app')

@section('page', 'Edit Product')

@section('content')

<style>
    .label-control {
        color: #525252;
        font-size: 12px;
    }
    .color_holder {
        display: flex;
        border: 1px dashed #ddd;
        border-radius: 6px;
        padding: 5px;
        background: #f0f0f0;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }
    .color_holder_single {
        margin: 5px;
    }
    .color_box {
        display: flex;
        padding: 6px 10px;
        border-radius: 3px;
        align-items: center;
        margin: 0;
        background: #fff;
    }
    .color_box p {
        margin: 0;
        margin-left: 10px;
    }
    .color_box span, .color_box img {
        display: inline-block;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        /* margin-right: 10px; */
    }
    .sizeUpload {
        margin-bottom: 10px;
    }
    .size_holder {
        padding: 10px 0;
        border-top: 1px solid #ddd;
    }
    .img_thumb img {
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        object-fit: cover;
    }
    .remove_image {
        display: inline-flex;
        width: 30px;
        height: 30px;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: #fff;
        background-color: #dc3545;
        border-color: #dc3545;
        position: absolute;
        top: 0;
        right: 0;
    }
    .remove_image i {
        line-height: 13px;
    }
    .image_upload {
        display: inline-flex;
        padding: 0 20px;
        border:  1px solid #ccc;
        background: #ddd;
        padding: 5px 12px;
        border-radius: 3px;
        vertical-align: top;
        cursor: pointer;
    }
    .status-toggle {
        padding: 6px 10px;
        border-radius: 3px;
        align-items: center;
        background: #fff;
    }
    .status-toggle a {
        text-decoration: none;
        color: #000
    }
    .color_holder {
        display: flex;
        border: 1px dashed #ddd;
        border-radius: 6px;
        padding: 5px;
        background: #f0f0f0;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }
    .color_holder_single {
        margin: 5px;
    }
    .color_box {
        display: flex;
        padding: 6px 10px;
        border-radius: 3px;
        align-items: center;
        margin: 0;
        background: #fff;
    }
    .sizeUpload {
        margin-bottom: 10px;
    }
    .img_thumb {
        width: 100%;
        padding-bottom: calc((4/3)*100%);
        position: relative;
        border:  1px solid #ccc;
        max-width: 80px;
        min-width: 80px;
    }
    .img_thumb img {
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        object-fit: cover;
    }
    .remove_image {
        display: inline-flex;
        width: 30px;
        height: 30px;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: #fff;
        background-color: #dc3545;
        border-color: #dc3545;
        position: absolute;
        top: 0;
        right: 0;
    }
    .remove_image i {
        line-height: 13px;
    }
    .image_upload {
        display: inline-flex;
        padding: 0 20px;
        border:  1px solid #ccc;
        background: #ddd;
        padding: 5px 12px;
        border-radius: 3px;
        vertical-align: top;
        cursor: pointer;
    }
    .status-toggle {
        padding: 6px 10px;
        border-radius: 3px;
        align-items: center;
        background: #fff;
    }
    .status-toggle a {
        text-decoration: none;
        color: #000
    }
    .color-fabric-image-holder {
        width: 36px;
        height: 36px;
    }
    .color-fabric-image {
        width: inherit;
        height: inherit;
        border-radius: 50%;
    }
    .change-image {
        position: absolute;
        bottom: -4px;
        right: -8px;
        background: #c1080a;
        width: 25px;
        height: 25px;
        border-radius: 50%;
        padding: 0 0;
    }
    .change-image .badge {
        padding: 3px;
        cursor: pointer;
    }
    .croppie-container {
        height: auto;
    }
</style>

<section>
    <form method="POST" action="{{ route('admin.product.update') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-sm-9">

                @if (Session::has('message'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>{{ Session::get('message') }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <label class="label-control">Category <span class="text-danger">*</span></label>
                                <select class="form-control" name="cat_id">
                                    <option hidden selected>Select...</option>
                                    @foreach ($categories as $index => $item)
                                        <option value="{{$item->id}}" {{ ($data->cat_id == $item->id) ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                @error('cat_id') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="label-control">Product Title <span class="text-danger">*</span></label>
                            <input type="text" name="name" placeholder="Add Product Title" class="form-control" value="{{$data->name}}">
                            @error('name') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <label class="label-control">Short Description <span class="text-danger">*</span></label>
                        <textarea id="product_short_des" name="short_desc">{{$data->short_desc}}</textarea>
                        @error('short_desc') <p class="small text-danger">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <label class="label-control">Description <span class="text-danger">*</span></label>
                        <textarea id="product_des" name="desc">{{$data->desc}}</textarea>
                        @error('desc') <p class="small text-danger">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="card shadow-sm">
                    <div class="card-body pt-0">
                        <div class="admin__content">
                        <aside>
                            <nav>Price <span class="text-danger">*</span></nav>
                        </aside>
                        <content>
                            <div class="row mb-2 align-items-center">
                            <div class="col-3">
                                <label for="inputPassword6" class="col-form-label">Regular Price</label>
                            </div>
                            <div class="col-9">
                                <input type="text" id="inputprice6" class="form-control" aria-describedby="priceHelpInline" name="price" value="{{$data->price}}">
                                @error('price') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>
                            </div>
                            <div class="row mb-2 align-items-center">
                            <div class="col-3">
                                <label for="inputprice6" class="col-form-label">Offer Price</label>
                            </div>
                            <div class="col-9">
                                <input type="text" id="inputprice6" class="form-control" aria-describedby="priceHelpInline" name="offer_price" value="{{$data->offer_price}}">
                                @error('offer_price') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>
                            </div>
                        </content>
                        </div>
                        <div class="admin__content">
                            <aside>
                                <nav>Meta</nav>
                            </aside>
                            <content>
                                <div class="row mb-2 align-items-center">
                                    <div class="col-3">
                                        <label for="inputPassword6" class="col-form-label">Title</label>
                                    </div>
                                    <div class="col-9">
                                        <input type="text" id="inputprice6" class="form-control" aria-describedby="priceHelpInline" name="meta_title" value="{{$data->meta_title}}">
                                        @error('meta_title') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="row mb-2 align-items-center">
                                    <div class="col-3">
                                        <label for="inputprice6" class="col-form-label">Description</label>
                                    </div>
                                    <div class="col-9">
                                        <input type="text" id="inputprice6" class="form-control" aria-describedby="priceHelpInline" name="meta_desc" value="{{$data->meta_desc}}">
                                        @error('meta_desc') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="row mb-2 align-items-center">
                                    <div class="col-3">
                                        <label for="inputprice6" class="col-form-label">Keyword</label>
                                    </div>
                                    <div class="col-9">
                                        <input type="text" id="inputprice6" class="form-control" aria-describedby="priceHelpInline" name="meta_keyword" value="{{$data->meta_keyword}}">
                                        @error('meta_keyword') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </content>
                        </div>
                        <div class="admin__content">
                            <aside>
                                <nav>Data <span class="text-danger">*</span></nav>
                            </aside>
                            <content>
                                <div class="row mb-2 align-items-center">
                                    <div class="col-3">
                                        <label for="inputPassword6" class="col-form-label">Product No</label>
                                    </div>
                                    <div class="col-9">
                                        <input type="text" id="inputprice6" class="form-control" aria-describedby="priceHelpInline" name="style_no" value="{{$data->style_no}}">
                                        @error('style_no') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="col-3">
                                        <label class="col-form-label">GST Applicable</label>
                                    </div>
                                    <div class="col-9">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="gstToggle" checked>
                                            <label class="form-check-label" for="gstToggle">Yes</label>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <label for="inputPassword6" class="col-form-label">GST(%)</label>
                                    </div>
                                    <div class="col-9">
                                        <input type="number" id="gst" class="form-control" aria-describedby="priceHelpInline" name="gst" value="{{$data->gst}}" step="0.01">
                                        @error('gst') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </content>
                        </div>
                        <div class="admin__content">
                            <aside>
                                <nav>Pack <span class="text-danger">*</span></nav>
                            </aside>
                            <content>
                                <div class="row mb-2 align-items-center">
                                    <div class="col-3">
                                        <label for="inputPassword6" class="col-form-label">Net Qty</label>
                                    </div>
                                    <div class="col-9">
                                        <input type="text" id="inputprice6" class="form-control" aria-describedby="priceHelpInline" name="pack" value="{{ old('pack') ? old('pack') : $data->pack }}">
                                        @error('pack') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </content>
                        </div>
                        <div class="card shadow-sm mt-3">
                            <div class="card-body">
                                <nav>Product Variation <span class="text-danger">*</span></nav>

                                <table class="table table-bordered" id="variationTable">
                                    <thead>
                                        <tr>
                                            <th>Weight</th>
                                            <th>SKU Code</th>
                                            <th>Price</th>
                                            <th>Offer Price</th>
                                            <th>Stock</th>
                                            <th>Variation Images</th>
                                            <th><button type="button" class="btn btn-sm btn-success" id="addVariation">+</button></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($product->variations as $index => $variation)
                                        <tr>
                                            <input type="hidden" name="variations[{{ $index }}][id]" value="{{ $variation->id }}">
                                            <td><input type="text" name="variations[{{ $index }}][weight]" class="form-control" value="{{ $variation->weight }}"></td>
                                            <td><input type="text" name="variations[{{ $index }}][code]" class="form-control" value="{{ $variation->code }}"></td>
                                            <td><input type="number" step="0.01" name="variations[{{ $index }}][price]" class="form-control" value="{{ $variation->price }}"></td>
                                            <td><input type="number" step="0.01" name="variations[{{ $index }}][offer_price]" class="form-control" value="{{ $variation->offer_price }}"></td>
                                            <td><input type="number" name="variations[{{ $index }}][stock]" class="form-control" value="{{ $variation->stock }}"></td>
                                            <td>
                                                <input type="file" name="variations[{{ $index }}][images][]" class="form-control" multiple>
                                                <small class="text-muted">You can upload multiple images for this variation.</small>
                                                <div class="existing-images mt-1">
                                                    @foreach($variation->images as $img)
                                                        <img src="{{ asset($img->image_path) }}" style="width: 50px; height: 50px;" class="me-1 mb-1">
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td><button type="button" class="btn btn-sm btn-danger removeVariation">x</button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card shadow-sm">
                    <div class="card-header">
                        Product Thumbnail Image
                    </div>
                    <div class="card-body">
                        <div class="w-100 product__thumb">
                            <label for="thumbnail"><img id="output" src="{{ asset($data->image) }}"/></label>
                            @error('image') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <input type="file" id="thumbnail" accept="image/*" name="image" onchange="loadFile(event)" class="d-none">
                        <small>Image Size: 870px X 1160px</small>
                        <script>
                            var loadFile = function(event) {
                                var output = document.getElementById('output');
                                output.src = URL.createObjectURL(event.target.files[0]);
                                output.onload = function() {
                                URL.revokeObjectURL(output.src) // free memory
                                }
                            };
                        </script>
                    </div>
                </div>
                <div class="card shadow-sm" style="position: sticky;top: 60px;">
                    <div class="card-body text-end">
                        <input type="hidden" name="product_id" value="{{$data->id}}">
                        <button type="submit" class="btn btn-danger w-100">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>

        ClassicEditor.create( document.querySelector( '#product_des' ) ).catch( error => {
            console.error( error );
        });

        ClassicEditor.create( document.querySelector( '#product_short_des' ) ).catch( error => {
            console.error( error );
        });

        function deleteImage(imgId, id1, id2) {
            $.ajax({
                url : '{{route("admin.product.variation.image.delete")}}',
                method : 'POST',
                data : {'_token' : '{{csrf_token()}}', id : imgId},
                beforeSend : function() {
                    $('#img__holder_'+id1+'_'+id2+' a').text('Deleting...');
                },
                success : function(result) {
                    $('#img__holder_'+id1+'_'+id2).hide();
                    toastFire('success', result.message);
                },
                error: function(xhr, status, error) {
                    // toastFire('danger', 'Something Went wrong');
                }
            });
        }

        $(".row_position").sortable({
            delay: 150,
            stop: function() {
                var selectedData = new Array();
                $('.row_position > .single-color-holder').each(function() {
                    selectedData.push($(this).attr("id"));
                });
                updateOrder(selectedData);
            }
        });


        // image fabric upload
        $image_crop = $('#image_demo').croppie({
            enableExif: true,
            viewport: {
                width: 150,
                height: 150,
                type: 'circle'
            },
            boundary: {
                width: 200,
                height: 200
            }
        });

        // $('#upload_image').on('change', function () {
        $('input[name=upload_image]').on('change', function () {
            var reader = new FileReader();
            reader.onload = function (event) {
                $image_crop.croppie('bind', {
                    url: event.target.result
                });
            }
            reader.readAsDataURL(this.files[0]);
            $('#uploadimageModal').modal('show');
        });




        // bulk action
        $('select[name="bulkAction"]').on('change', function() {
            $('#bulkActionForm').submit();
        });

        $(document).ready(function () {
            function toggleGstField() {
                if ($('#gstToggle').is(':checked')) {
                    $('#gst').prop('disabled', false).prop(true);
                } else {
                    $('#gst').val('').prop('disabled', true).prop(false);
                }
            }

            // Run on load
            toggleGstField();

            // Run on toggle change
            $('#gstToggle').on('change', function () {
                toggleGstField();
            });
        });


        let variationIndex = {{ $product->variations->count() }};

        document.getElementById('addVariation').addEventListener('click', function() {
            let tableBody = document.querySelector('#variationTable tbody');
            let row = `
                <tr>
                    <td><input type="text" name="variations[${variationIndex}][weight]" class="form-control"></td>
                    <td><input type="text" name="variations[${variationIndex}][code]" class="form-control"></td>
                    <td><input type="number" step="0.01" name="variations[${variationIndex}][price]" class="form-control"></td>
                    <td><input type="number" step="0.01" name="variations[${variationIndex}][offer_price]" class="form-control"></td>
                    <td><input type="number" name="variations[${variationIndex}][stock]" class="form-control"></td>
                    <td>
                        <input type="file" name="variations[${variationIndex}][images][]" class="form-control" multiple>
                    </td>
                    <td><button type="button" class="btn btn-sm btn-danger removeVariation">x</button></td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', row);
            variationIndex++;
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('removeVariation')) {
                e.target.closest('tr').remove();
            }
        });

    </script>
@endsection
