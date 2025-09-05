@extends('layouts.app')

@section('content')
<div class="col-sm-12">
    <div class="profile-card">
        <h3>Invoice</h3>

        <a href="{{ route('front.invoice.create') }}" class="btn btn-danger">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus mr-2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>    
            Add new invoice
        </a>

        <table class="table mainCartTable mt-4 mb-3">
            <thead>
                <tr>
                    <th><span class="text-dark mx-3">#</span></th>
                    <th>Images</th>
                    <th>Amount</th>
                    <th>Description</th>
                    <th>Action</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($collection as $itemkey => $itemval)
                    <tr>
                        <td class="text-dark mx-3">{{ $itemkey + 1 }}</td>
                        <td><img src="{{ asset('uploads/invoice/' . $itemval->image) }}" width="120px" height="120px"></td>
                        <td>Rs.{{ number_format($itemval->amount) }}</td>
                        <td>{{ $itemval->description }}</td>
                        <td><a href="{{ route('front.invoice.edit', [$itemval->id]) }}">Edit</a></td>
                        <td><a onclick="return confirm('Are you sure to delete?')"
                                href="{{ route('front.invoice.delete', [$itemval->id]) }}">Delete</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%" class="text-center text-muted">No data yet!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
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
