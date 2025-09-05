@extends('admin.layouts.app')

@section('page', 'Scanandwin Customers')

@section('content')

<section>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">    
                <div class="card-body">
					<div class="col-md-12 text-end">
                    <form class="row align-items-end justify-content-end" action="{{ route('admin.customers.index') }}">
						<div class="col-auto">
                                <label for="date_from" class="text-muted small">Date from</label>
                                <input type="date" name="date_from" id="date_from" class="form-control form-control-sm" aria-label="Default select example" value="{{request()->input('date_from') ?? date('Y-m-01') }}">
                            </div>
                            <div class="col-auto">
                                <label for="date_to" class="text-muted small">Date to</label>
                                <input type="date" name="date_to" id="date_to" class="form-control form-control-sm" aria-label="Default select example" value="{{request()->input('date_to') ?? date('Y-m-d') }}">
                            </div>
                        <div class="col-auto">
                            <label for="ase" class="small text-muted">Winning Status</label>
                            <select class="form-select form-select-sm select2" id="ase" name="status">
								<option value="" selected>Select</option>
                                    <option value="1" {{ (request()->input('status') == 1) ? 'selected' : '' }}>Winner</option>
                                  <option value="0" {{ (request()->input('status') == 0) ? 'selected' : '' }}>Looser</option>
                            </select>
                        </div>
                       
                        <div class="col-auto">
                            <input type="search" name="keyword" id="keyword" class="form-control form-control-sm" placeholder="Search by qrcode/mobile" value="{{request()->input('keyword')}}" autocomplete="off">
                        </div>
                        <div class="col-auto">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Filter
                                </button>

                                <a href="{{ url()->current() }}" class="btn btn-sm btn-light" data-bs-toggle="tooltip" title="Clear Filter">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                </a>

                                <a href="{{route('admin.customers.csv.export',['date_from'=>$request->date_from,'date_to'=>$request->date_to,'keyword'=>$request->keyword,'status'=>$request->status])}}" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Export data in CSV">
                                    EXPORT <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                </a>

                                {{--<a href="javascript: void(0)" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Export data in CSV" onclick="ajaxExcelExport()" id="csvEXP">
                                    <iconify-icon icon="material-symbols:download"></iconify-icon> EXPORT
                                </a>--}}
                            </div>
                        </div>
                    </form>
                </div>
                   
					
                    <table class="table">
                        <thead>
                            <tr>
								<th>Sr No</th>
                                <th>Name</th>
                                <th>Mobile</th>
								<th>IP with address</th>
								<th>Code</th>
								<th>QR</th>
                                <th>Win status</th>
                                <th>Gift</th>
                                <th>Date</th>
								<th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $index => $item)
							
							@php
							   $qr=DB::table('user_txn_histories')->where('customer_id', $item->id)->first();
							   $giftDetails=DB::table('gifts')->where('id', $item->gift_id)->first();
						      if(!empty($item->ip)){
								$getLocation = getLocation($item->ip);
								$city = $getLocation->city ?? '';
								$region = $getLocation->region ?? '';
                  				$postal = $getLocation->postal ?? '';
							    $org = $getLocation->org ?? '';
							 }
							@endphp
							
                            <tr>
								<td>{{ $item->order_sequence_int }}</td>
                             <td>
                                {{$item->name}}
                                
                                </td>
                                <td>{{$item->phone}}</td>
								<td>{{$item->ip ?? ''}}
									<p>{{$city ?? ''}} <br>{{$region ?? ''}}<br>{{$postal ?? ''}}<br>{{$org ?? ''}}</p>
								</td>
								<td>{{$item->qrcode ?? ''}}</td>
								<td>@if(!empty($item->qrcode))<img src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text={{$qr->qrcode}}&height=6&textsize=10&scale=6&includetext" alt="" style="height: 105px;width:105px">@endif</td>
                               <td><span class="badge bg-{{($item->is_gifted == 1) ? 'success' : 'danger'}}">{{($item->is_gifted == 1) ? 'Win' : 'Not win'}}</span></td>
                                <td>{{$giftDetails->gift_title ?? ''}}
									@if(!empty($giftDetails->gift_image))
									<p><img src="{{asset($giftDetails->gift_image)}}" height="100" width="100"></p>
									@endif
								</td>
                               
                                <td>Published<br/>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y g:i:s A')}}</td>
                               {{-- @if($item->is_gifted == 1)
								<td><a href="{{route('admin.customers.dispatch',$item->id)}}" type="button" class="btn btn-danger btn-sm">Dispatch</a></td>
								@endif--}}
                            </tr>
                            @empty
                            <tr><td colspan="100%" class="small text-muted">No data found</td></tr>
                            @endforelse
                        </tbody>
                    </table>  
					<div class="row">
            <div class="col-12">
                <div class="pagination justify-content-end">
                    {{$data->appends($_GET)->links()}}
                </div>
            </div>
        </div>
                </div>
            </div>
        </div>

	</div>
</section>

<div id="expTab" style="display: none"></div>
@endsection
@section('script')
<script>
    //$('input[name="ip"]').on('keyup', (event) => {
	//$(document).ready(function(){
	$('input[name=ip]').each(function() {
          var value = $('input[name="ip"]').val();
          console.log(value);
          $.ajax({
              url: 'https://ipinfo.io/'+value+'/geo',
              method: 'GET',
                success:function(html){
                    let address = html.city  +  "<br>" + html.region + "<br>"  + html.postal + "<br>" + html.org;
					 console.log(address);
					document.getElementById('address').innerHTML = address;
                   // $('p[id=address]').val(address);
                  

                }
          });
      });


      function ajaxExcelExport() {
        $.ajax({
            url: 'csv/export/ajax/',
            method: 'GET',
            data: {
                'zsm': $('select[name="zsm"]').val(),
                'rsm': $('select[name="rsm"]').val(),
                'sm': $('select[name="sm"]').val(),
                'asm': $('select[name="asm"]').val(),
                'ase': $('select[name="ase"]').val(),
                'month': $('input[name="month"]').val(),
                'checkbox': $('input[name="checkbox"]').val(),
            },
            beforeSend: function() {
                $('#csvEXP').html('Please wait').attr('disabled', true);
            },
            success: function(result) {
                if (result.status === 200) {
                    $('#expTab').html(result.data);

                    // var url = 'data:application/vnd.ms-excel,' + encodeURIComponent($('#expTab').html())
                    // location.href = url

                    var myBlob =  new Blob( [$('#expTab').html()] , {type:'application/vnd.ms-excel'});
                    var url = window.URL.createObjectURL(myBlob);
                    var a = document.createElement("a");
                    document.body.appendChild(a);
                    a.href = url;
                    a.download = "ONNINTERNATIONAL-Scanandwin-Customers-{{date('Y-m-d')}}.xls";
                    a.click();
                    //adding some delay in removing the dynamically created link solved the problem in FireFox
                    setTimeout(function() {window.URL.revokeObjectURL(url);},0);

                    $('#csvEXP').html('<iconify-icon icon="material-symbols:download"></iconify-icon> Downloading...').attr('disabled', false);
                    setTimeout(()=> {
                        $('#csvEXP').html('<iconify-icon icon="material-symbols:download"></iconify-icon> EXPORT').attr('disabled', false);
                    }, 1500);
                    return false
                }
                $('#csvEXP').html('<iconify-icon icon="material-symbols:download"></iconify-icon> EXPORT').attr('disabled', false);
            }
        });
    }
</script>
@endsection