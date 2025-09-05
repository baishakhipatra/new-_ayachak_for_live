@extends('layouts.app')

@section('page', 'FAQ')

@section('content')

<style type="text/css">
    .cms_context h1 {
        font-size: 30px;
        line-height: 1.5;
    }
    .cms_context figure img {
        max-width: 100%;
    }
    .news_meta {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    .news_date {
        display: block;
        background: #f0f0f0;
        color: #898989;
        padding: 0 5px;
        height: 20px;
        line-height: 20px;
        font-weight: 500;
        margin-right: 10px;
    }
    .news_magazine {
        font-weight: 500;
        color: #898989;
    }
</style>

<section class="cart-header mb-3 mb-sm-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h4>Single News</h4>
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
                            <li><a href="{{route('front.content.corporate')}}">Corporate</a></li>
                            <li class="{{ request()->is('news*') ? 'active' : '' }}"><a href="{{route('front.content.news')}}">News</a></li>
                            <li><a href="{{route('front.content.blog')}}">Blogs</a></li>
                            <li><a href="{{route('front.content.global')}}">Global</a></li>
                            <li><a href="{{route('front.content.contact')}}">Contact</a></li>
                            <!-- <li><a href="{{route('front.content.career')}}">Career</a></li> -->
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="col-sm-9">
                <div class="cms_context">
                    <h1>{{$data->title}}</h1>
                    <div class="news_meta">
                        <div class="news_date">{{ date('d F Y', strtotime($data->created_at)) }}</div><div class="news_magazine">0 Comment</div>
                    </div>

                    <figure>
                        <img src="{{asset($data->image)}}">
                    </figure>

                    <figcaption>
                        <p>{{$data->link_text}}</p>
                        <p>Date: {{ date('d F Y', strtotime($data->created_at)) }}</p>
                        <p><a href="{{$data->link}}" target="_blank">Read More</a></p>
                    </figcaption>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
