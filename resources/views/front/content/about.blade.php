@extends('layouts.app')

@section('page', 'FAQ')

@section('content')
<style type="text/css">
    .account-card {
        height: auto;
    }
    .about_header {
        padding: 150px 0 50px;
    }
    .about_header h1 {
        margin: 0;
    }
    .about_header p {
        font-size: 14px;
        font-weight: 500;
        line-height: 1.6;
        margin: 0;
        padding-top: 20px;
        margin-top: 20px;
        border-top: 1px solid #eee;
        color: #111;
    }
    .about_count {
        font-size: 60px;
        font-weight: 600;
        color: #c10909;
    }
    .about_count_content {
        font-size: 18px;
        font-weight: 500;
    }
    .about_badge {
        display: inline-block;
        vertical-align: top;
        padding: 3px 15px;
        background: #c10909;
        color: #fff;
        font-weight: 700;
        border-radius: 30px;
        text-transform: uppercase;   
    }
    .about_banner figure img {
        width: 100%;
        height: 400px;
        object-fit: cover;
    }
    .about_excerpt {
        padding: 50px 0;
    }
    .about_excerpt p {
        font-size: 14px;
        font-weight: 400;
        line-height: 1.6;
        color: #111;
    }
    .about_excerpt h5 {
        margin-bottom: 10px;
    }
    .global_reach_list {
        columns: 4;
        column-gap: 30px;
    }
    .global_reach_list li {
        width: 100%;
        font-size: 14px;
        font-weight: 400;
        margin-bottom: 10px;
    }
    .about_services {
        padding: 50px 0 0;
        background: #f7f7f7;
    }
    .about_services h2 {
        font-size: 38px;
        line-height: 1.4;
        margin-bottom: 50px;
    }
    .about_services_item {
        padding-bottom: 30px;
        margin-bottom: 30px;
        border-bottom: 1px solid #ccc;
    }
    .about_services h5 {
        margin-bottom: 20px;
        font-weight: 500;
    }
    .about_services p {
        font-size: 14px;
        font-weight: 400;
        line-height: 1.6;
        color: #111;
        margin: 0;
    }
    .about_services_item:last-child {
        margin: 0;
        border: none;
    }
    .about_services figure {
        width: 100%;
        padding-bottom: 95%;
        position: relative;
        margin: 0;
    }
    .about_services figure img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .about_banner figure {
        position: relative;
        overflow: hidden;
    }
    .about_banner .stroke_text {
        position: absolute;
        bottom: -30px;
        left: 0;
        -webkit-text-stroke-width: 3px;
        -webkit-text-stroke-color: #fff;
        color: transparent;
        z-index: 9;
        font-size: 200px;
        font-weight: 900;
        line-height: 1;
        white-space: nowrap;
    }
    .about_gallery {
        display: block;
        padding: 50px 0;
    }
    .about_gallery h2 {
        font-size: 38px;
        line-height: 1.4;
        margin-bottom: 50px;
    }
    .about_gallery img {
        max-width: 100%;
    }
    .media_popop {
        display: inline-block;
        vertical-align: top;
        line-height: 0;
        position: relative;
    }
    .media_popop span {
        position: absolute;
        display: inline-block;
        vertical-align: top;
        padding: 8px;
        border-radius: 50%;
        background-color: rgba(0,0,0,0.3);
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9;
    }
</style>

<section class="about_header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="about_badge">
                    About Us
                </div>
                <h1>Business<br/>with differences approach.</h1>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="about_count">30</div>
                        <div class="about_count_content">Years<br/>Experience</div>
                    </div>
                    <div class="col-sm-4">
                        <div class="about_count">20k+</div>
                        <div class="about_count_content">Usual<br/>Users</div>
                    </div>
                    <div class="col-sm-4">
                        <div class="about_count">98%</div>
                        <div class="about_count_content">Positive<br/>Feedback</div>
                    </div>
                </div>
                <p>ONN is the new Premium Inners/Men’s Underwear Brand from the house of Lux Industries Limited. Crafted with the latest technology, the ONN range of Onn Premium wear effectively touches the style nerve of the fashionable Indian male.</p>
            </div>
        </div>
    </div>
</section>

<section class="about_banner p-0">
    <div class="container pr-0">
        <figure>
            <div class="stroke_text">onninternational</div>
            <img src="{{asset('/img/aboutpage_banner.jpg')}}">
        </figure>
    </div>
</section>

<section class="about_excerpt">
    <div class="container">
        <div class="row">
            <div class="col-sm-10 offset-sm-1">
            <p>A vast range of designs, top notch quality and sensible styling has been presented to meet the standards and aspirations of today’s youth. In addition, the very fact that it is actively endorsed by the great fashion icon – Shahrukh Khan – adds yet another feather to its cap.</p>

            <h5>Vision & Mission</h5>
            <p>We believe in providing our customers with the products to satisfy their needs of ultimate comfort and style at the same time. As the fashion is changing at rapid pace, we aim to make the products meet the desires and aspirations of the youth. We believe in consistently meeting and even surpassing the customer’s values and the expectations that they associate with our brand.</p>

            <p>We are focusing on maintaining and strengthening the brand image in accordance with the brand identity and brand values. We desire to become the best youthful Onn Premium wear-sportswear and leisure wear brand with top of the mind recall.</p>
        </div>
    </div>
</section>

<section class="about_services">
    <div class="container">
        <div class="row">
            <div class="col-sm-5 offset-sm-1">
                <h2>Experience and trust built over the years</h2>
            </div>
        </div>
    </div>

    <div class="container pl-0">
        <div class="row align-items-center">
            <div class="col-sm-5">
                <figure>
                    <img src="{{asset('/img/onn_franchise03.jpg')}}">
                </figure>
            </div>
            <div class="col-sm-5 offset-sm-1">
                <div class="about_services_item">
                    <h5>1914 translation by H. Rackham</h5>
                    <p>A vast range of designs, top notch quality and sensible styling has been presented to meet the standards and aspirations of today’s youth.</p>
                </div>

                <div class="about_services_item">
                    <h5>Vision & Mission</h5>
                    <p>We believe in providing our customers with the products to satisfy their needs of ultimate comfort and style at the same time. As the fashion is changing at rapid pace, we aim to make the products meet the desires and aspirations of the youth.</p>
                </div>

                <div class="about_services_item">
                    <h5>1914 translation by H. Rackham</h5>
                    <p>We are focusing on maintaining and strengthening the brand image in accordance with the brand identity and brand values.</p>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="about_gallery">
    <div class="container">
        <div class="row">
            <div class="col-sm-5">
                <h2>Our Medias and News</h2>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-sm-3">
                <a data-fancybox class="media_popop" href="https://www.youtube.com/watch?v=TEPsi1ha2Co">
                    <img src="{{asset('/img/brand_commercial02.png')}}">
                    <span><svg xmlns="http://www.w3.org/2000/svg" width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-play-circle"><circle cx="12" cy="12" r="10"></circle><polygon points="10 8 16 12 10 16 10 8"></polygon></svg></span>
                </a>
            </div>
            <div class="col-sm-3">
                <a data-fancybox class="media_popop" href="https://www.youtube.com/watch?v=zHHTcUV8xW8">
                    <img src="{{asset('/img/LUX-PAGE_ONN.jpg')}}">
                    <span><svg xmlns="http://www.w3.org/2000/svg" width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-play-circle"><circle cx="12" cy="12" r="10"></circle><polygon points="10 8 16 12 10 16 10 8"></polygon></svg></span>
                </a>
            </div>
            <div class="col-sm-3">
                <a data-fancybox class="media_popop" href="https://www.youtube.com/watch?v=foQQBjS1qvU">
                    <img src="{{asset('/img/onn_gallery3.png')}}">
                    <span><svg xmlns="http://www.w3.org/2000/svg" width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-play-circle"><circle cx="12" cy="12" r="10"></circle><polygon points="10 8 16 12 10 16 10 8"></polygon></svg></span>
                </a>
            </div>
            <div class="col-sm-3">
                <a data-fancybox class="media_popop" href="https://www.youtube.com/watch?v=l5tEOJxw92Q">
                    <img src="{{asset('/img/onn_gallery4.png')}}">
                    <span><svg xmlns="http://www.w3.org/2000/svg" width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-play-circle"><circle cx="12" cy="12" r="10"></circle><polygon points="10 8 16 12 10 16 10 8"></polygon></svg></span>
                </a>
            </div>
        </div>
    </div>
</section>


<section class="about_services">
    <div class="container">
        <div class="row">
            <div class="col-sm-5">
                <h2>Experience and trust built over the years</h2>
            </div>
        </div>
    </div>

    <div class="container pr-0">
        <div class="row align-items-center">
            <div class="col-sm-5 offset-sm-1">
                <div class="about_services_item">
                    <h5>1914 translation by H. Rackham</h5>
                    <p>A vast range of designs, top notch quality and sensible styling has been presented to meet the standards and aspirations of today’s youth.</p>
                </div>

                <div class="about_services_item">
                    <h5>Vision & Mission</h5>
                    <p>We believe in providing our customers with the products to satisfy their needs of ultimate comfort and style at the same time. As the fashion is changing at rapid pace, we aim to make the products meet the desires and aspirations of the youth.</p>
                </div>

                <div class="about_services_item">
                    <h5>1914 translation by H. Rackham</h5>
                    <p>We are focusing on maintaining and strengthening the brand image in accordance with the brand identity and brand values.</p>
                </div>
            </div>
            <div class="col-sm-5 offset-sm-1">
                <figure>
                    <img src="{{asset('/img/onn_franchise03.jpg')}}">
                </figure>
            </div>
        </div>
    </div>
</section>

<!-- <section class="global_reach">
    <div class="container">
        <h2>Our Global Reach</h2>
        <ul class="global_reach_list">
            <li><a href="javaScript:void(0)">Algeria</a></li>
            <li><a href="javaScript:void(0)">Angola</a></li>
            <li><a href="javaScript:void(0)">Australia</a></li>
            <li><a href="javaScript:void(0)">Bahrain</a></li>
            <li><a href="javaScript:void(0)">Benin</a></li>
            <li><a href="javaScript:void(0)">Burkina Faso</a></li>
            <li><a href="javaScript:void(0)">Cameroon</a></li>
            <li><a href="javaScript:void(0)">Canada</a></li>
            <li><a href="javaScript:void(0)">Chad</a></li>
            <li><a href="javaScript:void(0)">Congo</a></li>
            <li><a href="javaScript:void(0)">Cote d’Ivoire</a></li>
            <li><a href="javaScript:void(0)">Djibouti</a></li>
            <li><a href="javaScript:void(0)">Ethiopia</a></li>
            <li><a href="javaScript:void(0)">Gabon</a></li>
            <li><a href="javaScript:void(0)">Gambia</a></li>
            <li><a href="javaScript:void(0)">Ghana</a></li>
            <li><a href="javaScript:void(0)">Guinea</a></li>
            <li><a href="javaScript:void(0)">Guinea Bissau</a></li>
            <li><a href="javaScript:void(0)">Hong Kong</a></li>
            <li><a href="javaScript:void(0)">Kenya</a></li>
            <li><a href="javaScript:void(0)">Kuwait</a></li>
            <li><a href="javaScript:void(0)">Malaysia</a></li>
            <li><a href="javaScript:void(0)">Mali</a></li>
            <li><a href="javaScript:void(0)">Mauritania</a></li>
            <li><a href="javaScript:void(0)">Morocco</a></li>
            <li><a href="javaScript:void(0)">Nepal</a></li>
            <li><a href="javaScript:void(0)">Niger</a></li>
            <li><a href="javaScript:void(0)">Nigeria</a></li>
            <li><a href="javaScript:void(0)">Panama</a></li>
            <li><a href="javaScript:void(0)">Poland</a></li>
            <li><a href="javaScript:void(0)">Saudi Arabia</a></li>
            <li><a href="javaScript:void(0)">Senegal</a></li>
            <li><a href="javaScript:void(0)">Singapore</a></li>
            <li><a href="javaScript:void(0)">South Africa</a></li>
            <li><a href="javaScript:void(0)">Sri Lanka</a></li>
            <li><a href="javaScript:void(0)">Sudan</a></li>
            <li><a href="javaScript:void(0)">Sultanate of Oman</a></li>
            <li><a href="javaScript:void(0)">Thailand</a></li>
            <li><a href="javaScript:void(0)">Togo</a></li>
            <li><a href="javaScript:void(0)">Uganda</a></li>
            <li><a href="javaScript:void(0)">UK</a></li>
            <li><a href="javaScript:void(0)">United Arab Emirates</a></li>
            <li><a href="javaScript:void(0)">United States of America</a></li>
            <li><a href="javaScript:void(0)">Yemen</a></li>
            <li><a href="javaScript:void(0)">Zambia</a></li>
            <li><a href="javaScript:void(0)">Zimbabwe</a></li>
        </ul>
    </div>
</section>
 -->

<!-- <section class="cart-header mb-3 mb-sm-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h4>About us</h4>
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
                            <li><a href="{{route('front.content.about')}}">About</a></li>
                            <li><a href="{{route('front.content.corporate')}}">Corporate</a></li>
                            <li><a href="{{route('front.content.news')}}">News</a></li>
                            <li><a href="{{route('front.content.blog')}}">Blogs</a></li>
                            <li><a href="{{route('front.content.global')}}">Global</a></li>
                            <li><a href="{{route('front.content.contact')}}">Contact</a></li>
                            <li><a href="{{route('front.content.career')}}">Career</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="col-sm-7 col-lg-7">
                <div class="row">
                    <div class="col-sm-12 col-lg-6">
                        <a href="{{route('front.content.corporate')}}" class="account-card">
                            <figure>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-coffee"><path d="M18 8h1a4 4 0 0 1 0 8h-1"></path><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path><line x1="6" y1="1" x2="6" y2="4"></line><line x1="10" y1="1" x2="10" y2="4"></line><line x1="14" y1="1" x2="14" y2="4"></line></svg>
                                </span>
                            </figure>
                            <figcaption>
                                <h4>Corporate</h4>
                            </figcaption>
                        </a>
                    </div>
                    <div class="col-sm-12 col-lg-6">
                        <a href="{{route('front.content.news')}}" class="account-card">
                            <figure>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                </span>
                            </figure>
                            <figcaption>
                                <h4>News</h4>
                            </figcaption>
                        </a>
                    </div>
                    <div class="col-sm-12 col-lg-6">
                        <a href="{{route('front.content.career')}}" class="account-card">
                            <figure>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-briefcase"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                                </span>
                            </figure>
                            <figcaption>
                                <h4>Career</h4>
                            </figcaption>
                        </a>
                    </div>
                    <div class="col-sm-12 col-lg-6">
                        <a href="{{route('front.content.global')}}" class="account-card">
                            <figure>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                                </span>
                            </figure>
                            <figcaption>
                                <h4>Global</h4>
                            </figcaption>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section> -->

@endsection
