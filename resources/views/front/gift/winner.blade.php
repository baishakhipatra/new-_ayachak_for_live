@extends('layouts.app')
@section('page', 'Winners')
@section('content')
<style>
	.badge svg {
		width: 16px;
		height: 16px;
		margin-right: 6px;
	}
	.badge.bg-success {
		background: #d1e7dd !important;
		border: 1px solid #a3cfbb;
		color: #0a3622;
	}
	.toggle-row {
		border: none;
		background: transparent;
		position: absolute;
		top: 50%;
		right: 5px;
		transform: translateY(-50%);
		display: none;
	}
	.gift-table td {
		position: relative;
	}
	
	@media(max-width: 575px) {
		.gift-table tr th.check-column {
			display: table-cell;
			width: 70px;
			text-align: center;
		}
		.gift-table tr:not(.inline-edit-row):not(.no-items) td.column-primary~td:not(.check-column) {
			display: none;
		}
		.gift-table tr:not(.inline-edit-row):not(.no-items) th.column-primary~th:not(.check-column) {
			display: none;
		}
		.gift-table {
			margin-top: 50px;
		}
		.gift-table tr:not(.inline-edit-row):not(.no-items) td:not(.check-column) {
			position: relative;
			clear: both;
			width: auto!important;
		}
		.gift-table .is-expanded td:not(.hidden) {
			display: block!important;
			overflow: hidden;
		}
		.gift-table td.column-primary {
			padding-right: 50px;
		}
		.gift-table tr:not(.inline-edit-row):not(.no-items) td.column-primary~td:not(.check-column) {
			padding: 12px 12px 12px 35%;
		}
		.gift-table tr:not(.inline-edit-row):not(.no-items) td:not(.column-primary)::before {
			position: absolute;
			left: 10px;
			display: block;
			overflow: hidden;
			width: 32%;
			content: attr(data-colname);
			white-space: nowrap;
			text-overflow: ellipsis;
			font-weight: bold;
		}
		.toggle-row {
			display: block;
		}
	}
</style>
<section>
    <div class="row">
        <div class="container">
            
                                       
                                        
                          
                                       
                                   
                           
                            
                      <div class="cms_context">
                       
                   
                    <table class="gift-table table table-bordered table-striped table-hover" id="example5">
                        <thead>
                            <tr>
                                <th class="check-column">Sl No.</th>
                                <th class="column-primary">Name</th>
                                <th>Mobile number </th>
								<th>QRCODE</th>
								<th>Gift</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $index => $item)
                                @php
                                $qr=DB::table('user_txn_histories')->where('customer_id',$item->id)->first();
							$gift= DB::table('gifts')->where('id',$item->gift_id)->first();
                                    
                                        
                                   
                                       
                               
                                @endphp
                                <tr>
                                    <th class="check-column">{{ $index+1 }}</th>
                                    <td class="column-primary">
                                        {{$item->name}}
                                        
										<button class="toggle-row">▼</button>
                                    </td>
                                    <td data-colname="Mobile number">{{getTruncatedCCNumber($item->phone)}}</td>
									<td data-colname="QRCODE">{{$qr->qrcode}}</td>
									<td data-colname="Gift">{{$gift->gift_title}}

                                    <td data-colname="Status"><span class="badge bg-{{($item->is_gifted== 1) ? 'success' : 'danger'}}"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="512" height="512" x="0" y="0" viewBox="0 0 511.883 511.883" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path fill="#fed843" d="m384.906 193.554 7.559 15.117c10.759 21.546.39 48.153-27.466 55.898-.352.117-38.936 10.474-50.654-24.624l-11.294-33.926 32.139-32.153-79.307-118.945-79.307 118.945 32.139 32.153-11.294 33.926c-12.925 38.725-66.118 29.608-78.662 2.3-4.951-10.752-4.746-22.983.542-33.574l7.544-15.103L0 148.29l60.883 233.672 30 30h330l30-30 61-233.657z" data-original="#fed843" class=""></path><path fill="#fabe2c" d="m450.883 381.962 61-233.657-126.977 45.249 7.559 15.117c10.759 21.546.39 48.153-27.466 55.898-.352.117-38.936 10.474-50.654-24.624l-11.294-33.926 32.139-32.153-79.307-118.945v357.041h165z" data-original="#fabe2c" class=""></path><path fill="#fabe2c" d="M255.883 381.962h-195v75h390v-75z" data-original="#fabe2c" class=""></path><path fill="#ff9100" d="M255.883 381.962h195v75h-195z" data-original="#ff9100"></path><path fill="#fabe2c" d="m180.826 381.949 74.988-74.989 74.99 74.989-74.99 74.989z" data-original="#fabe2c" class=""></path><path fill="#ff9100" d="M255.883 306.962v150l75-75z" data-original="#ff9100"></path></g></svg> {{($item->is_gifted== 1) ? 'Win' : 'Not win'}}</span></td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="100%" class="small text-muted">No data found</td></tr>
                                    @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end">
                        {{ $data->appends($_GET)->links() }}
				</div>
			</div>
				</div>
                   
    </div>
</section>
@endsection
@section('script')
<script>
    function htmlToCSV() {
        var data = [];
        var rows = document.querySelectorAll("#example5 tbody tr");
        @php
            if (!request()->input('page')) {
                $page = '1';
            } else {
                $page = request()->input('page');
            }
        @endphp

        var page = "{{ $page }}";

        data.push("SRNO.,Title,Date,Status");

        for (var i = 0; i < rows.length; i++) {
            var row = [],
                cols = rows[i].querySelectorAll("td");

            for (var j = 0; j < cols.length; j++) {
                var text = cols[j].innerText.split(' ');
                var new_text = text.join('-');
                if (j == 2)
                    var comtext = new_text.replace(/\n/g, "-");
                else
                    var comtext = new_text.replace(/\n/g, ";");
                row.push(comtext);
            }
            data.push(row.join(","));
        }

        downloadCSVFile(data.join("\n"), 'Scheme.csv');
    }

    function downloadCSVFile(csv, filename) {
        var csv_file, download_link;

        csv_file = new Blob([csv], {
            type: "text/csv"
        });

        download_link = document.createElement("a");

        download_link.download = filename;

        download_link.href = window.URL.createObjectURL(csv_file);

        download_link.style.display = "none";

        document.body.appendChild(download_link);

        download_link.click();
    }


</script>
<script>
	$('.toggle-row').click(function() {
		$(this).parent().parent().toggleClass('is-expanded');
	});
</script>	
 @if (request()->input('export_all') == true)
                <script>
                    htmlToCSV();
                    window.location.href = "{{ route('admin.offer.index') }}";
                </script>
@endif
@endsection
