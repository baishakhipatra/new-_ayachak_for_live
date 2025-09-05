@extends('layouts.app')

@section('page', 'Directory')

@section('content')
<div class="col-sm-12">
    <h5 class="mb-3">Distributor MOM</h5>
    <section class="store_listing">
            <div class="row">
            @forelse($data as $categoryProductKey => $categoryProductValue)
                <div class="col-lg-4 col-12">
                    <div class="store_card card">
                        <div class="store_card_body">
                            <a href="javascript: void(0)" data-toggle="modal" data-target="#momModal_{{$categoryProductKey}}" class="product__single" onclick="document.getElementById('distributor_name_{{$categoryProductKey}}').value = '{{$categoryProductValue->distributor_name}}';">
                                <figcaption>
                                    <h5>{{$categoryProductValue->distributor_name}}</h5>
                                    <div class="storLoction">
                                        <div class="storLoction_icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#34acc0" stroke="#fff" stroke-width="0" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" stroke-width="2" cy="10" r="4"></circle></svg>
                                        </div>
                                        <div class="storLoction_text">
                                            <ul>
                                                <li><span class="storeId">{{$categoryProductValue->state}}</span></li>
                                                <li>{{$categoryProductValue->area}}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </figcaption>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div id="momModal_{{$categoryProductKey}}" class="modal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add MoM for <span id="distributor_name">{{$categoryProductValue->distributor_name}}</span></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('front.directory.mom.store')}}" method="POST">@csrf
                                    <textarea name="comment" id="comment" class="form-control" cols="30" rows="10"></textarea>
                
                                    <p class="small mt-3">Please note, MOM date & time will be added automatically</p>
                
                                    <input type="hidden" name="distributor_name" id="distributor_name_{{$categoryProductKey}}" value="">
                                    <input type="hidden" name="user_id" value="{{ Auth::guard('web')->user()->id }}">
                                    <button type="submit" class="btn btn-danger btn-sm mt-2">Save changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty

            @endforelse
            </div>
    </section>
</div>

{{-- mom modal --}}

@endsection
