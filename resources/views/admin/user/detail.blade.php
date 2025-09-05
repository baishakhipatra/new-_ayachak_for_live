@extends('admin.layouts.app')

@section('page', 'User detail')

@section('content')
<section>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                        @if($data->image)
                            <img src="{{asset($data->image)}}" alt="" style="height: 50px" class="mr-4">
                        @else
                            <img src="{{asset('backend_asset/images/placeholder-image.jpg')}}" alt="" class="mr-4" style="width: 50px;height: 50px;border-radius: 50%;">
                        @endif
                        </div>
                        <div class="col-md-10">
                            <h3>{{ $data->fname.' '.$data->lname }}</h3>
                            <p><span class="text-muted">Gender :</span> {{ strtoupper($data->gender) }}</p>
                            <p><span class="text-muted">Email :</span> {{ $data->email }}</p>
                                <p><span class="text-muted">Contact Details:</span> {{ $data->mobile }}</p>
                                <p><span class="text-muted">WhatsApp Details:</span> {{ $data->whatsapp_no }}</p>
                                <p><span class="text-muted">Employee Id:</span> {{ $data->employee_id }}</p>
                                    {{-- <p><span class="text-muted">User Type :</span>{{($item->type == 1) ? 'Distributor' : 'Dealer' :'Sales Person'}}</p> --}}
                                    <p><span class="text-muted">User Type :@if($data->user_type==1) VP @elseif($data->user_type==2) RSM @elseif($data->user_type==3)ASM @elseif ($data->user_type==4)RSE @elseif ($data->user_type==5)Distributor @elseif ($data->user_type==6)Retailer @endif</p>
                            <p class="small">  <p><span class="text-muted">Address : </span> {{ $data->address }}</p>
                                <p><span class="text-muted">Landmark :</span> {{ $data->landmark }}</p>
                                    <p><span class="text-muted">City :</span> {{ $data->city }}</p>
                                        <p><span class="text-muted">State :</span> {{ $data->state }}</p>
                                            <p><span class="text-muted">Pincode :</span> {{ $data->pin }}</p>
                                                <p><span class="text-muted">Aadhar Number :</span> {{ $data->aadhar_no }}</p>
                                                    <p><span class="text-muted">Pan Number :</span> {{ $data->pan_no }}</p>
                                                    <p>Published<br/>{{date('d M Y', strtotime($data->created_at))}}</p>

                        </div>
                    </div>
                    <a type="submit" href="{{ route('admin.user.index') }}" class="btn btn-sm btn-danger">Back</a>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
