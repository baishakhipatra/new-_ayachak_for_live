@extends('front.layout.app')
@section('page-title', 'Donation')
@section('content')

<section class="main py-5">
    <div class="container">
        <div class="profile-wrapper">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-lg-3 mb-4">
                    @include('front/sidebar_profile')
                </div>

                <!-- Content -->
                <div class="col-lg-9">
                    <div class="profile-right card shadow-sm border-0 p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="mb-0">My Donations</h2>
                        </div>

                        @if($donations->isEmpty())
                            <div class="d-flex flex-column justify-content-center align-items-center text-center py-5">
                                <img src="{{ asset('assets/images/empty-box.png') }}" 
                                     alt="No Donations" class="mb-3" style="width:120px;">
                                <p class="text-muted fs-5">You haven’t made any donations yet.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
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
                                                        <span class="badge bg-success">Success</span>
                                                    @elseif($donation->status == 'pending')
                                                        <span class="badge bg-warning text-dark">Pending</span>
                                                    @else
                                                        <span class="badge bg-danger">Failed</span>
                                                    @endif
                                                </td>
                                                <td>{{ $donation->created_at->format('d M, Y h:i A') }}</td>
                                                <td><i style="cursor: pointer;" class="fa fa-eye view-btn" data-id="{{ $donation->id }}"></i></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="modal fade" id="donationModal" tabindex="-1" aria-labelledby="donationModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                        <div class="modal-header bg-secondary text-white">
                                            <h5 class="modal-title" id="donationModalLabel">Donation Details</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="row"><strong class="col-sm-6">Name:</strong> <span class="col-sm-6" id="modal-name"></span></p>
                                            <p class="row"><strong class="col-sm-6">Email:</strong> <span class="col-sm-6"id="modal-email"></span></p>
                                            <p class="row"><strong class="col-sm-6">Phone:</strong> <span class="col-sm-6" id="modal-phone"></span></p>
                                            <p class="row"><strong class="col-sm-6">PAN:</strong> <span class="col-sm-6" id="modal-pan"></span></p>
                                            <p class="row"><strong class="col-sm-6">Address:</strong> <span class="col-sm-6" id="modal-address"></span></p>
                                            <p class="row"><strong class="col-sm-6">Amount:</strong> <span class="col-sm-6" id="modal-amount"></span></p>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



@endsection
@section('script')

<script>
    $('.view-btn').click(function() {
        var id = $(this).data('id');

        $.ajax({
            url: "{{ url('/') }}/show/"+id,
            method: 'GET',
            success: function(data) {
                $('#modal-name').text(capitalizeAllWords(data.full_name));
                $('#modal-email').text(data.email);
                $('#modal-phone').text(data.phone_number);
                $('#modal-pan').text(data.pan_number);
                $('#modal-address').text(capitalizeAllWords(data.address)+', '+capitalizeAllWords(data.city_village)+', '+capitalizeAllWords(data.district)+', '+capitalizeAllWords(data.state)+', '+data.zipcode+', '+data.country);
                $('#modal-amount').text(data.amount);
                $('#donationModal').modal('show');
            },
            error: function() {
                alert('Error loading donation details.');
            }
        });
    });
    function capitalizeAllWords(str) {
        return str.replace(/\b\w/g, function(char) {
            return char.toUpperCase();
        });
    }

</script>
@endsection
