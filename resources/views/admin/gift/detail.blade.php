@extends('admin.layouts.app')
@section('page', 'Gift detail')

@section('content')
<section>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <span class="badge bg-{{($data->status == 1) ? 'success' : 'danger'}}">{{($data->status == 1) ? 'Active' : 'Inactive'}}</span>
                    </div>

                    <div class="w-100 mb-3">
                        <img src="{{ asset($data->gift_image) }}" class="img-thumbnail" style="height: 200px">
                    </div>

                   
                    <h5 class="display-6">{{ $data->gift_title }}</h5>
					<p>User Limit : {{ $data->user_count }}</p>
                    
                    <p class="text-dark mb-2">{{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y g:i:s A')}}</p>

                    {{-- <div class="row">
                        <div class="col-md-5">
                            <h3> {{ $data->title }}</h3>

                        </div><br><br><br>
                        <div class="col-md-10">
                            <img src="{{ asset($data->image) }}" alt="" style="height: 50px" class="mr-4">
                        </div><br><br><br>
                        <div class="col-md-10">
                            <h3>{{ $data->tilte }}</h3>
                            <p class="small"><a href="{{ asset($data->pdf) }}" target="_blank"><i class="app-menu__icon fa fa-download"></i>Document</a></p>
                        </div>

                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
