@extends('admin.layouts.app')

@section('page', 'QRcode detail')

@section('content')
<section>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">    
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="text-muted">{{ $data->name }}</h3>
                            {{-- <h6>{{ $data->name }}</h6> --}}
                        </div>
                        <div class="col-md-4 text-end">
                            @if ($data->end_date < \Carbon\Carbon::now() )
                            <h3 class="text-danger mt-3 fw-bold">EXPIRED</h3>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <p class="small text-muted mt-4 mb-2">Details</p>
                            <table class="">
                                <tr>
                                    <td class="text-muted">No of qrcodes: </td>
                                    <td>{{count($coupons)}}</td>
                                </tr>
                                
                                {{-- <tr>
                                    <td class="text-muted">Max time usage : </td>
                                    <td>{{$data->max_time_of_use}}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Max time usage for single user :  </td>
                                    <td>{{$data->max_time_one_can_use}}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">No of usage : </td>
                                    <td>{{$data->no_of_usage}}</td>
                                </tr> --}}
                                <tr>
                                    <td class="text-muted">Start date: </td>
                                    <td>{{ date('j M Y', strtotime($data->start_date)) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">End date: </td>
                                    <td>{{ date('j M Y', strtotime($data->end_date)) }}</td>
                                </tr>
                            </table>

                            <hr>

                            <p class="small text-muted mt-4 mb-2">QRcodes</p>
                           {{-- <div class="col-auto">
                                <a type="button" id="basic" class="btn btn-outline-danger btn-sm">Download pdf</a>
                            </div> --}}
							
							 <div class="col-md-12 text-end">
                    <form class="row align-items-end justify-content-end" action="">
						
                        <div class="col-auto">
                            <input type="search" name="keyword" id="keyword" class="form-control form-control-sm" placeholder="Search by QRcode" value="{{request()->input('keyword')}}" autocomplete="off">
                        </div>
                        <div class="col-auto">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Filter
                                </button>

                                <a href="{{ url()->current() }}" class="btn btn-sm btn-light" data-bs-toggle="tooltip" title="Clear Filter">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                </a>

                                <a type="button" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"  id="basic">
                                   Download pdf
                                </a>
								<a href="{{ route('admin.scanandwin.csv.export',['slug'=>$data->slug,'keyword'=>$request->keyword]) }}" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="">
                                    Download CSV
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                            <table class="table table-sm print-code">
                                <tr>
                                    <th>#SR</th>
                                    
                                    <th>QRcodes</th>
                                   
                                    
                                    <th>Status</th>
                                </tr>
                                @forelse ($coupons as $couponKey => $coupon)
                                <tr>
                                    <td>{{$couponKey+1}}</td>
									{{--<td>{{$coupon->code}}--}}
									<td><div style="width: 120px;" class="text-center">
										{{--<img src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text=https://onninternational.com/scanandwin?code={{$coupon->code}}&height=6&textsize=10&scale=6&includetext" alt="" style="height: 105px;width:105px">--}}
									   <p class="text-center my-3">{{$coupon->code}}</p></div>
									 <div class="row__action">
                                             <a href="{{ route('admin.scanandwin.show', $coupon->id) }}">View</a> 
                                            <a href="{{ route('admin.scanandwin.edit', $coupon->id) }}">Edit</a>
                                            <a href="{{ route('admin.scanandwin.status', $coupon->id) }}">{{($coupon->status == 1) ? 'Active' : 'Inactive'}}</a>
                                           
                                        </div>
									</td>
                                   {{-- <td><img src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text=https://onninternational.com/scanandwin?code={{$coupon->code}}&height=6&textsize=10&scale=6&includetext" alt="" style="height: 105px;width:105px">
                                       
                                    </td>--}}
                                    
                                   
                                    
                                    <td><span class="badge bg-{{($coupon->status == 1) ? 'success' : 'danger'}}">{{($coupon->status == 1) ? 'Active' : 'Inactive'}}</span></td>
                                </tr>
                                @empty
                                <tr><td colspan="100%" class="small text-muted">No data found</td></tr>
                                @endforelse
                            </table>
                           <div class="d-flex justify-content-end">
								{{ $coupons->appends($_GET)->links() }}
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
<script src="{{ asset('backend_asset/js/printThis.js') }}"></script>
<script>
 $('#basic').on("click", function () {
      $('.print-code').printThis();
    });
</script>
@endsection
