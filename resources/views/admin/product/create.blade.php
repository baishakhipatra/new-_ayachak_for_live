@extends('admin.layouts.app')

@section('page', 'Create Product')

@section('content')
<style>
    .label-control {
        color: #525252;
        font-size: 12px;
    }
</style>

<section>
    <form method="post" action="{{ route('admin.product.store') }}" enctype="multipart/form-data">@csrf
        <div class="row">
            <div class="col-lg-9">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row mb-3">
                            {{-- <div class="col-sm-6">
                                <label class="label-control">Range <span class="text-danger">*</span></label>
                                <select class="form-control" name="collection_id">
                                    <option hidden selected>Select...</option>
                                    @foreach ($collections as $index => $item)
                                        <option value="{{$item->id}}" {{ (old('collection_id') == $item->id) ? 'selected' : ''  }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                @error('collection_id') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div> --}}

                            <div class="col-sm-6">
                                <label class="label-control">Category <span class="text-danger">*</span></label>
                                <select class="form-control" name="cat_id">
                                    <option hidden selected>Select...</option>
                                    @foreach ($categories as $index => $item)
                                        <option value="{{$item->id}}" {{ (old('cat_id') == $item->id) ? 'selected' : ''}}>{{ $item->name }} </option>
                                    @endforeach
                                </select>
                                @error('cat_id') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="row align-items-center">
                            <div class="col-sm-12">
                                <label class="label-control">Product Title <span class="text-danger">*</span></label>
                                <div class="form-group mb-3">
                                    <input type="text" name="name" placeholder="Add Product Title" class="form-control" value="{{old('name')}}">
                                    @error('name') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <label class="label-control">Short Description <span class="text-danger">*</span></label>
                        <textarea id="product_short_des" name="short_desc">{{old('short_desc')}}</textarea>
                        @error('short_desc') <p class="small text-danger">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <label class="label-control">Description <span class="text-danger">*</span></label>
                        <textarea id="product_des" name="desc">{{old('desc')}}</textarea>
                        @error('desc') <p class="small text-danger">{{ $message }}</p> @enderror
                    </div>
                </div>
                {{-- <div class="card shadow-sm">
                    <div class="card-body">
                        <label class="label-control">Wash Care <span class="text-danger">*</span></label>
                        <textarea id="wash_care" name="wash_care">{{old('wash_care')}}</textarea>
                        @error('wash_care') <p class="small text-danger">{{ $message }}</p> @enderror
                    </div>
                </div> --}}
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
                                    <input type="text" id="inputprice6" class="form-control" aria-describedby="priceHelpInline" name="price" value="{{old('price')}}">
                                    @error('price') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="row mb-2 align-items-center">
                                <div class="col-3">
                                    <label for="inputprice6" class="col-form-label">Offer Price</label>
                                </div>
                                <div class="col-9">
                                    <input type="text" id="inputprice6" class="form-control" aria-describedby="priceHelpInline" name="offer_price" value="{{old('offer_price')}}">
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
                                        <input type="text" id="inputprice6" class="form-control" aria-describedby="priceHelpInline" name="meta_title" value="{{old('meta_title')}}">
                                        @error('meta_title') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="row mb-2 align-items-center">
                                    <div class="col-3">
                                        <label for="inputprice6" class="col-form-label">Description</label>
                                    </div>
                                    <div class="col-9">
                                        <input type="text" id="inputprice6" class="form-control" aria-describedby="priceHelpInline" name="meta_desc" value="{{old('meta_desc')}}">
                                        @error('meta_desc') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="row mb-2 align-items-center">
                                    <div class="col-3">
                                        <label for="inputprice6" class="col-form-label">Keyword</label>
                                    </div>
                                    <div class="col-9">
                                        <input type="text" id="inputprice6" class="form-control" aria-describedby="priceHelpInline" name="meta_keyword" value="{{old('meta_keyword')}}">
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
                                        <input type="text" id="inputprice6" class="form-control" aria-describedby="priceHelpInline" name="style_no" value="{{old('style_no')}}">
                                        @error('style_no') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="row mb-2 align-items-center">
                                    <div class="col-3">
                                        <label class="col-form-label">GST Applicable</label>
                                    </div>
                                    <div class="col-9">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="gstToggle" checked>
                                            <label class="form-check-label" for="gstToggle">Yes</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2 align-items-center">
                                    <div class="col-3">
                                        <label for="inputPassword6" class="col-form-label">Gst(%)</label>
                                    </div>
                                    <div class="col-9">
                                        <input type="number" id="gst" name="gst" class="form-control" aria-describedby="priceHelpInline" name="gst" value="{{old('gst')}}" step="0.01">
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
                                        <input type="text" id="inputprice6" class="form-control" aria-describedby="priceHelpInline" name="pack" value="{{ old('pack') ? old('pack') : '1 PC' }}">
                                        @error('pack') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </content>
                        </div>
                    </div>
                </div>

                {{-- <div class="card shadow-sm">
                    <div class="card-header">
                        Product Variation
                    </div>
                    <div class="card-body pt-0"  id="timePriceTable">
                        <div class="admin__content">
                            <content>
                                <div class="row mb-2 align-items-center">
                                    <div class="col-2">
                                        <label for="inputPassword6" class="col-form-label">Color</label>
                                    </div>
                                    <div class="col-3">
                                        <select class="form-control" name="color[]">
                                            <option value="" disabled hidden selected>Select...</option>
                                            @foreach($colors as $colorIndex => $colorValue)
                                                <option value="{{$colorValue->id}}" @if (old('color') && in_array($colorValue,old('color'))){{('selected')}}@endif>{{$colorValue->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <label for="inputPassword6" class="col-form-label">Size</label>
                                    </div>
                                    <div class="col-3">
                                        <select class="form-control" name="size[]">
                                            <option value="" disabled hidden selected>Select...</option>
                                            @foreach($sizes as $sizeIndex => $sizeValue)
                                                <option value="{{$sizeValue->id}}">{{$sizeValue->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-1">
                                        <a class="btn btn-sm btn-success actionTimebtn addNewTime">+</a>
                                    </div>
                                </div>
                            </content>
                        </div>
                    </div>
                </div> --}}
            </div>

            <div class="col-sm-3">
                <div class="card shadow-sm">
                    <div class="card-header">
                        Product Image<span class="text-danger">*</span>
                    </div>
                    <div class="card-body">
                        <div class="w-100 product__thumb">
                        <label for="thumbnail"><img id="output" src="{{ asset('backend_asset/images/placeholder-image.jpg') }}"/></label>
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
                        <button type="submit" class="btn btn-danger w-100">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
@endsection

@section('script')
<script>
    ClassicEditor.create( document.querySelector( '#product_des' ) ).catch( error => {
        console.error( error );
    });

    ClassicEditor.create( document.querySelector( '#product_short_des' ) ).catch( error => {
        console.error( error );
    });
    ClassicEditor.create( document.querySelector( '#wash_care' ) ).catch( error => {
        console.error( error );
    });

    $(document).on('click','.addNewTime',function(){
		var thisClickedBtn = $(this);
		thisClickedBtn.removeClass(['addNewTime','btn-success']);
		thisClickedBtn.addClass(['removeTimePrice','btn-danger']).text('X');

		var toAppend = `
        <div class="admin__content">
            <content>
                <div class="row mb-2 align-items-center">
                    <div class="col-2">
                        <label for="inputPassword6" class="col-form-label">Color</label>
                    </div>
                    <div class="col-3">
                        <select class="form-control" name="color[]">
                            <option value="" disabled hidden selected>Select...</option>
                            @foreach($colors as $colorIndex => $colorValue)
                                <option value="{{$colorValue->id}}" @if (old('color') && in_array($colorValue,old('color'))){{('selected')}}@endif>{{$colorValue->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-2">
                        <label for="inputPassword6" class="col-form-label">Size</label>
                    </div>
                    <div class="col-3">
                        <select class="form-control" name="size[]">
                            <option value="" disabled hidden selected>Select...</option>
                            @foreach($sizes as $sizeIndex => $sizeValue)
                                <option value="{{$sizeValue->id}}">{{$sizeValue->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-1">
                        <a class="btn btn-sm btn-success actionTimebtn addNewTime">+</a>
                    </div>
                </div>
            </content>
        </div>
        `;

		$('#timePriceTable').append(toAppend);
	});

	$(document).on('click','.removeTimePrice',function(){
		var thisClickedBtn = $(this);
		thisClickedBtn.closest('.admin__content').remove();
	});

    $(document).ready(function () {
        function toggleGstField() {
            if ($('#gstToggle').is(':checked')) {
                $('#gst').prop('disabled', false).prop('required', true);
            } else {
                $('#gst').val('').prop('disabled', true).prop('required', false);
            }
        }

        // Run on load
        toggleGstField();

        // Run on toggle change
        $('#gstToggle').on('change', function () {
            toggleGstField();
        });
    });
</script>
@endsection
