@extends('front.layout.app')
@section('page-title', 'Donation')
@section('content')

<section class="main">
    <div class="container">
        <div class="profile-wrapper">
            <div class="row">
                <div class="col-lg-3 mb-4">
                   @include('front/sidebar_profile')
                </div>
                <div class="col-lg-9">
                    <div class="profile-right">
                        <h2>Donation History</h2>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="profile-order-place">
                                    @if($donations->isEmpty())
                                        <div class="d-flex flex-column justify-content-center align-items-center text-center py-5">
                                            <img src="{{ asset('assets/images/empty-box.png') }}" 
                                                alt="No Donations" class="mb-3" style="width:120px;">
                                            <p class="text-muted fs-5">You haven’t made any donations yet.</p>
                                        </div>
                                    @else
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($donations as $index => $donation)
                                                    <tr>
                                                        <td>{{ $index+1 }}</td>
                                                        <td>
                                                            <strong>₹{{ number_format($donation->amount, 2) }}</strong>
                                                        </td>
                                                        <td>
                                                            @if($donation->status == 'success')
                                                                <span class="label complete">Success</span>
                                                            @elseif($donation->status == 'pending')
                                                                <span class="label complete">Pending</span>
                                                            @else
                                                                <span class="label complete">Failed</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $donation->created_at->format('d M, Y h:i A') }}</td>
                                                        <td><a href="{{route('front.donation.show', $donation->id)}}" class="view"><i class="fa fa-eye" aria-hidden="true"></a></i></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('script')

<script>
    function capitalizeAllWords(str) {
        return str.replace(/\b\w/g, function(char) {
            return char.toUpperCase();
        });
    }

</script>
@endsection
