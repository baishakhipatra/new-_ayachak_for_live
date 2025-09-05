@extends('layouts.app')

@section('page', 'Report')

@section('content')
<div class="col-sm-12">
    <div class="profile-card">
        <h3>Target</h3>

        <section class="store_listing">
            <div class="container">
                <div class="row">
                    @forelse ($data as $target)
                    <div class="col-12 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h5 class="badge badge-danger" style="font-size: 25px;border-radius: 0;">{{$target->collection->name}}</h5>

                                    <div class="year-container">
                                        Year : {{$target->year_from . ' - ' . $target->year_to}}
                                    </div>
                                </div>

                                <h5 class="display-4">Rs {{number_format($target->amount)}}</h5>

                                <p class="small">{{$target->title}}</p>

                                {{-- <p class="small">Current status : Ongoing</p> --}}
                                {{-- <a href="" class="btn btn-sm btn-danger">Remark</a> --}}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 mb-3">
                        {{-- <div class="card">
                            <div class="card-body"> --}}
                                <h5>No targets found for this account !</h5>
                                <p class="small">Did you know, you can always talk to your manager & get some targets assigned to your account ?</p>
                                <a href="{{route('front.dashboard.index')}}" class="btn btn-sm btn-danger">Go to Dashboard</a>
                            {{-- </div>
                        </div> --}}
                    </div>
                    @endforelse

                </div>
            </div>
        </section>
    </div>
</div>
@endsection
