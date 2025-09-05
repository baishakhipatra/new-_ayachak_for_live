<header>
    <div class="container">
        <div class="header-inner">
            <a href="{{route('front.home')}}" class="logo">
                <img src="{{asset('assets/images/logo.png')}}" alt="">
            </a>
            <div class="main-nav">
                <ul class="menu">
                    <li><a href="{{route('front.home')}}">Home</a></li>

                    <li class="">
                        <a href="javascript.void(0)" class="dropdown dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">Shop</a>
                            <ul class="dropdown-menu sub-menu">
                                 <li><a href="{{route('front.shop.list')}}">All Products</a></li>
                                {{-- <li><a href="{{ route('front.shop.list', ['category' => 'Book']) }}">Books</a></li>
                                <li><a href="{{ route('front.shop.list', ['category' => 'Medicine']) }}">Medicines</a></li>
                                <li><a href="{{ route('front.shop.list', ['category' => 'water']) }}">Water</a></li>
                                <li><a href="{{ route('front.shop.list', ['category' => 'photo-frame']) }}">Photo Frame</a></li> --}}
                                @foreach($categories as $category)
                                    <li>
                                        <a href="{{ route('front.shop.list', ['category' => Str::slug($category->name)]) }}">
                                            {{ $category->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                    </li>
                    <li><a href="{{route('front.event.index')}}">Events</a></li>
                    <li><a href="{{route('front.about-us.index')}}">About Us</a></li>
                    
                </ul>
            </div>
            <a href="{{route('front.donation.form')}}" class="bton btn-fill">Donate Now</a>
            <div class="icon-place">
                <a href="#" class="search">
                    <img src="{{asset('assets/images/search.svg')}}">
                </a>
                {{--                 
                <a href="{{ route('front.user.login') }}" class="account">
                    <img src="./assets/images/user.svg">
                </a> --}}
                <a href="
                    @if(Auth::guard('web')->check())
                        {{ route('front.profile') }}
                    @else
                        {{ route('front.login') }}
                    @endif
                    " class="account">
                    <img src="{{ asset('assets/images/user.svg') }}" alt="User">
                </a>

                <a href="{{route('front.cart.index')}}" class="cart">
                    <img src="{{asset('assets/images/bag.svg')}}">
                    <span>{{ $cartCount }}</span>
                </a>
            </div>
            <div class="ham">
                <img src="{{asset('assets/images/menu.svg')}}">
            </div>
        </div>
    </div>
    <div class="offcanvas-menu">
        <div class="canvas-header">
            <a href="index.html" class="logo">
                <img src="{{asset('assets/images/logo.png')}}" alt="">
            </a>

            <a href="#" class="cross">
                <img src="{{asset('assets/images/cross.svg')}}">
            </a>
        </div>
        <div class="menu-holder">
            <ul class="menu">
                <li><a href="{{route('front.home')}}">Home</a></li>
                    <li class="">
                        <a href="javascript.void(0)" class="dropdown dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">Shop</a>
                            <ul class="dropdown-menu sub-menu">
                                 <li><a href="{{route('front.shop.list')}}">All Products</a></li>
                                <li><a href="{{ route('front.shop.list', ['category' => 'Book']) }}">Books</a></li>
                                <li><a href="{{ route('front.shop.list', ['category' => 'Medicine']) }}">Medicines</a></li>
                                <li><a href="{{ route('front.shop.list', ['category' => 'water']) }}">Water</a></li>
                                <li><a href="{{ route('front.shop.list', ['category' => 'photo-frame']) }}">Photo Frame</a></li>
                            </ul>
                    </li>
                <li><a href="{{route('front.event.index')}}">Events</a></li>
                <li><a href="{{route('front.about-us.index')}}">About Us</a></li>
            </ul>
            <a href="#" class="bton btn-fill">Donate Now</a>
        </div>
    </div>
</header>
<form id="logout-form" action="{{ route('front.logout') }}" method="POST" style="display: none;">
    @csrf
</form>