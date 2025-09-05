@extends('admin.layouts.app')

@section('page', 'SYNC all SKUs with Unicommerce')

@section('content')
<section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <p class="mb-0">{{$data->skuCount}} SKUs found</p>
                        <a class="btn btn-light border btn-sm" href="{{ route('admin.product.sku_list.sync.all.report') }}">
                            View Sync report

                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-flag"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" y1="22" x2="4" y2="15"></line></svg>
                        </a>
                    </div>

                    <div class="sku-sync-contents my-4">
                        <div class="d-flex">
                            <div class="onn">
                                <img src="{{ asset('img/onn.png') }}" alt="">
                            </div>
                            <div class="sync">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-cw sync-svg"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                            </div>
                            <div class="uni">
                                <img src="{{ asset('img/uni.png') }}" alt="">
                            </div>
                        </div>
                    </div>

                    <h5 class="mb-2">Do you want to sync all SKUs with Unicommerce ?</h5>

                    <p class="small text-muted mb-2">Please Note:</p>

                    <ul class="">
                        <li><p class="small text-muted mb-1">You are trying to fetch inventory for all {{$data->skuCount}} SKUs from Unicommerce to ONNINTERNATIONAL. This process is going to take time.</p></li>
                        <li><p class="small text-muted mb-1">Once sync started, keep this tab open till it finishes.</p></li>
                        <li><p class="small text-muted mb-1">You will be shared a detailed report after the sync finishes.</p></li>
                        <li><p class="small text-muted mb-1">Unicommerce does not support sharing inventory snapshot, less than 24 hours. <a href="https://documentation.unicommerce.com/docs/inventory-snapshot.html#request-payload-details" target="_blank">Refer to this link for more information</a></p></li>
                    </ul>

                    <div id="syncReport" class="my-4"></div>

                    <a href="{{ route('admin.product.sku_list') }}" class="btn btn-lg btn-light border">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>
                        Go back to Product SKUs
                    </a>

                    <form action="{{ route('admin.product.sku_list.sync.all.start') }}" class="d-inline-block" id="syncStart" method="GET">
                        <button type="submit" class="btn btn-lg btn-danger">
                            Start Syncing
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
    <script>
        $('#syncStart').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url : $(this).attr('action'),
                method : $(this).attr('method'),
                data : {_token : '{{csrf_token()}}',email : $('input[name="subsEmail"]').val()},
                beforeSend : function() {
                    // 1
                    toastFire('info', 'SYNC STARTED');

                    // 2
                    const beforeSend = `
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Sync started. <strong>DO NOT close this tab!</strong></h4>
                        <p>SKUs from ONNINTERNATIONAL are being synched with Unicommerce. This process might take 3 to 4 minutes (Depending on the server load). Please stay put.</p>
                        <hr>
                        <p>Once sync finishes, you will be notified with a detailed report.</p>
                    </div>
                    `;
                    $('#syncReport').html(beforeSend);

                    /*
                    setTimeout(() => {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            showCloseButton: true,
                            timerProgressBar: false,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })

                        Toast.fire({
                            icon: 'warning',
                            title: 'DO NOT CLOSE THIS TAB',
                            // text: body
                        })
                    }, 5000);
                    */

                    // 3
                    $('.sync-svg').addClass('start');

                    // 4
                    $('#syncStart button').prop('disabled', true);
                },
                success : function(result) {

                    if (result.status == 200) {
                        const content = `
                        <div class="alert alert-success" role="alert">
                            <h4 class="alert-heading">Sync complete!</h4>
                            <p>${result.description}</p>
                            <hr>
                            <a href="${result.report}" class="mb-0" target="_blank">Click here to view detailed report</a>
                        </div>
                        `;

                        $('#syncReport').html(content);

                        $('.sync-svg').removeClass('start');
                        $('#syncStart button').prop('disabled', false);
                        swal.close();
                    } else {
                        toastFire('failure', 'Something happened');
                    }

                    // result.resp == 200 ? $icon = '<i class="fas fa-check"></i> ' : $icon = '<i class="fas fa-info-circle"></i> ';
                    // $('#joinUsMailResp').html($icon+result.message);
                    // $('button').attr('disabled', false);
                }
            });
        });
    </script>
@endsection