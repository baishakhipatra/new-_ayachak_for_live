@extends('admin.layouts.app')

@section('page', 'scan and win')

@section('content')
<section>
    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            
                            <p class="text-muted small mb-1">Tracking Id</p>
                            <p class="text-dark small">{!!$dispatch->tracking_id ?? ''	!!}</p>
                            <p class="text-muted small mb-1">Images</p>
                            <div class="card shadow-sm">
                    			<div class="card-header">Images</div>
                    				<div class="card-body">
                        				<div class="w-100 product__thumb">
                        					@foreach($images as $index => $singleImage)
                            					<label for="thumbnail"><img id="output" src="{{ asset($singleImage->image) }}" class="img-thumbnail mb-3"/></label>
                        					@endforeach
                        				</div>
                    				</div>
                				</div>
							</div>
                        </div>
                    </div>
                </div>
            </div>
      
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.customers.dispatch.store') }}" enctype="multipart/form-data">
                    @csrf
						<input type="hidden" name="user_id" value="{{$data->id}}">
						<input type="hidden" name="gift_id" value="{{$data->gift_id}}">
                        <h4 class="page__subtitle">Edit</h4>
                        <div class="form-group mb-3">
                            <label class="label-control">Tracking Id <span class="text-danger">*</span></label>
                            <input type="text" id="tracking_id" name="tracking_id" placeholder="" class="form-control" value="{{ $data->tracking_id }}">
                            @error('success_message') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        
                        <div class="card shadow-sm">
							<div class="card-header">
								 Image
							</div>
							<div class="card-body">
								<input type="file" accept="image/*" name="image[]" multiple>
								@error('product_images') <p class="small text-danger">{{ $message }}</p> @enderror
							</div>
            			</div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-danger">Update</button>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
       
    </div>
    <br>
    
</section>
@endsection
@section('script')
<script>
 ClassicEditor
        .create( document.querySelector( '#success_message' ) )
        .catch( error => {
            console.error( error );
        });
	ClassicEditor
        .create( document.querySelector( '#failure_message' ) )
        .catch( error => {
            console.error( error );
        });
</script>
@endsection