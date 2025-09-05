@extends('layouts.app')

@section('page', 'FAQ')

@section('content')
<style type="text/css">
    .map_area {
        width: 100%;
        height: 300px;
        position: relative;
        margin-bottom: 20px;
    }
    .map_area iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    .profile-card textarea.form-control {
        border: 1px solid #EAEAEC !important;
        border-radius: 0 !important;
    }
    textarea {
        min-height: 120px;
        resize: none;
    }
</style>

<section class="cart-header mb-3 mb-sm-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h4>Contact Us</h4>
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
                            <li><a href="{{route('front.content.news')}}">News</a></li>
                            <li><a href="{{route('front.content.blog')}}">Blogs</a></li>
                            <li><a href="{{route('front.content.global')}}">Global</a></li>
                            <li class="{{ request()->is('contact') ? 'active' : '' }}"><a href="{{route('front.content.contact')}}">Contact</a></li>
                            <!-- <li><a href="{{route('front.content.career')}}">Career</a></li> -->
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="col-sm-9">
                <div class="map_area">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3683.986685743695!2d88.43082871487756!3d22.57960128840419!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a0275a56e5af545%3A0xc3d00bd19501575b!2sONN%20Innerwear!5e0!3m2!1sen!2sin!4v1654109109960!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-lg-6">
                        <div class="account-card">
                            <figure>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-navigation"><polygon points="3 11 22 2 13 21 11 13 3 11"></polygon></svg>
                                </span>
                            </figure>
                            <figcaption>
                                <h4>Contact Information</h4>
                                <h6>Feel free to call us on <a href="tel:{{ $settings[6]->content }}">{{ $settings[6]->content }}</a><br/>(9 AM to 7 PM on all working days.)<br/><strong>E-mail</strong> - <a href="mailto:{{ $settings[7]->content }}">{{ $settings[7]->content }}</a></h6>
                            </figcaption>
                        </div>
                    </div>
                    <div class="col-sm-12 col-lg-6">
                        <div href="{{route('front.content.news')}}" class="account-card">
                            <figure>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                </span>
                            </figure>
                            <figcaption>
                                <h4>Address</h4>
								<h6>{!! $settings[5]->content !!}</h6>
                            </figcaption>
                        </div>
                    </div>

                    <div class="col-sm-12 col-lg-6">
                        <div href="{{route('front.content.news')}}" class="account-card">
                            <figure>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                </span>
                            </figure>
                            <figcaption>
                                <h4>Inquiries & Feedback</h4>
                                <h6>For any kind of product related issues,<br/>suggestions or feedback you can mail here- <a href="mailto:{{ $settings[7]->content }}">{{ $settings[7]->content }}</a></h6>
                            </figcaption>
                        </div>
                    </div>
                </div>

                <div class="profile-card">
                    <h3>Feel free to contact us</h3>

                    <div class="row">
                        <div class="col-sm-12 col-lg-4">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Your Name" name="fname" value="">
                                <label class="floating-label">Your Name</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-4">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Your Email" name="fname" value="">
                                <label class="floating-label">Your Email</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-4">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Subject" name="fname" value="">
                                <label class="floating-label">Subject</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-12">
                            <div class="form-group">
                                <textarea class="form-control" placeholder="Your Message" name="fname"></textarea>
                                <label class="floating-label">Your Message</label>
                            </div>
                        </div>
                    </div>

                    <div class="profile-card-footer">
                        <button type="submit" class="btn checkout-btn">Send</button>
                    </div>
                </div>



            </div>

        </div>
    </div>
</section>
@endsection
