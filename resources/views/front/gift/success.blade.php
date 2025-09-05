@extends('layouts.app')

@section('page', 'Scan and Win')

@section('content')
    {{--<link rel="stylesheet" href="{{ asset('css/styles/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/styles/responsive.css') }}" />--}}
    <link rel="stylesheet" href="{{ asset('styles/bootstrap.min.css') }}" />
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />--}}
<style>
	.thank-section {
		padding-top: 80px;
		text-align: center;
	}
    .contact_form {
		display: block;
		padding: 60px 0;
	}
	.contact_form h2 {
	    font-size: 32px;
	    font-weight: 700;
	    margin: 0 0 60px;
	    line-height: 1.2;
	    text-transform: uppercase;
	    color: #000;
	}
	.contact_form .form-group {
		flex-direction: column;
	}
	.contact_form .form-group label {
		font-size: 16px;
		color: #000;
		font-weight: 400;
	}
	.contact_form .form-group input[type="text"], 
	.contact_form .form-group input[type="email"], 
	.contact_form .form-group input[type="number"], 
	.contact_form .form-group input[type="date"],
	.contact_form .form-group input[type="tel"],
	.contact_form .form-group select, 
	.contact_form .form-group textarea {
		background: #f7f7f7;
		border: 1px solid #ddd;
		border-radius: 0;
		margin-bottom: 15px;
		color: #000;
		height: 52px;
	}
	.cart-wrapper .form-group input[type="text"]:not(:placeholder-shown), .cart-wrapper .form-group input[type="email"]:not(:placeholder-shown), .cart-wrapper .form-group input[type="number"]:not(:placeholder-shown), .cart-wrapper .form-group input[type="date"]:not(:placeholder-shown), .cart-wrapper .form-group input[type="tel"]:not(:placeholder-shown), .cart-wrapper .form-group select:not(:placeholder-shown), .cart-wrapper .form-group textarea:not(:placeholder-shown) {
		padding: 0 15px;
	}
	.form_submit {
		display: inline-block;
		vertical-align: top;
		padding: 15px 40px;
		background: #c10909;
		color: #fff;
		font-size: 18px;
		font-weight: 400;
		border-radius: 3px;
		border: none;
	}
	.contact_form p {
		font-size: 14px;
		font-weight: 500;
		line-height: 1.4;
	}
	.success img {
		width: 200px;
		height: 200px;
		display: block;
		margin: 0 auto 30px;
		object-fit: scale-down;
		object-position: center;
	}
	
</style>
@php
   $gift=\App\Models\Gift::where('id',$customer->gift_id)->first();
   $txn = DB::table('user_txn_histories')->where('customer_id',$customer->id)->first();
   $success_message= str_replace("Laptop", $gift->gift_title, $data->success_message);
   $message= str_replace("Display QR ID",$txn->qrcode,$success_message);
@endphp
<section class="thank-section">
	<div class="container">
      <div class="thankinner">
        <div class="success mb-3">
			@if(!empty($gift->gift_image))
          <figure><img src="{{asset($gift->gift_image)}}"></figure>
		   @endif
          <figcaption>
			{{-- <h1>You Win!</h1> --}}
           {{-- <p>Thank You! We've received your request. Someone from our sales team will contact you soon.</p> --}}
			  {!!$message!!}
			  
          </figcaption>
        </div>
		 {{-- <h1>{{$gift->gift_title}}</h1> --}}
		  <div class="col-sm-12 mt-3 mt-sm-0">
                            <a href="{{route('front.scanandwin.winner')}}" class="form_submit">Check Winners</a>
                        </div>
      {{--  <div class="fail">
          <figure><i class="fa-solid fa-xmark"></i></figure>

          <figcaption>
            <h1>Sorry!</h1>
            <p>Mail not delivered successfully. Please try again</p>
          </figcaption>
        </div>--}}
      </div>
	</div>
    </section>
@endsection

@section('script')
  {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>--}}
@endsection