@extends('admin.layouts.app')

@section('page', 'Product detail')

@section('content')
<section>
    <form method="post" action="{{ route('admin.product.update', $data->id) }}" enctype="multipart/form-data">@csrf
        <div class="row">
            <div class="col-sm-3">
                <div class="card shadow-sm">
                    <div class="card-header">Main image</div>
                    <div class="card-body">
                        <div class="w-100 product__thumb">
                            <label for="thumbnail"><img id="output" src="{{ asset($data->image) }}"/></label>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm">
                    <div class="card-header">More images</div>
                    <div class="card-body">
                        <div class="w-100 product__thumb">
                        @foreach($images as $index => $singleImage)
                            <label for="thumbnail"><img id="output" src="{{ asset($singleImage->image) }}" class="img-thumbnail mb-3"/></label>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-9">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <h2>{{$data->name}}</h2>
                        </div>
                        <div class="form-group mb-3">
                            <p>
								@if($data->category) <span class="text-muted">Category : </span>{{$data->category->name}} @endif
							</p>
                        </div>
                        
                        <hr>
                        <div class="form-group mb-3">
                            <h4>
                                <span class="text-muted small"><del>Rs {{$data->price}}</del></span>
                                <span class="text-danger">Rs {{$data->offer_price}}</span>
                            </h4>
                        </div>
                        <hr>
                        @if(!empty($data->gst) && $data->gst > 0)
                            <div class="form-group mb-3">
                                <h4>
                                    <span class="text-small">GST:{{ $data->gst }}%</span>
                                </h4>
                            </div>
                        @endif
                        <hr>
                        <div class="form-group mb-3">
                            <p class="small">Short Description</p>
                            {{ ucfirst(html_entity_decode(strip_tags($data->short_desc))) }}

                        </div>
                        <hr>
                        <div class="form-group mb-3">
                            <p class="small">Description</p>
                            {{ ucfirst(html_entity_decode(strip_tags($data->desc))) }}

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
                                        <p class="small">{{$data->meta_title}}</p>
                                    </div>
                                </div>
                                <div class="row mb-2 align-items-center">
                                    <div class="col-3">
                                        <label for="inputprice6" class="col-form-label">Description</label>
                                    </div>
                                    <div class="col-9">
                                        <p class="small">{{$data->meta_desc}}</p>
                                    </div>
                                </div>
                                <div class="row mb-2 align-items-center">
                                    <div class="col-3">
                                        <label for="inputprice6" class="col-form-label">Keyword</label>
                                    </div>
                                    <div class="col-9">
                                        <p class="small">{{$data->meta_keyword}}</p>
                                    </div>
                                </div>
                            </content>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
@endsection

@section('script')
    <script>
        function sizeCheck(productId, colorId) {
            $.ajax({
                url : '{{route("admin.product.size")}}',
                method : 'POST',
                data : {'_token' : '{{csrf_token()}}', productId : productId, colorId : colorId},
                success : function(result) {
                    if (result.error === false) {
                        let content = '<div class="btn-group" role="group" aria-label="Basic radio toggle button group">';

                        $.each(result.data, (key, val) => {
                            content += `<input type="radio" class="btn-check" name="productSize" id="productSize${val.sizeId}" autocomplete="off"><label class="btn btn-outline-primary px-4" for="productSize${val.sizeId}">${val.sizeName}</label>`;
                        })

                        content += '</div>';

                        $('#sizeContainer').html(content);
                    }
                },
                error: function(xhr, status, error) {
                    // toastFire('danger', 'Something Went wrong');
                }
            });
        }
    </script>
@endsection