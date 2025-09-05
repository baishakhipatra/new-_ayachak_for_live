@extends('admin.layouts.app')

@section('page', 'Offer detail')

@section('content')
<section>
    <div class="row">
        <div class="col-sm-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <h3> {{ $data->title }}</h3>

                        </div><br><br><br>
                        <div class="col-md-10">
                            <img src="{{ asset($data->image) }}" alt="" style="height: 50px" class="mr-4">
                        </div><br><br><br>
                        <div class="col-md-10">
                            <h3>{{ $data->tilte }}</h3>
                            <p class="small"><a href="{{ asset($data->pdf) }}" target="_blank"><i class="app-menu__icon fa fa-download"></i>Document</a></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.offer.update', $data->id) }}" enctype="multipart/form-data">
                    @csrf
                        <h4 class="page__subtitle">Edit</h4>
                        <div class="form-group mb-3">
                            <label class="label-control">Tilte <span class="text-danger">*</span> </label>
                            <input type="text" name="title" placeholder="" class="form-control" value="{{ $data->title }}">
                            @error('title') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>



                        <div class="form-group mb-3">
                            <label class="label-control">Status <span class="text-danger">*</span> </label>
                            <select class="form-control" name="is_current">
                                <option value="" hidden selected>Select...</option>
                                <option value="1" {{ ($data->is_current == "1") ? 'selected' : '' }}>Current</option>
                                <option value="0" {{ ($data->is_current == "0") ? 'selected' : '' }}>Past</option>


                            </select>
                            @error('is_current') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="label-control">Start date <span class="text-danger">*</span> </label>
                            <input type="date" name="start_date" placeholder="" class="form-control" value="{{ date('Y-m-d', strtotime($data->start_date)) }}">
                            @error('start_date') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">End date <span class="text-danger">*</span> </label>
                            <input type="date" name="end_date" placeholder="" class="form-control" value="{{ date('Y-m-d', strtotime($data->end_date)) }}">
                            @error('end_date') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>

                        <div class="card">
                            <div class="card-header p-0 mb-3">Image <span class="text-danger">*</span></div>
                            <div class="card-body p-0">
                                <div class="w-100 product__thumb">
                                    <label for="thumbnail"><img id="output" src="{{ asset($data->image) }}" /></label>
                                </div>
                                <input type="file" name="image" id="thumbnail" accept="image/*" onchange="loadFile(event)" class="d-none">
                                <script>
                                    var loadFile = function(event) {
                                        var output = document.getElementById('output');
                                        output.src = URL.createObjectURL(event.target.files[0]);
                                        output.onload = function() {
                                            URL.revokeObjectURL(output.src) // free memory
                                        }
                                    };
                                </script>
                            </div>
                            @error('image') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>


                        <div class="card">
                            <div class="card-header p-0 mb-3">Document <span class="text-danger">*</span></div>
                            <div class="card-body p-0">
                                <div class="card-body p-0">
                                    <div class="form-group">
                                        <label for="upload_file" class="control-label col-sm-3">Upload File</label>
                                        <div class="col-sm-9">
                                             <input class="form-control" type="file" name="pdf" id="pdf">
                                        </div>
                                   </div>
                                </div>
                            @error('pdf') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <br>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-danger">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
