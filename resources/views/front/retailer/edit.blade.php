@extends('layouts.app')

@section('content')
    <div class="col-sm-9">
        <div class="profile-card">
            <form class="createField" action="{{ route('front.invoice.update') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <h3>Invoice</h3>
                <img src="{{ asset('/uploads/invoice/' . $data[0]->image) }}" height="200px" width="150px">
                <div class="row">
                    <input type="hidden" name="id" value="{{ $data[0]->id }}">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="floating-label">Amount</label>
                            <input type="number" name="amount" class="form-control" placeholder="Amount"
                                value="{{ $data[0]->amount }}">
                        </div>
                        @error('amount')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="floating-label">Description</label>
                            <textarea type="text" name="description" style="height: 100px; resize:none;" class="form-control"
                                placeholder="Description">{{ $data[0]->description }}</textarea>
                        </div>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input type="hidden" name="latitude" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input type="hidden" name="longitude" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="floating-label">Image</label>
                            <input type="file" name="image" accept="image/jpeg, image/png" class="form-control">
                        </div>
                        @error('image')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script>
        navigator.geolocation.getCurrentPosition(positons);

        function positons(coords) {
            $('input[name="latitude"]').val(coords.coords.latitude);
            $('input[name="longitude"]').val(coords.coords.longitude);
        }
    </script>
@endsection
