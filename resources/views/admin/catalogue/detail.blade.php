@extends('admin.layouts.app')

@section('page', 'Catalogue detail')

@section('content')
<section>
    <div class="row">
        <div class="col-sm-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>{{ $data->title }}</h3>
                            <p class="small">Start Date: {{ $data->start_date }}</p>
                            <p class="small">End Date : {{ $data->end_date }}</p>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <p class="text-muted">Image</p>
                            <img src="{{ asset($data->image) }}" alt="" style="height: 50px">
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted">Pdf</p>
                            <a href="{{ asset($data->pdf) }}" target="_blank"><i class="app-menu__icon fa fa-download"></i>Pdf</a>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.catalogue.update', $data->id) }}" enctype="multipart/form-data">
                    @csrf
                        <h4 class="page__subtitle">Edit Collection</h4>
                        <div class="form-group mb-3">
                            <label class="label-control">Title <span class="text-danger">*</span> </label>
                            <input type="text" name="title" placeholder="" class="form-control" value="{{ $data->title }}">
                            @error('title') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Start Date </label>
                            <input type="date" name="start_date" class="form-control" value="{{ $data->start_date }}">
                            @error('start_date') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">End Date </label>
                            <input type="date" name="end_date" class="form-control" value="{{ $data->end_date }}">
                            @error('end_date') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 card">
                                <div class="card-header p-0 mb-3">Image <span class="text-danger">*</span></div>
                                <div class="card-body p-0">
                                    <div class="w-100 product__thumb">
                                        <label for="icon"><img id="iconOutput" src="{{ asset($data->image) }}" /></label>
                                    </div>
                                    <input type="file" name="image" id="image" accept="image/*" onchange="loadIcon(event)" class="d-none">
                                    <script>
                                        let loadIcon = function(event) {
                                            let iconOutput = document.getElementById('iconOutput');
                                            iconOutput.src = URL.createObjectURL(event.target.files[0]);
                                            iconOutput.onload = function() {
                                                URL.revokeObjectURL(iconOutput.src) // free memory
                                            }
                                        };
                                    </script>
                                </div>
                                @error('image') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>
                            <div class="col-md-6 card">
                                <div class="card-header p-0 mb-3">Pdf <span class="text-danger">*</span></div>
                                <div class="card-body p-0">

                                    <div class="col-sm-9">
                                        <input class="form-control" type="file" name="pdf" id="pdf" value="{{ asset($data->pdf) }}">
                                   </div>

                                </div>
                                @error('pdf') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-danger">Update Catalogue</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
