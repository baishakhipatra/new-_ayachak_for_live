@extends('admin.layouts.app')

@section('page', 'Donations')

@section('content')

<div class="card">
    @if (session('success'))
        <div class="alert alert-success m-3">{{ session('success') }}</div>
    @endif

    <div class="card-body">
        <form action="{{ route('admin.donations.index') }}" method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-2">
                    <label class="form-label">From Date</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                </div>

    
                <div class="col-2">
                    <label class="form-label">To Date</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                </div>

     
                <div class="col-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" placeholder="Name, Mobile, Email, Address" value="{{ request('search') }}" class="form-control">
                </div>

    
                <div class="col-3">
                    <label class="form-label">Amount Range</label>
                    <select name="amount_range" class="form-select">
                        <option value="">-- Select Amount Range --</option>
                        <option value="0-100" {{ request('amount_range') == '0-100' ? 'selected' : '' }}>0 - 100</option>
                        <option value="100-500" {{ request('amount_range') == '100-500' ? 'selected' : '' }}>100 - 500</option>
                        <option value="500-1000" {{ request('amount_range') == '500-1000' ? 'selected' : '' }}>500 - 1000</option>
                        <option value="1000-5000" {{ request('amount_range') == '1000-5000' ? 'selected' : '' }}>1000 - 5000</option>
                        <option value="5000+" {{ request('amount_range') == '5000+' ? 'selected' : '' }}>More than 5000</option>
                    </select>
                </div>

           
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>

              
                <div class="col-auto">
                    <a href="{{ route('admin.donations.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>


    <div class="card-footer d-flex justify-content-end">
        <a href="{{ route('admin.donations.export', request()->all()) }}" class="btn btn-success">Export</a>
    </div>
</div>


<div class="card-body">
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($donations as $donation)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ ucwords($donation->full_name) }}</td>
                        <td>{{ $donation->email }}</td>
                        <td>{{ $donation->phone_number }}</td>
                        <td>{{ $donation->amount }}</td>
                        <td>{{ $donation->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.donations.show', $donation->id) }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-eye"></i> 
                            </a>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


@endsection