@extends('admin.layouts.app')

@section('page', 'Target detail')

@section('content')
<section>
    <div class="row">
        <div class="col-sm-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Title : {{ $data->title }}</h3>
                            <p class="text-muted"> Collection : {{ $data->collection->name }}</p>
                            <p class="small">Person : {{$data->user->name}}</p>
                            <p class="small">Designation : @if($data->user_type==1) VP @elseif($data->user->user_type==2) RSM @elseif($data->user->user_type==3)ASM @elseif ($data->user->user_type==4)RSE @elseif ($data->user->user_type==5)Distributor @elseif ($data->user->user_type==6)Retailer @endif</p>
                            <p class="small">Target : {{ $data->amount }}</p>
                            <p class="small">Year : {{ $data->year_from }} - {{ $data->year_to }}</p>

                            <p class="small">Remarks: {{ $data->remarks }}</p>
                            <hr>
                        </div>
                    </div>
                    <a type="submit" href="{{ route('admin.target.index') }}" class="btn btn-sm btn-danger">Back</a>
                    <hr>


                </div>
            </div>
        </div>


    </div>
</section>
@endsection
