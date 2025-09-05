@extends('layouts.app')

@section('page', 'FAQ')

@section('content')

<style type="text/css">
    .cms_context ul.global_list {
        margin: 0 0 20px;
        padding: 0;
        list-style-type: none;
        column-gap: 20px;
        column-count: 4;
    }
    .global_list li {
        color: #333333;
        font-weight: 500;
        padding-left: 30px;
        background: url('{{asset('/img/map_global_pin.png')}}') left center no-repeat;
        background-size: 24px auto;
        line-height: 30px;
    }
    a.global {
        color: #c10909;
    }
    .management_profile h4 {
        margin-bottom: 5px;
    }
    .management_profile content {
        display: none;
    }
    .management_more {
        color: #c10909;
        font-size: 14px;
        font-weight: 500;  
    }
    @media(max-width:  1024px) {
        .cms_context ul.global_list {
            column-count: 3;
        }
    }
    @media(max-width:  575px) {
        .cms_context ul.global_list {
            column-count: 2;
        }
    }
</style>
<section class="cart-header mb-3 mb-sm-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h4>Corporate</h4>
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
                    <h5>Our Company</h5>
                    <p>ONN is the Premium Innerwear/Athleisure Brand from the house of Lux Industries Limited. Specially crafted for the young, energetic, and suave generation, the ONN range of premium wear brings luxury into casual fashion and effectively touches the style nerve of the modern Indian male. Made in superior quality imported textile machines, all our garments are comfortable, durable, and undergo several quality checks before going out into the market. Eclectic, congenial, and contemporary – ONN offers a vast range of designs, high-end quality, and a unique concoction of modernity and versatility in all its garments to meet the standards and inclination of today’s youth.</p>

                    {{--<img src="{{asset('/img/global_presence.svg')}}" class="mb-5 img-fluid">--}}

                    <h5>Management Profile</h5>

                    <div class="row justify-content-between mb-5">
                    	<div class="col-sm-5 mb-3 management_profile">
                            <figure>
                                <img src="{{asset('/img/ashok-todi.jpg')}}" class="img-fluid">    
                            </figure>
                    		
                            <figcaption>
                                <h4>Mr. Ashok Kumar Todi</h4>
                                <p><strong>Chairman, Lux Industries Limited (LIL)</strong></p>
                                <p>“Our collaborative efforts will enable us to continue pushing the limits of technology and incite us to develop new products and get closer to our consumer.”</p>

                                <content>
                                    <p>The daily affairs of Lux Industries Limited are run and managed by Mr. Ashok Todi. He is the driving force behind the company’s exponential growth and is responsible for formulating policies for the growth and expansion of all the brands under LIL. Mr. Ashok Todi also looks after the marketing, advertising, and sales functions, including the network and distribution of LIL. Hailing from a family associated with the hosiery business for over 5 decades, Mr. Ashok Todi has vast experience and knowledge about the hosiery market and has pulled the entire organisation towards success. Mr. Ashok Todi is acknowledged in the hosiery industry for his vision and commitment and has managed to establish the organisation as one of the leaders in the hosiery industry.</p>
                                </content>
                                <a href="javascript:void(0)" class="management_more">Read more</a>
                            </figcaption>
                    	</div>
                        <div class="col-sm-5 mb-3 management_profile">
                            <figure>
                                <img src="{{asset('/img/Saket-Todi.png')}}" class="img-fluid">
                            </figure>

                            <figcaption>
                                <h4>Mr. Saket Todi</h4>
                                <p><strong>Executive Director, Lux Industries Limited</strong></p>
                                <p>“We are committed to remain flexible in the medium and the long-term to handle the challenges and deliver consistent growth in the years to come.”</p>
                                <content>
                                    <p>Mr. Saket Todi, with his eminent knowledge in manufacturing and marketing, has significantly contributed towards strengthening the premium wear brand ONN and its export market. His proficiency in product development and in-depth knowledge of marketing has helped ONN to build its brand identity, achieve greater success and increase profitability. He expanded the brand presence by focusing on quality and thereby created a loyal customer base.</p>
                                </content>
                                <a href="javascript:void(0)" class="management_more">Read more</a>
                            </figcaption>
                        </div>
                    </div>

                    <h5>Vision & Mission</h5>
                    <p>We believe in providing our customers with garments that satisfy their need for ultimate comfort and style at the same time. We understand and acknowledge that fashion is subjective and changes at a rapid pace, and aim to make products that meet the inclination of the youth. With the motto to fabricate sustainable garments that make you feel Total Comfort - ONN is consistently meeting and even surpassing the customer’s values and the expectations that they associate with our brand.</p>
                    <p>We are focusing on maintaining and strengthening the brand image in accordance with the brand identity and brand values. ONN desires to become the best youth-centric premium wear, sportswear and leisure wear brand with top-of-the-mind recall amongst the customers.</p>

                    <h5>Global Presence</h5>

                    <ul class="global_list">
                        <li>Algeria</li>
                        <li>Angola</li>
                        <li>Australia</li>
                        <li>Bahrain</li>
                        <li>Benin</li>
                        <li>Burkina Faso</li>
                        <li>Cameroon</li>
                        <li>Canada</li>
                        <li>Chad</li>
                        <li>Congo</li>
                        <li>Cote d’Ivoire</li>
                        <li>Djibouti</li>
                        <li>Ethiopia</li>
                        <li>Gabon</li>
                        <li>Gambia</li>
                        <li>Ghana</li>
                        <li>Guinea</li>
                        <li>Guinea Bissau</li>
                        <li>Hong Kong</li>
                        <li>Kenya</li>
                        <li>Kuwait</li>
                        <li>Malaysia</li>
                        <li>Mali</li>
                        <li>Mauritania</li>
                        <li>Morocco</li>
                        <li>Nepal</li>
                        <li>Niger</li>
                        <li>Nigeria</li>
                        <li>Panama</li>
                        <li>Poland</li>
                        <li>Saudi Arabia</li>
                        <li>Senegal</li>
                        <li>Singapore</li>
                        <li>South Africa</li>
                        <li>Sri Lanka</li>
                        <li>Sudan</li>
                        <li>Sultanate of Oman</li>
                        <li>Thailand</li>
                        <li>Togo</li>
                        <li>Uganda</li>
                        <li>UK</li>
                        <li>United Arab Emirates</li>
                        <li>United States of America</li>
                        <li>Yemen</li>
                        <li>Zambia</li>
                        <li>Zimbabwe</li>
                    </ul>

                    <p><a class="global" href="{{route('front.content.global')}}">Click here to know more</a></p>

                </div>
            </div>

        </div>
    </div>
</section>
@endsection
