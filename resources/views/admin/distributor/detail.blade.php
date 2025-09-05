@extends('admin.layouts.app')

@section('page', (($userType == 5 ) )? 'Distributor List' : 'Retailer List')

@section('content')
<section>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            {{-- {{ dd($data[0]) }} --}}
                        @if($data[0]->image)
                            <img src="{{asset($data[0]->image)}}" alt="" style="height: 50px" class="mr-4">
                        @else
                            <img src="{{asset('backend_asset/images/placeholder-image.jpg')}}" alt="" class="mr-4" style="width: 50px;height: 50px;border-radius: 50%;">
                        @endif
                        </div>
                        <div class="col-md-10">
                            <h3>{{ $data[0]->fname.' '.$data[0]->lname }}</h3>
                            <p><span class="text-muted">Gender :</span> {{ strtoupper($data[0]->gender) }}</p>
                            <p><span class="text-muted">Email :</span> {{ $data[0]->email }}</p>
                                <p><span class="text-muted">Contact Details:</span> {{ $data[0]->mobile }}</p>
                                <p><span class="text-muted">WhatsApp Details:</span> {{ $data[0]->whatsapp_no }}</p>
                                <p><span class="text-muted">Employee Id:</span> {{ $data[0]->employee_id }}</p>
                                    {{-- <p><span class="text-muted">User Type :</span>{{($item->type == 1) ? 'Distributor' : 'Dealer' :'Sales Person'}}</p> --}}
                                    <p><span class="text-muted">User Type :@if($data[0]->user_type==1) VP @elseif($data[0]->user_type==2) RSM @elseif($data[0]->user_type==3)ASM @elseif ($data[0]->user_type==4)RSE @elseif ($data[0]->user_type==5)Distributor @elseif ($data[0]->user_type==6)Retailer @endif</p>
                            <p class="small">  <p><span class="text-muted">Address : </span> {{ $data[0]->address }}</p>
                                <p><span class="text-muted">Landmark :</span> {{ $data[0]->landmark }}</p>
                                    <p><span class="text-muted">City :</span> {{ $data[0]->city }}</p>
                                        <p><span class="text-muted">State :</span> {{ $data[0]->state }}</p>
                                            <p><span class="text-muted">Pincode :</span> {{ $data[0]->pin }}</p>
                                                <p><span class="text-muted">Aadhar Number :</span> {{ $data[0]->aadhar_no }}</p>
                                                    <p><span class="text-muted">Pan Number :</span> {{ $data[0]->pan_no }}</p>
                                                    <p>Published<br/>{{date('d M Y', strtotime($data[0]->created_at))}}</p>

                        </div>
                    </div>
                    <a type="submit" href="{{ route('admin.distributor.index',$userType) }}" class="btn btn-sm btn-danger">Back</a>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
