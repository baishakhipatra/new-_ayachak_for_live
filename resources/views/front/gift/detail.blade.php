@extends('layouts.app')

@section('page', 'Scan and Win')

@section('content')
    {{--<link rel="stylesheet" href="{{ asset('css/styles/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/styles/responsive.css') }}" />--}}
    <link rel="stylesheet" href="{{ asset('styles/bootstrap.min.css') }}" />
   {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" /> --}}
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
	.fail svg {
		width: 100px;
		height: 100px;
		display: block;
		margin: 0 auto 30px;
	}
</style>

<section class="thank-section">
	<div class="container">
      <div class="thankinner">
        <div class="success">
          <figure><i class="fa-solid fa-envelope-circle-check"></i></figure>
		
         {{-- <figcaption>
            <h1>Thank You!</h1>
            <p>We've received your message. Someone from our sales team will contact you soon.</p>
          </figcaption>
        </div> --}}
        <div class="fail mb-4">
          <figure><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="512" height="512" x="0" y="0" viewBox="0 0 100 100" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M4.66 53.01c1.2 4.54 4.07 7.25 7.67 7.25.54 0 1.1-.06 1.65-.19 5.58-1.24 10.24-6.61 12.45-14.37 1.51-5.31 1.9-12.87-2.01-16.69-1.5-1.46-4.17-2.94-8.4-1.63C5.78 30.54 2.45 44.67 4.66 53.01zm11.95-23.73c.9-.28 1.74-.42 2.52-.42 1.52 0 2.82.52 3.89 1.56 3.07 3 2.96 9.55 1.48 14.71-2.01 7.04-6.11 11.89-10.96 12.97-3.2.71-5.84-1.42-6.95-5.62-2.01-7.56.9-20.37 10.02-23.2z" fill="#000000" data-original="#000000" class=""></path><path d="M12.38 56.31c.27 0 .53-.03.75-.08 4.89-1.09 8.12-6.71 9.52-11.61 1.56-5.46 1.15-10.72-.97-12.8-.25-.25-1.02-1-2.58-1-.58 0-1.23.11-1.91.32C9.26 33.58 6.69 45.31 8.46 52c.95 3.56 2.65 4.31 3.92 4.31zm5.4-23.27c.49-.15.94-.23 1.32-.23.74 0 1.02.28 1.18.43 1.36 1.33 1.9 5.74.45 10.82-1.24 4.35-4 9.31-8.03 10.21-1.22.27-1.92-1.37-2.3-2.79-1.56-5.87.9-16.44 7.38-18.44zM27.3 54.02c1.04 3.91 3.54 6.24 6.69 6.24.48 0 .97-.06 1.47-.17 4.68-1.04 8.57-5.49 10.4-11.91 1.26-4.41 1.55-10.7-1.76-13.94-1.86-1.82-4.45-2.33-7.31-1.45-8.53 2.65-11.32 14.34-9.49 21.23zm10.07-19.31c.7-.22 1.38-.33 2.02-.33 1.29 0 2.42.44 3.3 1.29 2.51 2.46 2.43 7.77 1.24 11.96-1.63 5.7-4.96 9.63-8.91 10.51-2.71.6-4.87-1.16-5.79-4.64-1.63-6.13.74-16.5 8.14-18.79z" fill="#000000" data-original="#000000" class=""></path><path d="M34.06 56.31c.2 0 .38-.02.54-.06 3.15-.7 6.09-4.29 7.47-9.15 1.22-4.3.92-8.43-.73-10.05-.21-.21-.77-.75-1.92-.75-.44 0-.94.08-1.48.25C31.71 38.48 29.7 47.73 31.1 53c.72 2.74 2.01 3.31 2.96 3.31zm4.47-17.84c.34-.11.64-.16.89-.16.34 0 .43.09.52.18.79.77 1.4 3.87.2 8.07-1.17 4.1-3.57 7.21-5.98 7.75-.46.1-.9-.91-1.14-1.81-1.18-4.49.65-12.53 5.51-14.03zM48.71 40.59l-3.59 16.05a2.959 2.959 0 0 0 2.9 3.61c1.38 0 2.59-.98 2.89-2.32l1.06-4.75c5.08-2.04 12.68-6.79 11.26-12.66-.58-2.38-2.08-3.45-3.25-3.92-3.68-1.5-8.55 1.23-9.95 2.1-.67.43-1.15 1.11-1.32 1.89zm1.95.43c.06-.25.21-.47.43-.61.43-.27 3.7-2.24 6.48-2.24.58 0 1.15.09 1.65.29 1.06.43 1.75 1.29 2.06 2.54 1.06 4.37-5.71 8.68-10.53 10.51-.31.12-.55.39-.62.72l-1.18 5.27c-.11.51-.64.85-1.15.73a.961.961 0 0 1-.73-1.15z" fill="#000000" data-original="#000000" class=""></path><path d="M52.18 48.69c.15 0 .31-.04.45-.11 2.81-1.43 7.4-4.54 6.78-7.12-.15-.63-.44-1.02-.91-1.21-1.67-.68-4.99 1.02-5.64 1.37-.26.14-.44.38-.5.66l-1.16 5.18c-.08.38.06.77.36 1.01.18.14.4.22.62.22zm2.03-5.51c1.4-.7 2.65-1.06 3.25-1.09-.13.56-1.26 2.02-3.82 3.63zM74.03 48.61c1.63.16 3.08-1.03 3.25-2.65l.55-5.41a2.99 2.99 0 0 0-1.84-3.05c-.42-.17-4.21-1.62-7.76.02-2.08.96-3.62 2.78-4.45 5.25-1.57 4.72 1.6 7.99 3.5 9.95.24.25.85.88 1.21 1.31-.38.32-.6.3-.71.29-1.06-.08-2.59-1.58-3.17-2.36-.47-.64-1.16-1.06-1.94-1.18s-1.57.07-2.2.54c-.64.47-1.06 1.16-1.18 1.94s.07 1.57.54 2.2c.33.45 3.37 4.44 7.48 4.76.18.01.36.02.53.02 1.83 0 3.55-.75 4.97-2.15 4.07-4.02.32-7.88-1.28-9.52-2.08-2.14-2.48-2.88-2.13-3.94.3-.9.72-1.47 1.29-1.73.29-.14.62-.2.95-.22l-.27 2.68a2.973 2.973 0 0 0 2.66 3.25zm-4.18-7.51c-1.09.51-1.86 1.46-2.34 2.91-.76 2.28.53 3.84 2.59 5.96 2.09 2.15 3.76 4.28 1.31 6.7-1.15 1.13-2.54 1.67-3.94 1.56-3.28-.26-5.91-3.8-6.02-3.95-.15-.21-.22-.46-.18-.72s.17-.48.38-.63a.975.975 0 0 1 .72-.18c.26.04.48.17.64.39.02.03 2.25 2.98 4.62 3.17.88.06 1.67-.26 2.42-1.01.33-.32.53-.63.61-.91.03-.1.04-.2.03-.31-.03-.66-.61-1.36-1.98-2.77-1.79-1.84-4.23-4.35-3.04-7.92.65-1.94 1.82-3.34 3.38-4.07 2.79-1.29 5.84-.12 6.18.02.4.16.64.57.6.99l-.55 5.41a.96.96 0 0 1-1.06.86.96.96 0 0 1-.86-1.06l.38-3.73a1 1 0 0 0-.86-1.09c-.81-.08-1.99-.11-3.03.38zM81.04 53.51a2.967 2.967 0 0 0 3.46-2.37l2.58-13.72c.15-.78-.02-1.57-.47-2.22s-1.12-1.1-1.9-1.24a2.95 2.95 0 0 0-2.22.46c-.66.45-1.1 1.12-1.24 1.9l-2.58 13.72c-.15.78.02 1.57.47 2.22.45.67 1.13 1.11 1.9 1.25zm2.19-16.81c.05-.25.19-.47.41-.62.16-.11.35-.17.54-.17a.997.997 0 0 1 .8.42c.15.21.2.47.15.72l-2.58 13.72c-.1.52-.57.88-1.13.77-.25-.05-.47-.19-.62-.4s-.2-.47-.15-.72zM80.95 60.25a2.97 2.97 0 1 0 0-5.94 2.97 2.97 0 0 0 0 5.94zm0-3.93a.97.97 0 1 1-.002 1.942.97.97 0 0 1 .002-1.942zM92.51 27.79c-.66.45-1.1 1.12-1.24 1.9l-3.42 18.18c-.15.78.02 1.57.47 2.22a2.939 2.939 0 0 0 2.45 1.29c1.42 0 2.65-1.02 2.91-2.42l3.42-18.18c.15-.78-.02-1.57-.47-2.22s-1.12-1.1-1.9-1.24-1.57.02-2.22.47zm2.62 2.63L91.71 48.6c-.1.52-.58.88-1.13.77-.25-.05-.47-.19-.62-.4s-.2-.47-.15-.72l3.42-18.18c.05-.25.19-.47.41-.62.16-.11.35-.17.54-.17a.997.997 0 0 1 .8.42c.15.21.19.47.15.72zM92.86 57.29c0-1.64-1.33-2.97-2.98-2.97a2.97 2.97 0 0 0 .01 5.94c1.64-.01 2.97-1.34 2.97-2.97zm-3.94 0a.97.97 0 0 1 1.94 0c0 .53-.43.97-.97.97a.977.977 0 0 1-.97-.97zM5 71.1c.08 0 .16-.01.23-.03.4-.1 40.54-9.57 81.75-5.28a1 1 0 1 0 .21-1.99c-41.56-4.32-82.02 5.23-82.42 5.33-.54.13-.87.67-.74 1.21.11.45.52.76.97.76z" fill="#000000" data-original="#000000" class=""></path></g></svg></figure>

          <figcaption>
           {{-- <h1>Sorry!</h1>
            <p>Better luck next time</p> --}}
			   {!!$data->failure_message!!}
          </figcaption>
        </div>
		  <div class="col-sm-12 mt-3 mt-sm-0">
                           <a href="{{route('front.scanandwin.winner')}}" class="form_submit">Check Winners</a>
          </div>
      </div>
		</div>
    </section>
@endsection

@section('script')
  {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>--}}
@endsection