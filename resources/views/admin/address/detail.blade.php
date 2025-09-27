@extends('admin.layouts.app')

@section('page', 'Address detail')

@section('content')
<section>
    <div class="row">
        <div class="col-sm-8">
            <div class="card">    
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex">
                                <div>
                                    <h5>{{ucwords($data->user->fname.' '.$data->user->lname)}}</h5>
                                    <p class="text-muted small mb-0 mt-2">{{$data->user->email}}</p>
                                    <p class="text-muted small">{{$data->user->mobile}}</p>
                                </div>
                            </div>

                            <hr>

                            <p class="text-muted small mb-1">Street address</p>
                            <p class="text-dark small">{{ucwords($data->address)}}</p>

                            <p class="text-muted small mb-1">Landmark</p>
                            <p class="text-dark small">{{ucwords($data->landmark)}}</p>

                            <p class="text-muted small mb-1">State</p>
                            <p class="text-dark small">{{ucwords($data->state)}}</p>

                            <p class="text-muted small mb-1">City</p>
                            <p class="text-dark small">{{ucwords($data->city)}}</p>

                            <p class="text-muted small mb-1">Pincode</p>
                            <p class="text-dark small">{{$data->pin}}</p>
                        </div>
                    </div>  
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.address.update', $data->id) }}" enctype="multipart/form-data">
                    @csrf
                        <h4 class="page__subtitle">Edit</h4>
                        <div class="form-group mb-3">
                            <label class="label-control">User <span class="text-danger">*</span> </label>
                            <select class="form-control" name="user_id">
                                <option hidden selected>Select user...</option>
                                @foreach ($users as $index => $item)
                                    <option value="{{$item->id}}" {{ ($data->user_id == $item->id) ? 'selected' : ''  }}>{{ $item->fname.' '.$item->lname }}</option>
                                @endforeach
                            </select>
                            @error('name') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Address </label>
                            <textarea name="address" class="form-control">{{$data->address}}</textarea>
                            @error('address') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Landmark <span class="text-danger">*</span> </label>
                            <input type="text" name="landmark" placeholder="" class="form-control" value="{{$data->landmark}}">
                            @error('landmark') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">State <span class="text-danger">*</span> </label>
                            <input type="text" name="state" placeholder="" class="form-control" value="{{$data->state}}">
                            @error('state') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">City <span class="text-danger">*</span> </label>
                            <input type="text" name="city" placeholder="" class="form-control" value="{{$data->city}}">
                            @error('city') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Pin <span class="text-danger">*</span> </label>
                            <input type="number" name="pin" placeholder="" class="form-control" value="{{$data->pin}}">
                            @error('pin') <p class="small text-danger">{{ $message }}</p> @enderror
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