@extends('admin.layouts.app')

@section('page', 'Collection detail')

@section('content')
<section>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.color.update', $data->id) }}" enctype="multipart/form-data">
                    @csrf
                        <h4 class="page__subtitle">Edit Color</h4>
                        <div class="form-group mb-3">
                            <label class="label-control">Name <span class="text-danger">*</span> </label>
                            <input type="text" name="name" placeholder="" class="form-control" value="{{ $data->name }}">
                            @error('name') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Code </label>
                            <input name="code"  id="colorpicker" type="color" class="form-control" value="{{$data->code}}">
                            <input type="text" class="form-control" id="hexcolor" ></input>

                            @error('code') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-danger">Update Color</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
    <script>
        $('#colorpicker').on('input',function(){
            $('#hexcolor').val(this.value);
        });
        $('#hexcolor').on('input',function(){
            $('#colorpicker').val(this.value);
        });
    </script>
@endsection
