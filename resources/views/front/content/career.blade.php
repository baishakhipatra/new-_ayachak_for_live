@extends('layouts.app')

@section('page', 'FAQ')

@section('content')

<style type="text/css">
    .career_header {
        padding: 150px 0 50px;
    }
    .career_header h1 {
        margin: 0;
    }
    .career_header p {
        font-size: 14px;
        font-weight: 500;
        line-height: 1.6;
        margin: 0;
        padding-top: 20px;
        margin-top: 20px;
        color: #111;
    }
    .career_badge {
        display: inline-block;
        vertical-align: top;
        padding: 3px 15px;
        background: #c10909;
        color: #fff;
        font-weight: 700;
        border-radius: 30px;
        text-transform: uppercase;   
    }
    .career_banner {
        padding: 0;
    }
    .career_banner figure img {
        width: 100%;
        height: 400px;
        object-fit: cover;
    }
    .career_banner figure {
        position: relative;
        overflow: hidden;
    }
    .career_banner .stroke_text {
        position: absolute;
        padding: 30px;
        background: #fff;
        border-top: 5px solid #c10909;
        left: auto;
        right: 100px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 9;
        max-width: 400px;
    }
    .career_banner .stroke_text h3 {
        color: #000;
        display: inline;
        line-height: 1.4;
        font-weight: 300;
        box-shadow: inset 0 -1px 0 0 rgb(0 0 0 / 15%);
    }
    .career_banner .quote_meta {
        display: block;
        margin-top: 30px;
        font-weight: 500;
    }
    .career_value {
        display: block;
        padding: 50px 0;
    }
    .career_value h2 {
        font-size: 42px;
        line-height: 1.4;
        margin: 0 0 10px;
    }
    .career_value h3 {
        font-size: 32px;
        margin-bottom: 25px;
    }
    .career_value h5 {
        font-size: 18px;
        line-height: 1.3;
        text-align: center;
        color: #111;
        font-weight: 400;
    }
    .career_value p {
        font-size: 15px;
        color: #111;
        line-height: 1.6;
        font-weight: 400;
    }
    .value_count {
        display: block;
        font-size: 60px;
        line-height: 1;
        font-weight: 900;
        color: #eee;
        margin-bottom: 15px;
    }
    .leadership_team {
        display: block;
        padding: 50px 0;
    }
    .leadership_team h2 {
        font-size: 42px;
        line-height: 1.4;
        margin: 0 0 10px;
    }
    .leadership_team h5 {
        font-size: 18px;
        line-height: 1.3;
        text-align: center;
        color: #111;
        font-weight: 400;
    }
    .leadership_team p {
        font-size: 15px;
        color: #111;
        line-height: 1.6;
        font-weight: 400;
    }
    .leadership_team figure img {
        max-width: 100%;
    }
    .member-name {
        font-size: 18px;
        color: #000;
        font-weight: 600;
        margin: 0 0 10px;
        line-height: 1.3;
    }
    .member-subtitle {
        font-size: 15px;
        color: #111;
        font-weight: 500;
        margin-bottom: 10px;
    }
    .member-description {
        font-weight: 400;
        line-height: 1.6;
        color: #111;
    }
    .job_opening {

    }
    .job_opening {
        display: block;
        padding: 50px 0;
    }
    .job_opening h2 {
        font-size: 42px;
        line-height: 1.4;
        margin: 0 0 10px;
    }
    .job_opening h5 {
        font-size: 18px;
        line-height: 1.3;
        text-align: center;
        color: #111;
        font-weight: 400;
    }
    .career_list {
        margin: 50px 0 0;
        padding: 0;
        list-style-type: none;
    }
    .career_list > li {
        display: inline-block;
        width: 100%;
        vertical-align: top;
        padding: 20px 30px;
        border-radius: 8px;
        background: #f0f0f0;
        margin-bottom: 10px;
    }
    .career_list > li h4 {
        font-size: 16px;
        font-weight: 500;
    }
    .career_list > li a {
        display: inline-block;
        padding: 8px 15px;
        border-radius: 4px;
        background: #c10909;
        color: #fff;
        font-weight: 500;
        font-size: 14px;
    }
    .career_list > li h4 span {
        font-weight: 600;
        color: #111;
    }
    .cms_context {
        padding: 20px 30px;
    }
    .cms_context h5 {
        text-align: left;
    }
    .cms_context {
        display: none;
    }
    .cms_context ul {
        padding-left: 17px;
    }
    .contact_form {
        display: block;
        padding: 50px 0;
    }
    .contact_form h2 {
        font-size: 42px;
        line-height: 1.4;
        margin: 0 0 50px;
    }
    .contact_form .form-group {
        flex-direction: column;
    }
    .contact_form .form-group label {
        font-size: 16px;
        color: #000;
        font-weight: 400;
    }
    .contact_form .form-group input[type="text"], 
    .contact_form .form-group input[type="email"], 
    .contact_form .form-group input[type="number"], 
    .contact_form .form-group input[type="date"],
    .contact_form .form-group input[type="tel"],
    .contact_form .form-group select, 
    .contact_form .form-group textarea {
        background: #f7f7f7;
        border: 1px solid #ddd;
        border-radius: 0;
        margin-bottom: 15px;
        color: #000;
        height: 52px;
    }   
    .form_submit {
        display: inline-block;
        vertical-align: top;
        padding: 15px 40px;
        background: #c10909;
        color: #fff;
        font-size: 18px;
        font-weight: 400;
        border-radius: 3px;
        border: none;
    }
    .contact_form p {
        font-size: 11px;
        font-weight: 500;
        line-height: 1.4;
    }
</style>

<section class="career_header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="career_badge">
                    Career
                </div>
                <h1>Looking for a job?<br/>Join the brand.</h1>
            </div>
            <div class="col-sm-5 offset-sm-1">
                <p>ONN is the new Premium Inners/Men’s Underwear Brand from the house of Lux Industries Limited. Crafted with the latest technology, the ONN range of Onn Premium wear effectively touches the style nerve of the fashionable Indian male.</p>
            </div>
        </div>
    </div>
</section>

<section class="career_banner">
    <div class="container">
        <figure>
            <div class="stroke_text">
                <h3>good products come from good people, and all problems are solved by good design.</h3>
                <div class="quote_meta">
                    <strong>John Doe</strong><br/>
                    CEO - ONN International
                </div>
            </div>
            <img src="{{asset('/img/aboutpage_banner.jpg')}}">
        </figure>
    </div>
</section>

<section class="career_value">
    <div class="container text-center">
        <h2>Our Core Purpose</h2>
        <h5>Make a difference in the lives of Our Customers, Employees and Community.</h5>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-4 mt-3 mt-sm-5">
                <div class="value_count">01</div>
                <h3>Customers</h3>
                <p>To our dedicated employees we provide an open work environment that promises to nurture creativity, tolerance, professional development, safety, and a family environment. We strive to bring diversity into the workplace.</p>
            </div>
            <div class="col-sm-4 mt-3 mt-sm-5">
                <div class="value_count">02</div>
                <h3>Employees</h3>
                <p>To our dedicated employees we provide an open work environment that promises to nurture creativity, tolerance, professional development, safety, and a family environment. We strive to bring diversity into the workplace.</p>
            </div>
            <div class="col-sm-4 mt-3 mt-sm-5">
                <div class="value_count">03</div>
                <h3>Community</h3>
                <p>To our dedicated employees we provide an open work environment that promises to nurture creativity, tolerance, professional development, safety, and a family environment. We strive to bring diversity into the workplace.</p>
            </div>
        </div>
    </div>
</section>

<section class="leadership_team">
    <div class="container text-center">
        <h2>ONN Leadership Team</h2>
        <h5>meet the executive management team<br/>of ONN International company.</h5>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-3 mt-3 mt-sm-5">
                <figure>
                    <img src="{{asset('/img/p1.jpg')}}">
                </figure>
                <figcaption>
                    <h3 class="member-name">Larry Swank</h3>
                    <div class="member-subtitle">Founder, Chairman and CEO</div>
                    <div class="member-description">
                        It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
                    </div>
                </figcaption>
            </div>
            <div class="col-sm-3 mt-3 mt-sm-5">
                <figure>
                    <img src="{{asset('/img/p2.jpg')}}">
                </figure>
                <figcaption>
                    <h3 class="member-name">Larry Swank</h3>
                    <div class="member-subtitle">Founder, Chairman and CEO</div>
                    <div class="member-description">
                        It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
                    </div>
                </figcaption>
            </div>
            <div class="col-sm-3 mt-3 mt-sm-5">
                <figure>
                    <img src="{{asset('/img/p3.jpg')}}">
                </figure>
                <figcaption>
                    <h3 class="member-name">Larry Swank</h3>
                    <div class="member-subtitle">Founder, Chairman and CEO</div>
                    <div class="member-description">
                        It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
                    </div>
                </figcaption>
            </div>
            <div class="col-sm-3 mt-3 mt-sm-5">
                <figure>
                    <img src="{{asset('/img/P4.jpg')}}">
                </figure>
                <figcaption>
                    <h3 class="member-name">Larry Swank</h3>
                    <div class="member-subtitle">Founder, Chairman and CEO</div>
                    <div class="member-description">
                        It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
                    </div>
                </figcaption>
            </div>
        </div>
    </div>
</section>

<section class="job_opening">
    <div class="container text-center">
        <h2>Current Job Openings</h2>
        <h5>We know that our employees are our greatest asset<br/>as we would not exist without them.</h5>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-11">
                <ul class="career_list">
                    <li>
                        <div class="row align-items-center">
                            <div class="col-sm-3">
                                <h4><span>01.</span> SALES EXECUTIVE</h4>
                            </div>
                            <div class="col-sm-3 mt-3 mt-sm-0">
                                <h4>Full Time</h4>
                            </div>
                            <div class="col-sm-3 mt-3 mt-sm-0">
                                <h4>Katihar/Raipur/Jagdalpur...</h4>
                            </div>
                            <div class="col-sm-3 mt-3 mt-sm-0 text-center text-sm-right">
                                <a href="javascript:void(0)">View Details</a>
                            </div>
                        </div>
                    </li>
                    <div class="cms_context">
                        <div class="row">
                            <div class="col-sm-4">
                                <h4>Position :</h4>
                            </div>
                            <div class="col-sm-8">
                                <h5>SALES EXECUTIVE</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <h4>Job Description :</h4>
                            </div>
                            <div class="col-sm-8">
                                <ul>
                                    <li>The candidate will be responsible for distributor handling.</li>
                                    <li>Secondary booking from retailers.</li>
                                    <li>POP &amp; POS utilization.</li>
                                </ul>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <h4>Qualification :</h4>
                            </div>
                            <div class="col-sm-8">
                                <ul>
                                    <li>Minimum Graduate with good communication skill.</li>
                                    <li>Secondary booking from retailers.</li>
                                    <li>Candidates having working experience of 2-5years with well known distribution company in Hosiery and FMCG,may apply.</li>
                                    <li> Age Profile: within 27 yrs -35yrs. </li>
                                    <li>Should be computer savvy with knowledge in e-mails/ internet.</li>
                                    <li> Remuneration : upto to 15kpm/ Negotiable. </li>
                                </ul>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <h4>Location :</h4>
                            </div>
                            <div class="col-sm-8">
                                <ul>
                                    <li>Katihar/Raipur/Jagdalpur/Gorakpur/Chandigarh/Amritsar/ Goa/Pune/Udaipur</li>
                                </ul>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <h4>Annual CTC :</h4>
                            </div>
                            <div class="col-sm-8">
                                <ul>
                                    <li>Negotiable</li>
                                </ul>
                                <p>Cv's to be forwarded at: hr@luxinnerwear.com</p>
                            </div>
                        </div>
                    </div>
                </ul>
            </div>
        </div>
    </div>
</section>


<section class="contact_form" id="partnerForm">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h2>Join Our Team</h2>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Name *</label>
                            <input type="text" name="name" value="" placeholder="" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Phone *</label>
                            <input type="tel" name="phone" value="" placeholder="" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" value="" placeholder="" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>City *</label>
                            <input type="text" name="city" value="" placeholder="" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Upload CV *</label>
                            <input type="file" name="cv" value="" placeholder="" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Message *</label>
                            <textarea name="message" class="form-control" value=""></textarea>
                        </div>
                    </div>
                    

  

                    <div class="col-sm-12">
                        <div class="form-group justify-content-center align-items-center">
                            <input type="submit" name="" class="form_submit" value="Submit Now">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- <section class="cart-header mb-3 mb-sm-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h4>Career</h4>
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

            <div class="col-sm-9">
                <div class="cms_context">
                    <div class="row">
                        <div class="col-sm-4">
                            <h4>Position :</h4>
                        </div>
                        <div class="col-sm-8">
                            <h5>SALES EXECUTIVE</h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <h4>Job Description :</h4>
                        </div>
                        <div class="col-sm-8">
                            <ul>
                                <li>The candidate will be responsible for distributor handling.</li>
                                <li>Secondary booking from retailers.</li>
                                <li>POP &amp; POS utilization.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <h4>Qualification :</h4>
                        </div>
                        <div class="col-sm-8">
                            <ul>
                                <li>Minimum Graduate with good communication skill.</li>
                                <li>Secondary booking from retailers.</li>
                                <li>Candidates having working experience of 2-5years with well known distribution company in Hosiery and FMCG,may apply.</li>
                                <li> Age Profile: within 27 yrs -35yrs. </li>
                                <li>Should be computer savvy with knowledge in e-mails/ internet.</li>
                                <li> Remuneration : upto to 15kpm/ Negotiable. </li>
                            </ul>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <h4>Location :</h4>
                        </div>
                        <div class="col-sm-8">
                            <ul>
                                <li>Katihar/Raipur/Jagdalpur/Gorakpur/Chandigarh/Amritsar/ Goa/Pune/Udaipur</li>
                            </ul>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <h4>Annual CTC :</h4>
                        </div>
                        <div class="col-sm-8">
                            <ul>
                                <li>Negotiable</li>
                            </ul>
                            <p>Cv's to be forwarded at: hr@luxinnerwear.com</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section> -->
@endsection
