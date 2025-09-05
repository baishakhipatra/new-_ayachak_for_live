@extends('front.profile.layouts.app')

@section('profile-content')
<div class="col-sm-7">
    <div class="profile-card">
        {{-- <h3>Default Addresses</h3>

        <div class="address-card">
            <span class="badge badge-secondary float-right">Home</span>
            <h5>Jhon Doe</h5>
            <p>College Pally, Telinipara, P.O. Sewli, Kolkata 700121<br/>Sewli Telinipara,<br/>North 24 parganas, - 700121<br/>West Bengal</p>
            <p>Mobile: 8420425082</p>
        </div> --}}

        <div class="profile-card-footer">
            <a href="{{route('front.user.address.add')}}" class="btn checkout-btn">Add Address</a>
        </div>
    </div>

    <div class="profile-card">
        <h3>Addresses</h3>
        @forelse ($data as $addressKey => $addressValue)
        <div class="address-card">
            <span class="badge badge-info float-right">{{ ($addressValue->type == 1 ? 'Home' : ($addressValue->type == 2 ? 'Work' : 'Other')) }}</span>
            <h5>{{$addressValue->user->name}}</h5>
            <p>{{$addressValue->address}}<br/>{{$addressValue->landmark ? $addressValue->landmark.',' : ''}}<br/>{{$addressValue->city}}, - {{$addressValue->pin}}<br/>{{$addressValue->state}}</p>
            <p>Mobile: {{$addressValue->user->mobile}}</p>
        </div>
        @empty
        <p>No address found. Add new.</p>
        @endforelse
    </div>
</div>
@endsection