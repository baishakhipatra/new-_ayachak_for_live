@extends('admin.layouts.app')

@section('page', 'Make Donations')

@section('content')

<div class="card p-4">
    <div class="card-footer d-flex justify-content-end">
        <a href="{{ route('admin.donations.index') }}" class="btn btn-danger">
        <i class="ri-arrow-left-line"></i> Back
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.donations.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="full_name" placeholder="Full Name" class="form-control" value="{{ old('full_name') }}">
                    @error('full_name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" placeholder="Email Address" class="form-control" value="{{ old('email') }}">
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                    <input type="text" name="phone_number" placeholder="Phone Number" class="form-control" value="{{ old('phone_number') }}">
                    @error('phone_number')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">PAN Number</label>
                    <input type="text" name="pan_number" placeholder="Enter Your PAN Number" class="form-control" value="{{ old('pan_number') }}">
                    @error('pan_number')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label">Address <span class="text-danger">*</span></label>
                    <textarea name="address" placeholder="Enter Your Address" class="form-control" rows="3">{{ old('address') }}</textarea>
                    @error('address')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">City/Village <span class="text-danger">*</span></label>
                    <input type="text" name="city_village" placeholder="Enter City/Village" class="form-control" value="{{ old('city_village') }}">
                    @error('city_village')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">District <span class="text-danger">*</span></label>
                    <input type="text" name="district" placeholder="Enter District" class="form-control" value="{{ old('district') }}">
                    @error('district')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">State <span class="text-danger">*</span></label>
                    <input type="text" name="state" placeholder="Enter State" class="form-control" value="{{ old('state') }}">
                    @error('state')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Zipcode <span class="text-danger">*</span></label>
                    <input type="text" name="zipcode" placeholder="Enter Zipcode" class="form-control" value="{{ old('zipcode') }}">
                    @error('zipcode')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Since you want country to be India only, you can make it readonly --}}
                <div class="col-md-6">
                    <label class="form-label">Country <span class="text-danger">*</span></label>
                    <input type="text" name="country" class="form-control" value="India" readonly>
                    @error('country')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Amount <span class="text-danger">*</span></label>
                    <input type="number" name="amount" placeholder="Enter Amount" class="form-control" value="{{ old('amount') }}">
                    @error('amount')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-12">
                    <button type="submit" class="btn btn-success">Donate Now</button>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection