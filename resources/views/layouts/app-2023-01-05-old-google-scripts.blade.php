<!doctype html>
<html>

<head>
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-K9F32CJ');</script>

    {{-- another google tag  - shared by Rohit da - on 2022-12-26 --}}
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-243366616-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'UA-243366616-1');
    </script>
	<!-- End Google Tag Manager --> 

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ONN Total Comfort | @yield('page')</title>

    <link rel="icon" href="{{asset('img/favicon.png')}}" type="image/png" sizes="16x16">
    <link rel="stylesheet" href="{{ asset('css/plugin.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/swiper@8.0.7/swiper-bundle.min.css">
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
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
</head>

<body>
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
    <header>
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="nav-toggle">
                    <span></span>
                </div>
                <div class="col-auto">
                    <a href="{{ route('front.home') }}" class="logo">
                        <img src="{{ asset('img/logo.png') }}">
                    </a>
                </div>
                <div class="col-auto d-none d-md-block ml-auto menu_area">
                    <nav class="main-nav">
                        <ul>
                            <li>
                                <a href="{{ route('front.home') }}" class="home"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></a>
                            </li>
                            <li>
                                <a href="javascript: void(0)">Shop <i class="far fa-angle-down"></i></a>
                                <div class="sub-menu mega-menu">
                                    <ul>
                                        @foreach ($categoryNavList as $categoryNavKey => $categoryNavValue)
                                        <li>
                                            <h5>{{$categoryNavValue['parent']}}</h5>
                                            <ul class="mega-drop-menu">
                                                @foreach ($categoryNavValue['child'] as $childCatKey => $childCatValue)
                                                    <li><a href="{{ route('front.category.detail', $childCatValue['slug']) }}"><img src="{{asset($childCatValue['sketch_icon'])}}"> {{$childCatValue['name']}}</a></li>
                                                @endforeach
                                            </ul>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="javascript: void(0)">Collection <i class="far fa-angle-down"></i></a>
                                <div class="sub-menu mega-menu">
                                    <ul>
                                        @foreach($collections as $collectionKey => $collectionValue)
                                        <li>
                                            <a href="{{ route('front.collection.detail', $collectionValue->slug) }}">
                                                <img src="{{ asset($collectionValue->sketch_icon) }}" />
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                            <!-- <li>
                                <a href="{{route('front.sale.index')}}">Sale</a>
                            </li> -->
                        </ul>
                    </nav>
                </div>
                <div class="col-auto">
                    <nav class="account-nav">
                        <ul>
                            <li>
                                <a href="javascript: void(0)" id="search_toggle">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                </a>
                            </li>
                            <li>
                                <a href="{{route('front.user.wishlist')}}" style="position: relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                    <div id="wishlist-count" class="{{ ($wishlistCount > 0) ? 'd-block' : 'd-none' }}" style="position: absolute;
                                    top: -9px;
                                    right: -9px;
                                    z-index: 9;
                                    width: 20px;
                                    height: 20px;
                                    border-radius: 50%;
                                    background: #c1080a;
                                    color: #fff;
                                    font-size: 10px;
                                    text-align: center;
                                    font-weight: 700;
                                    padding: 4px 0px;">{{$wishlistCount}}</div>
                                </a>
                            </li>
                            <li>
                                @if(auth()->guard('web')->check())
                                    <a href="{{route('front.user.login')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#c10909" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg> <i class="far fa-angle-down"></i>
                                    </a>
                                    <div class="profile_dropdown">
                                        <a href="{{route('front.user.order')}}" class="{{(request()->is('user/order*')) ? 'active' : '' }}">Orders</a>
                                        <a href="{{route('front.user.coupon')}}" class="{{request()->is('user/coupon*') ? 'active' : '' }}">Coupons</a>

                                        <a href="{{route('front.user.manage')}}" class="{{request()->is('user/manage*') ? 'active' : '' }}">Profile</a>
                                        <a href="{{route('front.user.wishlist')}}" class="{{request()->is('user/wishlist*') ? 'active' : '' }}">Wishlist</a>
                                        <a href="{{route('front.user.address')}}" class="{{request()->is('user/address*') ? 'active' : '' }}">Address</a>
                                        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                                    </div>
                                @else
                                    <a href="{{route('front.user.login')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                    </a>
                                @endif
                            </li>
                            <li>
                                <a href="{{route('front.cart.index')}}" style="position: relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                                    <div id="cart-count" class="{{ ($cartCount > 0) ? 'd-block' : 'd-none' }}" style="position: absolute;
                                    top: -9px;
                                    right: -9px;
                                    z-index: 9;
                                    width: 20px;
                                    height: 20px;
                                    border-radius: 50%;
                                    background: #c1080a;
                                    color: #fff;
                                    font-size: 10px;
                                    text-align: center;
                                    font-weight: 700;
                                    padding: 4px 0px;">{{$cartCount}}</div>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <div class="overlay">
        <div class="overlay__close">
            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#c10909" stroke-width="0.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </div>

        <div class="overlay_wrapper">
            <div class="overlay_block">
                <ul class="overlay_menu">
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

    <main>@yield('content')</main>

    <footer class="footer">
        <div class="footer-main">
            <div class="container">
                <div class="footer-main-top">
                    <div class="row align-items-center align-items-sm-end">
                        <div class="col-auto">
                            <img src="{{ asset('img/footer-logo.png') }}" />
                        </div>
                        <div class="col">
                            <div class="footer-block">
                                <ul class="social">
                                    <li><a href="{{$settings[9]->content}}" target="_blank"><i class="fab fa-facebook-f" aria-hidden="true"></i></a></li>
                                    <li><a href="{{$settings[10]->content}}" target="_blank"><i class="fab fa-twitter" aria-hidden="true"></i></a></li>
                                    <li><a href="{{$settings[12]->content}}" target="_blank"><i class="fab fa-instagram" aria-hidden="true"></i></a></li>
                                    <li><a href="{{$settings[11]->content}}" target="_blank"><i class="fab fa-youtube" aria-hidden="true"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-lg-4 mb-3 mb-md-0">
                        <div class="newsletter-form">
                            <form method="POST" action="{{route('front.subscription')}}" id="joinUsForm">@csrf
                                <p>Join us for more updates</p>
                                <div class="footer-form">
                                    <input type="email" name="subsEmail" value="" placeholder="Enter your email address">
                                    <button type="submit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                                    </button>
                                </div>
                                <p class="mt-3" id="joinUsMailResp"></p>
                                {{-- @error('email') <p class="mb-0 text-white small">{{$message}}</p>@enderror --}}
                                {{-- @if(Session::get('mailSuccess')) <p class="mb-0 text-white small">{{Session::get('mailSuccess')}}</p>@endif --}}
                            </form>

                            <div class="footer-block mt-3 mt-md-auto">
                                <img src="{{ asset('img/payment-options.png') }}" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-7 offset-lg-1">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="footer-block">
                                    <div class="footer-heading">Quick Links</div>
                                    <ul class="footer-block-menu">
                                        @foreach ($categories as $categoryIndex => $categoryValue)
                                            <li><a href="{{route('front.category.detail', $categoryValue->slug)}}">{{$categoryValue->name}}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="footer-block mb-2 mb-sm-5">
                                    <div class="footer-heading">Explore More</div>
                                    <ul class="footer-block-menu">
                                        <li><a href="{{route('front.content.blog')}}">Blog</a></li>
                                        {{-- <li><a href="{{route('front.content.blog.detail')}}">Blog Detail</a></li> --}}
                                        {{-- <li><a href="{{route('front.content.about')}}">About</a></li> --}}
                                        <li><a href="{{route('front.content.contact')}}">Contact</a></li>
                                        <li><a href="{{route('front.content.corporate')}}">Corporate</a></li>
                                        <li><a href="{{route('front.content.news')}}">News</a></li>
                                        {{-- <li><a href="{{route('front.content.news.detail')}}">News detail</a></li> --}}
                                        {{-- <li><a href="{{route('front.content.career')}}">Career</a></li> --}}
                                        <li><a href="{{route('front.content.global')}}">Global</a></li>
                                        <li><a href="{{route('front.franchise.index')}}">Franchise</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="footer-block mb-2 mb-sm-5">
                                    <div class="footer-heading">Customer Services</div>
                                    <ul class="footer-block-menu">
                                        <li><a href="{{route('front.faq.index')}}">FAQ</a></li>
                                        <li><a href="{{route('front.user.order')}}">My Shopping</a></li>
                                        {{--<li><a href="{{route('front.content.shipping')}}">Shipping & Delivery</a></li>--}}
                                        {{--<li><a href="{{route('front.content.payment')}}">Payment, Voucher & Promotions</a></li>--}}
                                        <li><a href="{{route('front.content.return')}}">Returns Policy</a></li>
                                        <li><a href="{{route('front.content.refund')}}">Refund & Cancellation Policy</a></li>
                                        {{--<li><a href="{{route('front.content.service')}}">Service & Contact</a></li>--}}
                                    </ul>
                                </div>

                                <div class="footer-block">
                                    <div class="footer-heading">Customer Support</div>
                                    <ul class="footer-block-menu support">
                                        <li><a href="tel:{{ $settings[6]->content }}">
<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg> {{ $settings[6]->content }}</a></li>
										<li><a href="mailto:{{ $settings[7]->content }}"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg> {{ $settings[7]->content }}</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        	
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <div class="footer-bottom-menu">
                            <a href="{{route('front.content.terms')}}">Terms & Conditions</a>
                            <a href="{{route('front.content.privacy')}}">Privacy Statement</a>
                            {{--<a href="{{route('front.content.security')}}">Security</a>--}}
                            <a href="{{route('front.content.disclaimer')}}">Disclaimer</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <p class="copy-right mb-0">
                            Total Comfort &copy; 2021-2022
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>

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
            /* Swal.fire({
                icon: type,
                title: title,
                text: body,
                confirmButtonColor: '#c10909',
                timer: 5000
            }) */

			const Toast = Swal.mixin({
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
			});
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

    @yield('script')
</body>


</html>
