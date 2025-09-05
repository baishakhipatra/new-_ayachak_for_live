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
                                @if($data->user->image)
                                    <img src="{{asset($data->user->image)}}" alt="" style="height: 100px" class="mr-4">
                                @else
                                    <img src="{{asset('backend_asset/images/placeholder-image.jpg')}}" alt="" class="me-4" style="width: 65px;height: 65px;border-radius: 50%;">
                                @endif
                                <div>
                                    <h5>{{$data->user->fname.' '.$data->user->lname}}</h5>
                                    <p class="text-muted small mb-0 mt-2">{{$data->user->email}}</p>
                                    <p class="text-muted small">{{$data->user->mobile}}</p>
                                </div>
                            </div>

                            <hr>

                            <p class="text-muted small mb-1">Street address</p>
                            <p class="text-dark small">{{$data->address}}</p>

                            <p class="text-muted small mb-1">Landmark</p>
                            <p class="text-dark small">{{$data->landmark}}</p>

                            <p class="text-muted small mb-1">LAT & LNG</p>
                            <p class="text-dark small">{{$data->lat.' | '.$data->lng}}</p>

                            <p class="text-muted small mb-1">State</p>
                            <p class="text-dark small">{{$data->state}}</p>

                            <p class="text-muted small mb-1">City</p>
                            <p class="text-dark small">{{$data->city}}</p>

                            <p class="text-muted small mb-1">Pincode</p>
                            <p class="text-dark small">{{$data->pin}}</p>

                            <p class="text-muted small mb-1">Type</p>
                            <p class="text-dark small">{{ ($data->type == 1) ? 'Home' : ($data->type == 2 ? 'Work' : 'Other') }}</p>
                        </div>
                        {{-- <div class="col-md-8">
                            <h5 class="text-muted">Address details</h5>
                            <h3>{{ $data->name }}</h3>
                            <p class="small">{{ $data->description }}</p>
                        </div> --}}
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
                            <label class="label-control">Latitude </label>
                            <input type="text" name="lat" placeholder="" class="form-control" value="{{$data->lat}}">
                            @error('lat') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Longitude</label>
                            <input type="text" name="lng" placeholder="" class="form-control" value="{{$data->lng}}">
                            @error('lng') <p class="small text-danger">{{ $message }}</p> @enderror
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
                        <div class="form-group mb-3">
                            <label class="label-control">Type <span class="text-danger">*</span> </label>
                            <input type="text" name="type" placeholder="" class="form-control" value="{{$data->type}}">
                            @error('type') <p class="small text-danger">{{ $message }}</p> @enderror
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