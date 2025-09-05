@extends('layouts.app')

@section('page', 'FAQ')

@section('content')

<style type="text/css">
    .cms_context h1 {
        font-size: 30px;
        line-height: 1.5;
    }
    .cms_context figure {
        margin: 30px 0;
    }
    .cms_context figure img {
        max-width: 100%;
        width: 100%;
    }
    .news_meta {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    .news_date {
        display: flex;
        align-items: center;
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
    .news_list {
        display: flex;
        flex-wrap: wrap;
        width: 100%;
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
    .news_meta {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    .news_comments {
        display: flex;
        align-items: center;
        font-weight: 500;
        color: #fff;
    }
    .news_meta svg {
        margin-right: 5px;
        width: 20px;
        height: auto;
    }

    @media(max-width: 575px) {
        .news_list li {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 100%;
            flex: 0 0 100%;
            max-width: 100%;
        }
    }

    .news_blog {
        display: -ms-grid;
        display: grid;
        -ms-grid-columns: 1fr 1fr 1fr 1fr;
        grid-template-columns: 1fr 1fr 1fr 1fr;
        -ms-grid-rows: 240px 240px;
        grid-template-rows: 240px 240px;
        gap: 20px 20px;
        grid-auto-flow: row;
        grid-template-areas:
            "top_left top_left top_right top_right"
            "top_left top_left bottom_right bottom_right";
        width: 100%;
        height: 100%;
        grid-column-gap: 30px;
        grid-row-gap: 30px;
        margin-bottom: 30px;
    }
    .news_grid {
        display: flex;
        height: 100%;
        flex-direction: column;
        position: relative;
        overflow: hidden;
    }
    .news_grid figure {
        display: flex;
        height: 100%;
    }
    .news_grid figure img {
        transition: all ease-in-out 0.5s;
    }
    .news_grid:hover figure img {
        transform: scale(1.2);
    }
    .news_grid figcaption {
        /* Permalink - use to edit and share this gradient: https://colorzilla.com/gradient-editor/#000000+0,000000+100&0+0,0.65+100 */
        background: -moz-linear-gradient(top,  rgba(0,0,0,0) 0%, rgba(0,0,0,1) 100%); /* FF3.6-15 */
        background: -webkit-linear-gradient(top,  rgba(0,0,0,0) 0%,rgba(0,0,0,1) 100%); /* Chrome10-25,Safari5.1-6 */
        background: linear-gradient(to bottom,  rgba(0,0,0,0) 0%,rgba(0,0,0,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00000000', endColorstr='#a6000000',GradientType=0 ); /* IE6-9 */
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 30px;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    .news_grid figcaption h4 {
        margin-bottom: 10px;
    }
    .news_grid figcaption h4 a {
        color: #fff;
    }
    .news_grid img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .news_grid:nth-child(1) {
        grid-area: top_left;
    }
    .news_grid:nth-child(2) {
        grid-area: top_right;
    }
    .news_grid:nth-child(3) {
        grid-area: bottom_right;
    }
    .news_block {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
    }
    .news_block figure {
        width: 35%;
        max-width: 35%;
        flex: 0 0 35%;
        padding-bottom: 26%;
        display: block;
        position: relative;
        margin: 0 30px 0 0;
        overflow: hidden;
    }
    .news_block figure img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0;
        left: 0;
        transition: all ease-in-out 0.5s;
    }
    .news_block:hover figure img {
        transform: scale(1.2);
    }
    .news_block figcaption {
        flex: 1 0 0%;
    }
    .news_block figcaption h4 {
        margin-bottom: 10px;
    }
    .news_block figcaption p {
        font-size: 14px;
        font-weight: 500;
        color: #888;
        margin: 0 0 15px;
        line-height: 1.4;
    }
    .news_block .news_date, .news_block .news_comments {
        color: #000;
    }
    .news_card {
        margin-bottom: 30px;
    }
    .news_card figure {
        width: 100%;
        overflow: hidden;
        position: relative;
        padding-bottom: 50%;
    }
    .news_card figure .news_meta {
        position: absolute;
        bottom: 10px;
        left: 10px;
        z-index: 9;
        margin: 0;
    }
    .news_card figure img {
        transition: all ease-in-out 0.5s;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .news_card:hover figure img {
        transform: scale(1.2);
    }
    .news_card figcaption h4 {
        font-size: 14px;
        line-height: 1.4;
        margin-bottom: 10px;
    }
    .news_card .news_date, .news_card .news_comments {
        color: #000;
    }
    .blognav-list {
      margin: 20px 0;
      padding: 0;
      list-style-type: none;
    }

    @media(max-width: 575.98px) {
      .blognav-list {
        border-right: none;
      }
    }

    .blognav-list li {
      display: inline-block;
      width: 100%;
      padding: 20px 0;
      border-bottom: 1px solid #eee;
    }

    .blognav-list li a {
      display: block;
      font-size: 14px;
      font-weight: 500;
    }

    .blognav-list li.active > a {
      font-weight: 500;
      color: #c10909;
    }

    .blognav-list li a:hover {
        color: #c10909;
    }

    .blognav-list li span {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      color: #c10909;
      font-weight: 500;
      text-transform: uppercase;
      font-size: 15px;
      background: url(../img/account-down.svg) center right 10px no-repeat;
      cursor: pointer;
    }

    .blognav-list li.active ul {
      display: block;
    }

    @media(max-width: 575.98px) {
      .blognav-list li span + ul {
        display: none;
      }
    }

    .blognav-list li ul {
      margin: 20px 0 0 0;
    }

    .blognav-list li ul li {
      padding: 0;
      border: none;
      margin-top: 10px;
    }

    .blognav-list li ul li a {
      font-size: 14px;
    }
</style>
<section class="cart-header"></section>
<section class="cart-wrapper mb-3 mb-sm-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-9">
                <div class="cms_context">
                    <h1>{{$data->title}}</h1>
                    <div class="news_meta">
                        <div class="news_date"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> {{ date('d F Y', strtotime($data->created_at)) }}</div><div class="news_magazine"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>0 Comment</div>
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
            <div class="col-sm-3">
                <ul class="blognav-list mt-0">
                    <li>
                        <span><strong>Recent Blogs</strong></span>
                        <ul>
                            <li><a href="#">Be Casual, Be you – Casualz ONN Premium Wear in India</a></li>
                            <li><a href="#">Be Bold…Inside…Out</a></li>
                            <li><a href="#">Lux to foray into casual, active wear, eyes Rs 2000 crore sales by 2020</a></li>
                            <li><a href="#">Lux Industries banks on premium brand ONN to boost bottomline</a></li>
                            <li><a href="#">How three Kolkata innerwear makers are trying to move up the value chain</a></li>
                        </ul>
                    </li>
                    <li>
                        <span><strong>Blogs Archive</strong></span>
                        <ul>
                            <li><a href="#">February 2018</a></li>
                            <li><a href="#">January 2018</a></li>
                            <li><a href="#">February 2016</a></li>
                            <li><a href="#">December 2015</a></li>
                            <li><a href="#">May 2014</a></li>
                            <li><a href="#">April 2014</a></li>
                        </ul>
                    </li>
                    <li>
                        <span><strong>Blogs Categories</strong></span>
                        <ul>
                            <li><a href="#">CSR Initiatives</a></li>
                            <li><a href="#">EXCLUSIVE SRK</a></li>
                            <li><a href="#">ONN THERMAL</a></li>
                            <li><a href="#">ONN WARDROBE</a></li>
                            <li><a href="#">STAY ONN</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>

        <hr/>

            <div class="row justify-content-center">
                <div class="col-12 mb-4">
                    <h4>Related News</h4>
                </div>
            </div>
            <div class="row justify-content-center">
                @php
                    $news = \DB::table('news')->latest('id')->get();
                @endphp
                @foreach ($news as $singleNews)
                <div class="col-sm-4">
                    <div class="news_card">
                        <figure>
                            <img src="{{asset($singleNews->image)}}">
                            <div class="news_meta">
                                <div class="news_magazine">{{$singleNews->link_text}}</div>
                            </div>
                        </figure>
                        <figcaption>
                            <h4><a href="{{ route('front.content.news.detail', $singleNews->slug) }}">{{$singleNews->title}}</a></h4>
                            <div class="news_meta">
                                <div class="news_date"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>{{date('d F Y', strtotime($singleNews->created_at))}}</div>
                                <div class="news_comments"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>0 Comments</div>
                            </div>
                        </figcaption>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection
