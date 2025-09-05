@extends('admin.layouts.app')

@section('page', 'View Donations')

@section('content')
<div class="container">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            {{-- <h5 class="mb-0">Donation Details</h5> --}}
            <a href="{{ url()->previous() }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover align-middle">
                <tbody>
                    <tr>
                        <th width="30%">Full Name</th>
                        <td>{{ ucwords($donations->full_name) }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $donations->email }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $donations->phone_number }}</td>
                    </tr>
                    <tr>
                        <th>PAN Number</th>
                        <td>{{ $donations->pan_number ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{ ucwords($donations->address) }}</td>
                    </tr>
                    <tr>
                        <th>City / Village</th>
                        <td>{{ ucwords($donations->city_village) }}</td>
                    </tr>
                    <tr>
                        <th>District</th>
                        <td>{{ ucwords($donations->district) }}</td>
                    </tr>
                    <tr>
                        <th>State</th>
                        <td>{{ ucwords($donations->state) }}</td>
                    </tr>
                    <tr>
                        <th>Zipcode</th>
                        <td>{{ $donations->zipcode }}</td>
                    </tr>
                    <tr>
                        <th>Country</th>
                        <td>{{ ucwords($donations->country) }}</td>
                    </tr>
                    <tr>
                        <th>Amount</th>
                        <td><strong>₹ {{ number_format($donations->amount, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>{{ $donations->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection