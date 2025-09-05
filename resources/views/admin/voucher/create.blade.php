@extends('admin.layouts.app')

@section('page', 'Voucher generate')

@section('content')
<section>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.voucher.store') }}" enctype="multipart/form-data">
                    @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="label-control">Voucher details <span class="text-danger">*</span> </label>
                                    <input type="text" name="name" placeholder="" class="form-control" value="{{old('name')}}">
                                    @error('name') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="label-control">Start date <span class="text-danger">*</span> </label>
                                    <input type="date" name="start_date" placeholder="" class="form-control" value="{{ old('start_date') ? old('start_date') : date('Y-m-d', strtotime('+1 day')) }}" min="{{ date('Y-m-d') }}">
                                    @error('start_date') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="label-control">End date <span class="text-danger">*</span> </label>
                                    <input type="date" name="end_date" placeholder="" class="form-control" value="{{ old('end_date') ? old('end_date') : date('Y-m-t') }}" min="{{ date('Y-m-d') }}">
                                    @error('end_date') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="label-control">Discount Type <span class="text-danger">*</span> </label>
                                    <br>
                                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                        <input type="radio" class="btn-check" name="type" value="1" id="btnradio1" autocomplete="off" checked>
                                        <label class="btn btn-outline-danger" for="btnradio1">Percentage</label>
                                      
                                        <input type="radio" class="btn-check" name="type" value="2" id="btnradio2" autocomplete="off">
                                        <label class="btn btn-outline-danger" for="btnradio2">Flat / Amount</label>
                                      </div>
                                    @error('type') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="label-control">Discount Amount <span class="text-danger">*</span> </label>
                                    <input type="number" name="amount" placeholder="" class="form-control" value="{{old('amount')}}">
                                    @error('amount') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="label-control">No of vouchers to generate <span class="text-danger">*</span> </label>
                                    <input type="number" name="generate_number" placeholder="" class="form-control" value="{{ old('generate_number') ? old('generate_number') : '100' }}" min="{{ date('Y-m-d') }}">
                                    @error('generate_number') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <p class="small text-danger">Voucher code will be auto-generated</p>
                            </div>
                            <div class="col-12">
                                {{-- <input type="hidden" name="type" value="1"> --}}
                                <input type="hidden" name="is_coupon" value="0">
                                <input type="hidden" name="max_time_of_use" value="1">
                                <input type="hidden" name="max_time_one_can_use" value="1">
                                <button type="submit" class="btn btn-danger w-100">Tap here to generate vouchers</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection