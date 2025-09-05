@extends('admin.layouts.app')

@section('page', 'Target')

@section('content')
<section>
    <div class="row">



        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.target.update', $data->id) }}" enctype="multipart/form-data">
                    @csrf
                        <h4 class="page__subtitle">Edit Target</h4>
                        <div class="form-group mb-3">
                            <label class="label-control">Title <span class="text-danger">*</span> </label>
                            <input type="text" name="title" placeholder="" class="form-control" value="{{ $data->title }}">
                            @error('title') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Collection <span class="text-danger">*</span> </label>
                            <select class="form-control" name="collection_id">
                                <option hidden selected>Select Collection...</option>
                                @foreach ($collection as $index => $item)
                                    <option value="{{$item->id}}" {{ ($data->collection_id == $item->id) ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('collection_id') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Person Name  <span class="text-danger">*</span> </label>
                            <select class="form-control" name="user_id">
                                <option hidden selected>Select Person...</option>
                                @foreach ($users as $index => $item)
                                    <option value="{{$item->id}}" {{ ($data->user_id == $item->id) ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Designation  <span class="text-danger">*</span> </label>
                            <select id="user_type" name="user_type" class="form-control">
                                <option value="">--- Select  ---</option>

                                <option value="1" {{ ($data->user_type == "1") ? 'selected' : '' }}>VP</option>
                                <option value="2" {{ ($data->user_type == "2") ? 'selected' : '' }}>RSM</option>
                                <option value="3" {{ ($data->user_type == "3") ? 'selected' : '' }}>ASM</option>
                                <option value="4" {{ ($data->user_type == "4") ? 'selected' : '' }}>ASE</option>
                                <option value="5" {{ ($data->user_type == "5") ? 'selected' : '' }}>Distributor</option>
                                <option value="6" {{ ($data->user_type == "6") ? 'selected' : '' }}>Retailer</option>
                            </select>
                            @error('to') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Amount <span class="text-danger">*</span> </label>
                            <input type="text" name="amount" placeholder="" class="form-control" value="{{ $data->amount }}">
                            @error('amount') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="label-control">Year From<span class="text-danger">*</span> </label>
                            <input type="text" name="year_from" placeholder="" class="form-control" value="{{ $data->year_from }}">
                            @error('year_from') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Year To <span class="text-danger">*</span> </label>
                            <input type="text" name="year_to" placeholder="" class="form-control" value="{{ $data->year_to }}">
                            @error('year_to') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Remarks <span class="text-danger">*</span> </label>
                            <input type="text" name="remarks" placeholder="" class="form-control" value="{{ $data->remarks }}">
                            @error('remarks') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-danger">Update Target</button>
                            <a type="submit" href="{{ route('admin.target.index') }}" class="btn btn-sm btn-danger">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
