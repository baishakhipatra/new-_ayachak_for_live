@extends('layouts.app')

@section('page', 'FAQ')

@section('content')
<style>
    .news_list {
        display: flex;
        flex-wrap: wrap;
    }
    .news_list li {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 50%;
        flex: 0 0 50%;
        max-width: 50%;
        padding: 0 15px;
        margin-bottom: 30px;
    }
    .news_list li:nth-child(2n -1) {
        border-right: 1px solid #eee;
    }
    .news_list li h4 {
        margin-bottom: 10px;
    }
    .news_list li h4 a:hover {
        color: #141b4b;
        box-shadow: inset 0 -1px 0 0 #c10909;
    }
    .news_list li figure {
        padding-bottom: 56%;
        width: 100%;
        position: relative;
    }
    .news_list li figure img {
        position: absolute;
        top: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .news_meta {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    .news_date {
        display: block;
        background: #c10909;
        color: #fff;
        padding: 0 5px;
        height: 20px;
        line-height: 20px;
        font-weight: 500;
        margin-right: 10px;
    }
    .news_magazine {
        font-weight: 500;
    }
    @media(max-width: 575px) {
        .news_list li {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 100%;
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
</style>

<section class="cart-header mb-3 mb-sm-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h4>All Blogs</h4>
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
                            <!-- <li><a href="{{route('front.content.about')}}">About</a></li> -->
                            <li class="{{ request()->is('corporate') ? 'active' : '' }}"><a href="{{route('front.content.corporate')}}">Corporate</a></li>
                            <li><a href="{{route('front.content.news')}}">News</a></li>
                            <li class="{{ request()->is('blog*') ? 'active' : '' }}"><a href="{{route('front.content.blog')}}">Blogs</a></li>
                            <li><a href="{{route('front.content.global')}}">Global</a></li>
                            <li><a href="{{route('front.content.contact')}}">Contact</a></li>
                            <!-- <li><a href="{{route('front.content.career')}}">Career</a></li> -->
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="col-sm-9 col-lg-9">
                <div class="row">
                    <ul class="news_list">
                        @php
                            $blogs = \DB::table('blogs')->orderBy('position', 'asc')->orderBy('id', 'asc')->get();
                        @endphp

                        @foreach ($blogs as $singleBlog)
                        <li>
                            @if($singleBlog->image)
                            <a href="{{route('front.content.blog.detail', $singleBlog->slug)}}">
                                <figure>
                                    <img src="{{asset($singleBlog->image)}}">
                                </figure>
                            </a>
                            @endif
                            <figcaption>
                                <div class="news_meta">
                                    <div class="news_date">{{date('d F Y', strtotime($singleBlog->created_at))}}</div><div class="news_magazine">STAY ONN</div>
                                </div>
                                <h4><a href="{{route('front.content.blog.detail', $singleBlog->slug)}}">{{$singleBlog->title}}</a></h4>
                                <div class="news_comments">0 Comments</div>
                            </figcaption>
                        </li>
                        @endforeach

                        {{-- <li>
                            <a href="https://onninternational.com/blog/detail">
                                <figure>
                                    <img src="{{asset('/img/blog1-1-850x370.jpg')}}">
                                </figure>
                            </a>
                            <figcaption>
                                <div class="news_meta">
                                    <div class="news_date">30 March 2017</div><div class="news_magazine">STAY ONN</div>
                                </div>
                                <h4><a href="https://onninternational.com/blog/detail">BE CASUAL, BE YOU - CASUALZ ONN PREMIUM WEAR IN INDIA</a></h4>
                                <div class="news_comments">0 Comments</div>
                            </figcaption>
                        </li>
                        <li>
                            <a href="https://onninternational.com/blog/detail">
                                <figure>
                                    <img src="{{asset('/img/blog_02-1-585x370.jpg')}}">
                                </figure>
                            </a>
                            <figcaption>
                                <div class="news_meta">
                                    <div class="news_date">17 October 2017</div><div class="news_magazine">STAY ONN</div>
                                </div>
                                <h4><a href="#">BE BOLD…INSIDE…OUT</a></h4>
                                <div class="news_comments">0 Comments</div>
                            </figcaption>
                        </li>
                        <li>
                            <figcaption>
                                <div class="news_meta">
                                    <div class="news_date">13 January 2018</div><div class="news_magazine">STAY ONN</div>
                                </div>
                                <h4><a href="https://onninternational.com/blog/detail">HOW THREE KOLKATA INNERWEAR MAKERS ARE TRYING TO MOVE UP THE VALUE CHAIN</a></h4>
                                <div class="news_comments">0 Comments</div>
                            </figcaption>
                        </li> --}}
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
