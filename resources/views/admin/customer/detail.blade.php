@extends('admin.layouts.app')

@section('page', 'Customer detail')

@section('content')
<section>
    <div class="row">
        <div class="col-sm-8">
            <div class="card">    
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                        @if($data->image)
                            <img src="{{asset($data->image)}}" alt="" style="height: 100px" class="mr-4">
                        @else
                            <img src="{{asset('backend_asset/images/placeholder-image.jpg')}}" alt="" class="mr-4" style="width: 100px;height: 100px;border-radius: 50%;">
                        @endif
                        </div>
                        <div class="col-md-10">
                            <h3>{{ $data->fname.' '.$data->lname }}</h3>
                            <p class="small">{{ $data->email }}</p>
                            <p class="small">{{ $data->mobile }}</p>
                            <p class="small">{{ strtoupper($data->gender) }}</p>
                        </div>
                    </div>  
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.customer.update', $data->id) }}" enctype="multipart/form-data">
                    @csrf
                        <h4 class="page__subtitle">Edit</h4>
                        <div class="form-group mb-3">
                            <label class="label-control">First Name <span class="text-danger">*</span> </label>
                            <input type="text" name="fname" placeholder="" class="form-control" value="{{$data->fname}}">
                            @error('fname') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Last Name <span class="text-danger">*</span> </label>
                            <input type="text" name="lname" placeholder="" class="form-control" value="{{$data->lname}}">
                            @error('lname') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Mobile <span class="text-danger">*</span> </label>
                            <input type="number" name="mobile" placeholder="" class="form-control" value="{{$data->mobile}}">
                            @error('mobile') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Gender <span class="text-danger">*</span> </label>
                            <select class="form-control" name="gender">
                                <option value="" hidden selected>Select...</option>
                                <option value="male" {{ ($data->gender == "male") ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ ($data->gender == "female") ? 'selected' : '' }}>Female</option>
                                <option value="trans" {{ ($data->gender == "trans") ? 'selected' : '' }}>Trans</option>
                                <option value="other" {{ ($data->gender == "other") ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        @if(request()->get('mode') == 'edit')
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-danger">Update</button>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection