@extends('front.layout.app')

@section('page', 'payment')

@section('content')

    <div class="search_wrap">
        <a href="javascript:void(0)" class="search_close">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="feather feather-x">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </a>
        <div class="search_area">
            <form class="search_form" method="GET" action="{{ route('front.search.index') }}">
                <input type="search" name="query" class="search_box" placeholder="Search Product Here.." autofocus>
                <button type="submit" class="search_btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-search">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
            </form>
            <div id="searchResp"></div>
        </div>
    </div>

    <div class="overlay">
        <div class="overlay__close">
            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80"
                 viewBox="0 0 24 24" fill="none" stroke="#c10909" stroke-width="0.5"
                 stroke-linecap="round" stroke-linejoin="round"
                 class="feather feather-x">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </div>

        <div class="overlay_wrapper">
            <div class="overlay_block">
                <ul class="overlay_menu">
                    <li><a href="javascript: void(0)">Shop by collection</a>
                        <ul class="overlay_submenu">
                            @foreach($collections as $collectionValue)
                                <li>
                                    <a href="{{ route('front.collection.detail', $collectionValue->slug) }}">
                                        <img class="logo_image" src="{{ asset($collectionValue->sketch_icon) }}" />
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>

                    @foreach ($categoryNavList as $categoryNavValue)
                        <li>
                            <a href="javascript: void(0)">{{ $categoryNavValue['parent'] }}</a>
                            <ul class="overlay_submenu">
                                @foreach ($categoryNavValue['child'] as $childCatValue)
                                    <li>
                                        <a href="{{ route('front.category.detail', $childCatValue['slug']) }}">
                                            <img src="{{ asset($childCatValue['sketch_icon']) }}">
                                            {{ $childCatValue['name'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('front.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

@endsection


@push('scripts')
    {{-- Put your custom page JS here --}}
    <script>
        // example toast
        @if (Session::has('success'))
            toastFire('success', '{{ Session::get('success') }}');
        @elseif (Session::has('failure'))
            toastFire('warning', '{{ Session::get('failure') }}');
        @endif
    </script>
@endpush
