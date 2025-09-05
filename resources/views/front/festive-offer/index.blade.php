@extends('layouts.app')

@section('page', 'Submit Coupon Request')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css">
@section('content')
<style>
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
	.swal-footer {
		text-align:center;
	}
</style>
<section class="cart-header mb-3 mb-sm-5"></section>
<section class="cart-wrapper">
    <div class="container">
        <div class="complele-box">
            <h5 class="display-4">Festive Offer</h5>

            <section class="contact_form" >
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-9">
                            <form  action="{{ route('front.festiveoffer.store') }}" method="post">@csrf
								@if($errors->any())
									{{--<div class="alert alert-danger" role="alert">
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>--}}

										@foreach($errors->all() as $error)
											{{--{{ $error }}<br/>--}}
								            {{--  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.js">                                                 </script>--}}
								<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
												<script type="text/javascript">
												swal({
  title: "Error!",
  text: "{{ $error }}",
  type: "error",
													 icon: "error",
  
})
												//timer: 1500
													
													
												</script>
										@endforeach
									</div>
								@endif
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Name *</label>
                                            <input type="text" name="name" value="{{old('name')}}" placeholder="" class="form-control">
											@error('name') <p class="small text-danger">{{ $message }}</p> @enderror
                                        </div>
                                        
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Phone *</label>
                                            <input type="number" name="phone" value="{{old('phone')}}" placeholder="" class="form-control">
											@error('phone') <p class="small text-danger">{{ $message }}</p> @enderror
                                        </div>
                                        
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Email *</label>
                                            <input type="email" name="email" value="{{old('email')}}" placeholder="" class="form-control">
											@error('email') <p class="small text-danger">{{ $message }}</p> @enderror
                                        </div>
                                        
                                    </div>

                                    <div class="col-sm-12">
                                        <p class="" id=""></p>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group justify-content-center align-items-center">
                                            {{-- <button type="submit" class="form_submit">Submit Now</button> --}}
                                            <input type="submit" name="" class="form_submit" value="Submit Now">
                                        </div>
                                    </div>

                                    <div class="col-12 mt-3">
                                       {{-- <p>Disclaimer : All information provided would be kept strictly confidential and used only for the purpose of the promotional campaign. Upon receive of your enquiry, we will send you a valid coupon code through you email</p> --}}
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>
@endsection

@section('script')
    <script>
		
    </script>
@endsection