@extends('admin.layouts.app')

@section('page', 'QRcode generate')

@section('content')
<section>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.scanandwin.store') }}" enctype="multipart/form-data">
                    @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="label-control">Qrcode details <span class="text-danger">*</span> </label>
                                    <input type="text" name="name" placeholder="" class="form-control" value="{{old('name')}}">
                                    @error('name') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
							<div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="label-control">Max time use <span class="text-danger">*</span> </label>
                                    <input type="text" name="max_time_of_use" placeholder="" class="form-control" value="{{old('max_time_of_use')}}">
                                    @error('name') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="label-control">Start date <span class="text-danger">*</span> </label>
                                    <input type="datetime-local" name="start_date" placeholder="" class="form-control" value="{{ old('start_date') ? old('start_date') : date('Y-m-d', strtotime('+1 day')) }}" min="{{ date('Y-m-d') }}">
                                    @error('start_date') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="label-control">End date <span class="text-danger">*</span> </label>
                                    <input type="datetime-local" name="end_date" placeholder="" class="form-control" value="{{ old('end_date') ? old('end_date') : date('Y-m-t') }}" min="{{ date('Y-m-d') }}">
                                    @error('end_date') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            {{--<div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="label-control"> Points <span class="text-danger">*</span> </label>
                                    <input type="number" name="points" placeholder="" class="form-control" value="{{old('points')}}">
                                    @error('points') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>--}}
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="label-control">No of qrcodes to generate <span class="text-danger">*</span> </label>
                                    <input type="number" name="generate_number" placeholder="" class="form-control" value="{{ old('generate_number') ? old('generate_number') : '100' }}" min="{{ date('Y-m-d') }}">
                                    @error('generate_number') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <p class="small text-danger">QRcodes code will be auto-generated</p>
                            </div>
                            {{-- <div class="card-body">
                                {!! QrCode::size(300)->generate('https://techvblogs.com/blog/generate-qr-code-laravel-9') !!}
                            </div> --}}
                            <div class="col-12">
                                {{-- <input type="hidden" name="type" value="1"> --}}
                                <input type="hidden" name="max_time_of_use" value="1">
                                <input type="hidden" name="max_time_one_can_use" value="1">
                                <button type="submit" class="btn btn-danger w-100">Tap here to generate QR</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
