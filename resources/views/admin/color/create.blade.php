@extends('admin.layouts.app')

@section('page', 'Collection')

@section('content')
<section>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
              <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.color.store') }}" enctype="multipart/form-data">
                    @csrf
                        <h4 class="page__subtitle">Add New Color</h4>
                        <div class="form-group mb-3">
                            <label class="label-control">Name <span class="text-danger">*</span> </label>
                            <input type="text" name="name" placeholder="" class="form-control" value="{{old('name')}}">
                            @error('name') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Code </label>
                            <input name="code" type="color"  id="colorpicker" class="form-control" value="{{old('code')}}" >
                            <input type="text" class="form-control" id="hexcolor" ></input>
                            @error('code') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-danger">Add New Color</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
	