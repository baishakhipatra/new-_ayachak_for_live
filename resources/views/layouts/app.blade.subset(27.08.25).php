<!doctype html>
<html>

<head>

	{{-- NEW script shared on 2023-01-09 --}}
	<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-K9F32CJ');</script>
<!-- End Google Tag Manager -->
	
	{{-- NEW script shared on 2023-01-05 --}}
	{{-- <script async src="https://www.googletagmanager.com/gtag/js?id=UA-243366616-1"></script> --}}
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-243366616-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-243366616-1');
</script>

{{-- <script type='text/javascript'>
			var gaProperty = 'UA-243366616-1';
			var disableStr = 'ga-disable-' + gaProperty;
			if ( document.cookie.indexOf( disableStr + '=true' ) > -1 ) {
				window[disableStr] = true;
			}
			function gaOptout() {
				document.cookie = disableStr + '=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/';
				window[disableStr] = true;
			}
		</script> --}}
	
	{{-- <script type='text/javascript'>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script', '//www.google-analytics.com/analytics.js','ga');ga( 'create', 'UA-243366616-1', 'auto' );(window.gaDevIds=window.gaDevIds||[]).push('dOGY3NW');ga( 'require', 'displayfeatures' );ga( 'set', 'anonymizeIp', true );
		ga( 'set', 'dimension1', 'no' );
ga( 'require', 'ec' );</script> --}}
<noscript><style id="nojs-css">.rll-youtube-player, [src]{display:none !important;}</style></noscript>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ONN Total Comfort | @yield('page')</title>

    <link rel="icon" href="{{asset('img/favicon.png')}}" type="image/png" sizes="16x16">
    <link rel="stylesheet" href="{{ asset('css/plugin.css') }}">
    <!-- <link rel="stylesheet" href="https://unpkg.com/swiper@8.0.7/swiper-bundle.min.css"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link rel="stylesheet" href="{{ asset('node_modules/select2/dist/css/select2.min.css') }}">
    <link rel='stylesheet' href="{{ asset('node_modules/lightbox2/dist/css/lightbox.min.css?ver=5.8.2') }}">
    <link rel='stylesheet' href="{{ asset('node_modules/@fancyapps/fancybox/dist/jquery.fancybox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('scss/css/preload.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
    <link rel="stylesheet" href="{{ asset('css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
	<link rel="canonical" href="https://onninternational.com/">

    <script src="{{ asset('node_modules/jquery/dist/jquery.min.js') }}"></script>
    <!-- <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
</head>

<body>
	{{-- NEW script shared on 2023-01-09 --}}
	<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K9F32CJ"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

    <div class="search_wrap">
        <a href="javascript:void(0)" class="search_close">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </a>
        <div class="search_area">
            <form class="search_form" method="GET" action="{{route('front.search.index')}}">
                <input type="search" name="query" class="search_box" placeholder="Search Product Here.." autofocus>
                <button type="submit" class="search_btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </button>
            </form>
            <div id="searchResp"></div>
        </div>
    </div>

@extends('front.layout.app')
@section('content')


    <div class="overlay">
        <div class="overlay__close">
            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#c10909" stroke-width="0.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </div>

        <div class="overlay_wrapper">
            <div class="overlay_block">
                <ul class="overlay_menu">
                    <li><a href="javascript: void(0)">Shop by collection</a>
                        <ul class="overlay_submenu">
                            @foreach($collections as $collectionKey => $collectionValue)
                            <li>
                                <a href="{{ route('front.collection.detail', $collectionValue->slug) }}">
                                    <img class="logo_image" src="{{ asset($collectionValue->sketch_icon) }}" />
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    @foreach ($categoryNavList as $categoryNavKey => $categoryNavValue)
                    <li>
                        <a href="javascript: void(0)">{{$categoryNavValue['parent']}}</a>
                        <ul class="overlay_submenu">
                            @foreach ($categoryNavValue['child'] as $childCatKey => $childCatValue)
                                <li><a href="{{ route('front.category.detail', $childCatValue['slug']) }}"><img src="{{asset($childCatValue['sketch_icon'])}}"> {{$childCatValue['name']}}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <!-- <div class="menu__row"> -->
            <!-- @foreach ($collections as $collectionKey => $collectionValue)
            @php if($collectionKey == 0 || $collectionKey == 1 || $collectionKey == 2) continue; @endphp
            <div class="menu__block">
                <div class="menu__text">{{$collectionValue->name}}</div>
                <div class="menu__links">
                    <ul>
                        @foreach ($collectionValue->ProductDetails as $collectionProductKey => $collectionProductValue )
							@php if($collectionProductKey == 5) break; @endphp
                            @php if($collectionProductValue->status == 0) continue; @endphp
                            <li><a href="{{ route('front.product.detail', $collectionProductValue->slug) }}">{{$collectionProductValue->name}}</a></li>
                        @endforeach
                        <li><a href="{{ route('front.collection.detail', $collectionValue->slug) }}">View All</a></li>
                    </ul>
                </div>
                <div class="menu__image">
                    <img src="{{asset($collectionValue->icon_path)}}">
                </div>
            </div>
            @endforeach -->

<!--
            @foreach ($categoryNavList as $categoryNavKey => $categoryNavValue)
            <div class="menu__block">
                <div class="menu__text">{{$categoryNavValue['parent']}}</div>
                <div class="menu__links">
                    <ul class="mega-drop-menu">
                        @foreach ($categoryNavValue['child'] as $childCatKey => $childCatValue)
                            <li><a href="{{ route('front.category.detail', $childCatValue['slug']) }}"><img src="{{asset($childCatValue['sketch_icon'])}}"> {{$childCatValue['name']}}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="menu__image">
                @php
                    if($categoryNavValue['parent'] == "Innerwear") {
                    @endphp
                        <img src="{{ asset('uploads/collection/range3.png') }}">
                    @php
                    }
                    elseif($categoryNavValue['parent'] == "Outerwear") {
                    @endphp
                        <img src="{{ asset('uploads/collection/range5.png') }}">
                    @php
                    }
                    elseif($categoryNavValue['parent'] == "Winter wear") {
                    @endphp
                        <img src="{{ asset('uploads/collection/range1.png') }}">
                    @php
                    } else {
                    @endphp
                        <img src="{{ asset('uploads/collection/range4.png') }}">
                    @php
                    }
                @endphp
                </div>
            </div>
            @endforeach

        </div> -->
    </div>

    <form id="logout-form" action="{{ route('front.logout') }}" method="POST" style="display: none;">@csrf</form>

@endsection


    

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.6.0/umd/popper.min.js" integrity="sha512-BmM0/BQlqh02wuK5Gz9yrbe7VyIVwOzD1o40yi1IsTjriX/NGF37NyXHfmFzIlMmoSIBXgqDiG1VNU6kB5dBbA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('js/plugin.js') }}"></script>
    <script src="{{ asset('node_modules/bootstrap/dist/js/bootstrap.js') }}"></script>
    <!-- <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script> -->
    <script src="{{ asset('node_modules/gsap/dist/gsap.min.js') }}"></script>
    <script src="{{ asset('node_modules/gsap/dist/ScrollTrigger.min.js') }}"></script>
    <script src="{{ asset('node_modules/waypoints/lib/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('node_modules/counterup/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('node_modules/lightbox2/dist/js/lightbox.min.js') }}"></script>
    <script src="{{ asset('node_modules/@fancyapps/fancybox/dist/jquery.fancybox.min.js') }}"></script>
    <script src="{{ asset('node_modules/select2/dist/js/select2.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.16.0/TweenMax.min.js"></script>
    <script src="{{ asset('node_modules/scrollmagic/scrollmagic/minified/ScrollMagic.min.js') }}"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.3/plugins/animation.gsap.min.js'></script>
    <script src="{{ asset('node_modules/scrollmagic/scrollmagic/minified/plugins/debug.addIndicators.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script type="text/javascript" src="lib.js"></script>

    <script>
        // enable tooltips everywhere
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        // sweetalert fires | type = success, error, warning, info, question
        function toastFire(type = 'success', title, body = '') {
             Swal.fire({
                icon: type,
                title: title,
                text: body,
                confirmButtonColor: '#c10909',
               //timer: 5000
            }) 

		/*	const Toast = Swal.mixin({
				toast: true,
				position: 'top-end',
				showConfirmButton: false,
				timer: 3000,
				// timerProgressBar: true,
                showCloseButton: true,
				didOpen: (toast) => {
					toast.addEventListener('mouseenter', Swal.stopTimer)
					toast.addEventListener('mouseleave', Swal.resumeTimer)
				}
			});

			Toast.fire({
				icon: type,
				title: title
			});*/
        }

        // on session toast fires
        @if (Session::has('success'))
            toastFire('success', '{{ Session::get('success') }}');
        @elseif (Session::has('failure'))
            toastFire('warning', '{{ Session::get('failure') }}');
        @endif

        // button text changes on form submit
        $('form').on('submit', function(e) {
            $('button').attr('disabled', true).prop('disabled', 'disabled');
        });

        // subscription mail form
        $('#joinUsForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url : $(this).attr('action'),
                method : $(this).attr('method'),
                data : {_token : '{{csrf_token()}}',email : $('input[name="subsEmail"]').val()},
                beforeSend : function() {
                    $('#joinUsMailResp').html('Please wait <i class="fas fa-spinner fa-pulse"></i>');
                },
                success : function(result) {
                    result.resp == 200 ? $icon = '<i class="fas fa-check"></i> ' : $icon = '<i class="fas fa-info-circle"></i> ';
                    $('#joinUsMailResp').html('<span class="success_message">'+ $icon+result.message + '</span>');
                    $('button').attr('disabled', false);
                }
            });
        });

        // remove applied coupon option
        function removeAppliedCoupon() {
            $.ajax({
                url: '{{ route('front.cart.coupon.remove') }}',
                method: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}'
                },
                beforeSend: function() {
                    $('#applyCouponBtn').text('Checking');
                },
                success: function(result) {
                    if (result.type == 'success') {
                        $('#appliedCouponHolder').html('');
                        $('input[name="couponText"]').val('').attr('disabled', false);
                        $('#applyCouponBtn').text('Apply').css('background', '#141b4b').attr('disabled', false);

                        let grandTotalWithoutCoupon = $('input[name="grandTotalWithoutCoupon"]').val();
                        $('#displayGrandTotal').text(grandTotalWithoutCoupon);

                        toastFire(result.type, result.message);

                        location.href="{{url()->current()}}";
                    } else {
                        toastFire(result.type, result.message);
                        $('#applyCouponBtn').text('Apply');
                    }
                }
            });
        }

        // input key validation
        $('input[name="fname"]').on('keypress', function(event) {
			validate(event, 'charOnly');
        });
		$('input[name="lname"]').on('keypress', function(event) {
			validate(event, 'charOnly');
        });
		$('input[name="mobile"]').on('keypress', function(event) {
			validate(event, 'numbersOnly');
        });
        $('input[name="billing_pin"]').on('keypress', function(event) {
			validate(event, 'numbersOnly');
        });
        $('input[name="shipping_pin"]').on('keypress', function(event) {
			validate(event, 'numbersOnly');
        });
		$('input[name="billing_country"]').on('keypress', function(event) {
			validate(event, 'charOnly');
        });
		$('input[name="billing_city"]').on('keypress', function(event) {
			validate(event, 'charOnly');
        });
		$('input[name="billing_state"]').on('keypress', function(event) {
			validate(event, 'charOnly');
        });
		$('input[name="shipping_country"]').on('keypress', function(event) {
			validate(event, 'charOnly');
        });
		$('input[name="shipping_city"]').on('keypress', function(event) {
			validate(event, 'charOnly');
        });
		$('input[name="shipping_state"]').on('keypress', function(event) {
			validate(event, 'charOnly');
        });

        function validate(evt, type) {
			var theEvent = evt || window.event;
			// var regex = /[0-9]|\./;
			var charOnlyRegex = /^[A-Za-z]+$/;
			var numberOnlyRegex = /^[0-9]+$/;

            // Handle paste
			if (theEvent.type === 'paste') {
				key = event.clipboardData.getData('text/plain');
			} else {
				// Handle key press
				var key = theEvent.keyCode || theEvent.which;
				key = String.fromCharCode(key);
			}

            // character only
            if (type == "charOnly") {
                if( !charOnlyRegex.test(key) ) {
					theEvent.returnValue = false;
					if(theEvent.preventDefault) theEvent.preventDefault();
                }
            }

            // number only
            if (type == "numbersOnly") {
                if( !numberOnlyRegex.test(key) ) {
					theEvent.returnValue = false;
					if(theEvent.preventDefault) theEvent.preventDefault();
                }
            }
        }

        // search box suggestion
        $('.search_box').on('keyup', function() {
            $.ajax({
                url : "{{ route('front.search.suggestion') }}",
                method : "POST",
                data : {_token : '{{csrf_token()}}',val : $(this).val()},
                beforeSend : function() {
                    $('#searchResp').html('<div class="col-12">Please wait...</div>');
                    // $('#joinUsMailResp').html('Please wait <i class="fas fa-spinner fa-pulse"></i>');
                },
                success : function(result) {
                    if (result.status === 200) {
                        var content = '';
                        $.each(result.data, (key, value) => {
                            content += `
                            <div class="searchbar-single-product">
                                <a href="${value.url}">
                                    <div class="d-flex">
                                        <img src="${value.image}" alt="" height="100">
                                        <div class="product-info">
                                            <h5>${value.name}</h5>
                                            <p>&#8377; ${value.offer_price}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            `;
                        })

                        $('#searchResp').html(content);
                    } else {
                        $('#searchResp').html('<div class="no-found text-center">'+result.message+'</div>');
                    }
                    // $('#searchResp').html('<div class="col-12">Please wait...</div>');
                    // result.resp == 200 ? $icon = '<i class="fas fa-check"></i> ' : $icon = '<i class="fas fa-info-circle"></i> ';
                    // $('#joinUsMailResp').html('<span class="success_message">'+ $icon+result.message + '</span>');
                    // $('button').attr('disabled', false);
                }
            });
        });
		document.addEventListener('contextmenu', event => event.preventDefault());
        document.onkeydown = function (e) {
            if(e.keyCode == 123) {
                return false;
            }
            if(e.ctrlKey && e.shiftKey && e.keyCode == 73){
                return false;
            }
            if(e.ctrlKey && e.shiftKey && e.keyCode == 74) {
                return false;
            }
            if(e.ctrlKey && e.keyCode == 85) {
                return false;
            }
        }

        /*
		function validate(evt, type) {
			var theEvent = evt || window.event;
			// var regex = /[0-9]|\./;
			var regex = /^[A-Za-z]+$\d{10}/;

			// Handle paste
			if (theEvent.type === 'paste') {
				key = event.clipboardData.getData('text/plain');
			} else {
				// Handle key press
				var key = theEvent.keyCode || theEvent.which;
				key = String.fromCharCode(key);
			}

			if( regex.test(key) ) {
				if(type == 'numbersOnly') {
					theEvent.returnValue = false;
					if(theEvent.preventDefault) theEvent.preventDefault();
				}
			} else {
                // alert()
				if(type == 'charOnly') {
					theEvent.returnValue = false;
					if(theEvent.preventDefault) theEvent.preventDefault();
				}
				// console.log(theEvent.length)
			}
		}
        */

        /* let chekoutAmount = getCookie('checkoutAmount');
        // console.log(chekoutAmount);
        if (chekoutAmount) {
            couponApplied(chekoutAmount);
        }

        // checkout page coupon applied design
        function couponApplied(amount) {
            $('input[name="grandTotal"]').val(amount);
            $('#displayGrandTotal').text(amount);

            let couponContent = `
            <div class="cart-total">
                <div class="cart-total-label">
                    COUPON APPLIED<br/>
                    <a href="javascript:void(0)" onclick="removeAppliedCoupon(${amount})"><small>(Remove this coupon)</small></a>
                </div>
                <div class="cart-total-value">- ${amount}</div>
            </div>
            `;

            $('#appliedCouponHolder').html(couponContent);
        } */

        // let paymentGatewayAmount = chekoutAmount ? parseInt(chekoutAmount) * 100 : document.querySelector('[name="grandTotal"]').value * 100;
        // let paymentGatewayAmount = parseInt($('#displayGrandTotal').text()) * 100;
    </script>
