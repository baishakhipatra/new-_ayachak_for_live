<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ONN Total Comfort | @yield('page')</title>

    <link rel="icon" href="{{asset('img/favicon.png')}}" type="image/png" sizes="16x16">
    <link rel="stylesheet" href="{{ asset('css/plugin.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/swiper@8.0.7/swiper-bundle.min.css">
    <link rel="stylesheet" href="{{ asset('node_modules/select2/dist/css/select2.min.css') }}">
    <link rel='stylesheet' href='{{ asset('node_modules/lightbox2/dist/css/lightbox.min.css?ver=5.8.2') }}'>
    <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
    <link rel="stylesheet" href="{{ asset('scss/css/preload.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
    <link rel="stylesheet" href="{{ asset('css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="search_wrap">
        <a href="javascript:void(0)" class="search_close">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </a>
        <form class="search_form" method="GET" action="{{route('front.search.index')}}">
            <input type="search" name="query" class="search_box" placeholder="Search Product Here..">
            <button type="submit" class="search_btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </button>
        </form>
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
                            <li>
                                <a href="{{route('front.sale.index')}}">Sale</a>
                            </li>
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
                                <a href="{{route('front.user.login')}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="{{ (Auth::guard('web')->check()) ? '#c10909' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                </a>
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
            <i class="fal fa-times"></i>
        </div>
        <div class="menu__row">
            @foreach ($collections as $collectionKey => $collectionValue)
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
            @endforeach
        </div>
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
                    <div class="col-md-5 col-lg-5 mb-3 mb-md-0">
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
                    <div class="col-md-7 col-lg-6 offset-lg-1">
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="footer-block">
                                    <div class="footer-heading">Quick Links</div>
                                    <ul class="footer-block-menu">
                                        @foreach ($categories as $categoryIndex => $categoryValue)
                                            <li><a href="{{route('front.category.detail', $categoryValue->slug)}}">{{$categoryValue->name}}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="footer-block">
                                    <div class="footer-heading">Customer Services</div>
                                    <ul class="footer-block-menu">
                                        <li><a href="{{route('front.faq.index')}}">FAQ</a></li>
                                        <li><a href="{{route('front.user.order')}}">My Shopping</a></li>
                                        <li><a href="{{route('front.content.shipping')}}">Shipping & Delivery</a></li>
                                        <li><a href="{{route('front.content.payment')}}">Payment, Voucher & Promotions</a></li>
                                        <li><a href="{{route('front.content.return')}}">Returns Policy</a></li>
                                        <li><a href="{{route('front.content.refund')}}">Refund & Cancellation Policy</a></li>
                                        <li><a href="{{route('front.content.service')}}">Service & Contact</a></li>
                                    </ul>
                                </div>
                            </div>
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
                            <a href="{{route('front.content.security')}}">Security</a>
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

    <script src="{{ asset('node_modules/jquery/dist/jquery.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.6.0/umd/popper.min.js" integrity="sha512-BmM0/BQlqh02wuK5Gz9yrbe7VyIVwOzD1o40yi1IsTjriX/NGF37NyXHfmFzIlMmoSIBXgqDiG1VNU6kB5dBbA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('js/plugin.js') }}"></script>
    <script src="{{ asset('node_modules/bootstrap/dist/js/bootstrap.js') }}"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="{{ asset('node_modules/gsap/dist/gsap.min.js') }}"></script>
    <script src="{{ asset('node_modules/gsap/dist/ScrollTrigger.min.js') }}"></script>
    <script src="{{ asset('node_modules/waypoints/lib/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('node_modules/counterup/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('node_modules/lightbox2/dist/js/lightbox.min.js') }}"></script>
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
                timer: 5000
            })
        }

        // on session toast fires
        @if (Session::get('success'))
            toastFire('success', '{{ Session::get('success') }}');
        @elseif (Session::get('failure'))
            toastFire('danger', '{{ Session::get('failure') }}');
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
                    $('#joinUsMailResp').html($icon+result.message);
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
                    } else {
                        toastFire(result.type, result.message);
                        $('#applyCouponBtn').text('Apply');
                    }
                }
            });
        }

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
