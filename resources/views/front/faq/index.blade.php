@extends('layouts.app')

@section('page', 'FAQ')

@section('content')
<section class="cart-header mb-3 mb-sm-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h4>Frequently Asked Question</h4>
            </div>
        </div>
    </div>
</section>

<section class="cart-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <ul class="account-list mt-0">
                    <li>
                        <span><strong>Quick Links</strong></span>
                        <ul>
                            <li class="{{ request()->is('faq') ? 'active' : '' }}"><a href="{{route('front.faq.index')}}">FAQ</a></li>
                            <li><a href="{{route('front.user.order')}}">My Shopping</a></li>
                            {{--<li class="{{ request()->is('shipping-and-delivery') ? 'active' : '' }}"><a href="{{route('front.content.shipping')}}">Shipping & Delivery</a></li>--}}
                            {{--<li class="{{ request()->is('payment-voucher-promotion') ? 'active' : '' }}"><a href="{{route('front.content.payment')}}">Payment, Voucher & Promotions</a></li>--}}
                            <li class="{{ request()->is('return-policy') ? 'active' : '' }}"><a href="{{route('front.content.return')}}">Returns Policy</a></li>
                            <li class="{{ request()->is('refund-policy') ? 'active' : '' }}"><a href="{{route('front.content.refund')}}">Refund & Cancellation Policy</a></li>
                            {{--<li class="{{ request()->is('service-and-contact') ? 'active' : '' }}"><a href="{{route('front.content.service')}}">Service & Contact</a></li>--}}
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="col-sm-8">
                <div class="cms_context">
                    @foreach ($data as $faqKey => $faqValue)
                        <h3 class="faq_heading">{!! $faqValue->question !!}</h3>
                        <div class="faq_content">
                            {!! $faqValue->answer !!}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection