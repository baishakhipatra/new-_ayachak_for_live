@extends('admin.layouts.app')

@section('page', 'Target')

@section('content')
<section>
    <div class="row">



        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.target.store') }}" enctype="multipart/form-data">
                    @csrf

                        <h4 class="page__subtitle">Add New Target</h4>
                        <div class="form-group mb-3">
                            <label class="label-control">Title <span class="text-danger">*</span> </label>
                            <input type="text" name="title" placeholder="" class="form-control" value="{{old('title')}}">
                            @error('title') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Collection <span class="text-danger">*</span> </label>
                            <select id="collection_id" name="collection_id" class="form-control">
                                <option value="">--- Select  ---</option>
                                @foreach ($collection as $index => $item)
                                <option value="{{$item->id}}" {{ old('collection_id') ?? (old('collection_id') == $item->id) ? 'selected' : ''}}> {{$item->name}}<br></option>

                             @endforeach
                            </select>
                            @error('title') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Person Name  <span class="text-danger">*</span> </label>
                            <select id="user_id" name="user_id" class="form-control">
                                <option value="">--- Select  ---</option>
                                @foreach ($users as $index => $item)
                                <option value="{{$item->id}}" {{ old('user_id') ?? (old('user_id') == $item->id) ? 'selected' : ''}}> {{$item->name}}<br></option>

                             @endforeach
                            </select>
                            @error('user_id') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Designation  <span class="text-danger">*</span> </label>
                            <select id="user_type" name="user_type" class="form-control">
                            <option value="">--- Select  ---</option>

                            <option value="1">VP</option>
                            <option value="2">RSM</option>
                            <option value="3">ASM</option>
                            <option value="4">ASE</option>
                            <option value="5">Distributor</option>
                            <option value="6">Retailer</option>
                        </select>
                            @error('user_type') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Amount <span class="text-danger">*</span> </label>
                            <input type="text" name="amount" placeholder="" class="form-control" value="{{old('amount')}}">
                            @error('amount') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="label-control">Year From<span class="text-danger">*</span> </label>
                            <input type="text" name="year_from" placeholder="" class="form-control" value="{{old('year_from')}}">
                            @error('year_from') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Year To <span class="text-danger">*</span> </label>
                            <input type="text" name="year_to" placeholder="" class="form-control" value="{{old('year_to')}}">
                            @error('year_to') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Remarks <span class="text-danger">*</span> </label>
                            <input type="text" name="remarks" placeholder="" class="form-control" value="{{old('remarks')}}">
                            @error('remarks') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-danger">Add New Target</button>
                            <a type="submit" href="{{ route('admin.target.index') }}" class="btn btn-sm btn-danger">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
