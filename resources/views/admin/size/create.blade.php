@extends('admin.layouts.app')

@section('page', 'Size')

@section('content')
<section>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
              <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.size.store') }}" enctype="multipart/form-data">
                    @csrf
                        <h4 class="page__subtitle">Add New size</h4>
                        <div class="form-group mb-3">
                            <label class="label-control">Name <span class="text-danger">*</span> </label>
                            <input type="text" name="name" placeholder="" class="form-control" value="{{old('name')}}">
                            @error('name') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-danger">Add New size</button>
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
	