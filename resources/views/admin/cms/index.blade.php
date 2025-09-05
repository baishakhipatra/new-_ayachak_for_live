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
                            
                            <p class="text-muted small mb-1">Win Message</p>
                            <p class="text-dark small">{!!$data->success_message ?? ''	!!}</p>
                            <p class="text-muted small mb-1">Failure Message</p>
                            <p class="text-dark small">{!! $data->failure_message ??'' !!}</p>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.cms.store',['id'=>$data->id]) }}" enctype="multipart/form-data">
                    @csrf
                        <h4 class="page__subtitle">Edit</h4>
                        <div class="form-group mb-3">
                            <label class="label-control">Win Message <span class="text-danger">*</span><h3 class="text-danger">Please do not remove text "You have won Laptop."</h3> </label>
                            <textarea type="text" id="success_message" name="success_message" placeholder="" class="form-control">{{ $data->success_message }}</textarea>
                            @error('success_message') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label class="label-control">Failure Message <span class="text-danger">*</span> </label>
                            <textarea type="text" id="failure_message" name="failure_message" placeholder="" class="form-control" >{{ $data->failure_message }}</textarea>
                            @error('failure_message') <p class="small text-danger">{{ $message }}</p> @enderror
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