@extends('layouts.app')

@section('page', 'Franchise')

@section('content')

<!-- Google Tag Manager -->
<script>
	/*
	(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PQBVBX9');
*/
</script>
<!-- End Google Tag Manager -->


<!-- Google Tag Manager (noscript) -->
<!--
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PQBVBX9"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
-->
<!-- End Google Tag Manager (noscript) -->

<style type="text/css">
	.franchise_banner {
		padding-top: 200px;
		padding-bottom: 100px;
		background: url('{{asset('/img/onn_franchise05.jpg')}}') center right no-repeat;
		position: relative;
		background-size: auto 100%;
	}
	.franchise_banner:before {
		position: absolute;
		width: 100%;
		height: 100%;
		top: 0;
		left: 0;
		content: '';
		/* Permalink - use to edit and share this gradient: https://colorzilla.com/gradient-editor/#000000+0,000000+100&1+40,0+100 */
		background: -moz-linear-gradient(left,  rgba(0,0,0,1) 0%, rgba(0,0,0,1) 40%, rgba(0,0,0,0) 100%); /* FF3.6-15 */
		background: -webkit-linear-gradient(left,  rgba(0,0,0,1) 0%,rgba(0,0,0,1) 40%,rgba(0,0,0,0) 100%); /* Chrome10-25,Safari5.1-6 */
		background: linear-gradient(to right,  rgba(0,0,0,1) 0%,rgba(0,0,0,1) 40%,rgba(0,0,0,0) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#000000', endColorstr='#00000000',GradientType=1 ); /* IE6-9 */
	}
	.franchise_banner h1 {
		font-size: 48px;
		line-height: 1;
		font-weight: 700;
		margin-bottom: 20px;
		color: #fff;
		position: relative;
		z-index: 9;
	}
	.franchise_banner figure {
		margin-top: -80px;
		margin-bottom: 30px;
	}
	.franchise_banner img {
		max-width: 100%;
	}
	.franchise_banner h1 strong {
		color: #c10909;
		font-weight: 700;
	}
	.franchise_banner a {
		display: inline-block;
		vertical-align: top;
		padding: 15px 40px;
		background: #c10909;
		color: #fff;
		font-size: 18px;
		font-weight: 400;
		border-radius: 3px;
	}
	.franchise_banner p {
		font-size: 14px;
		font-weight: 500;
		line-height: 1.6;
		margin-bottom: 25px;
		color: #fff;
	}
	.franchise_content {
		padding: 0 0 60px;
	}
	.franchise_content p {
		font-size: 13px;
		font-weight: 500;
		line-height: 1.6;
	}
	.franchise_content h3 {
		margin-bottom: 15px;
		color: #c10909;
		font-size: 35px;
		text-align: center;
	}
	.franchise_overview {
		padding: 100px 0;
	}
	.franchise_overview p {
		font-size: 13px;
		font-weight: 500;
		line-height: 1.6;
		margin-bottom: 20px;
		color: #131313;
	}
	.franchise_overview p:last-child {
		margin-bottom: 0;
	}
	.franchise_overview h2 {
	    font-size: 32px;
	    font-weight: 700;
	    margin: 0 0 20px;
	    line-height: 1.2;
	    text-transform: uppercase;
	    color: #000;
	}
	.franchise_overview h2 span {
		font-weight: 900;
		color: #c10909;
	}
	.franchise_overview figure {
		position: relative;
		margin: 0;
		padding-bottom: 76%;
	}
	.franchise_overview figure img {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		object-fit: cover;
		z-index: 9;
	}
	.franchise_overview figure:before {
	    content: '';
	    position: absolute;
	    top: -20px;
	    left: -20px;
	    right: 50%;
	    bottom: 50px;
	    z-index: 1;
	    background-color: #c10909;
	}
	.franchise_overview h6 {
		font-size: 16px;
		line-height: 20px;
		font-weight: 500;
		margin-bottom: 8px;
		color: #c10909;
	}
	.franchise_shop {
		width: 100%;
		display: block;
		padding-bottom: 80%;
		position: relative;
		margin: 15px 0 30px;
	}
	.franchise_shop img {
		width: 100%;
		height: 100%;
		object-fit: cover;
		position: absolute;
		top: 0;
		left: 0;
	}
	.franchise_requisite {
		padding: 60px 0;
	}
	.franchise_requisite h2 {
		font-size: 32px;
	    font-weight: 700;
	    margin: 0 0 60px;
	    line-height: 1.2;
	    text-transform: uppercase;
	    color: #000;
	}
	.franchise_requisite h5 {
		font-size: 16px;
		font-weight: 500;
		margin-bottom: 0;
		color: #000;
	}
	.franchise_requisite p {
		font-size: 13px;
		font-weight: 500;
		line-height: 1.6;
		color: #888;
	}
	.franchise_requisite figure {
		width: 100px;
		height: 100px;
		border-radius: 50%;
		padding: 25px;
		background: rgba(20, 27, 75, 0.1);
		margin: 0 30px 0 0;
		display: block;
	}
	.franchise_requisite figure img {
		max-width: 100%;
	}
	.franchise_requisite figcaption {
		flex: 1 0 0%;
	}
	.franchise_breakup_box {
		padding: 60px 0;
	}
	.franchise_breakup_box h2 {
		font-size: 32px;
	    font-weight: 700;
	    margin: 0 0 60px;
	    line-height: 1.2;
	    text-transform: uppercase;
	    color: #000;
	}
	.breakup_box {
		display: block;
		border-radius: 10px;
		padding: 25px;
		margin-bottom: 25px;
		background: rgba(20, 27, 75, 0.1);
	}
	.breakup_box figure {
		width: 100px;
		height: 100px;
		border-radius: 50%;
		padding: 25px;
		background: rgba(20, 27, 75, 0.1);
		margin: 0 auto 30px;
		display: block;
	}
	.breakup_box figure img {
		max-width: 100%;
	}
	.breakup_box h5 {
		min-height: 48px;
		font-size: 16px;
		font-weight: 500;
		margin-bottom: 10px;
		text-align: center;
	}
	.breakup_box p {
		min-height: 40px;
		font-size: 13px;
		font-weight: 500;
		line-height: 1.6;
		text-align: center;
		color: #000;
		margin: 0;
	}
	.franchise_segments {
		padding: 0;
	}
	.franchise_segments img {
		max-width: 100%;
	}
	.franchise_segments h2 {
		font-size: 30px;
		font-weight: 500;
		margin-bottom: 60px;
	}
	.franchise_segments p {
		font-size: 16px;
		font-weight: 500;
		line-height: 1.4;
	}
	.franchise_segments a:hover {
		text-decoration: underline;
		color: #c10909;
	}
	.franchise_block {
		display: block;
		background: #fff4de;
		padding: 0;
	}
	.franchise_block img {
		max-width: 100%;
	}
	.franchise_block h2 {
		margin-bottom: 25px;
		font-size: 32px;
		font-weight: 700;
		margin: 0 0 20px;
		line-height: 1.2;
		text-transform: uppercase;
		color: #000;
	}
	.franchise_block p {
		font-size: 16px;
		font-weight: 500;
		line-height: 1.4;
		color: #888;
	}
	.drop_form {
		display: flex;
		border-radius: 8px;
		border: 2px solid #c10909;
		margin-top: 25px;
	}
	.drop_email {
		padding: 0 10px;
		height: 40px;
		line-height: 40px;
		background: transparent;
		border: none;
		flex: 1 0 0%;
		border-radius: 6px 0 0 6px;
	}
	.drop_button {
		border: none;
		border-radius: 0 6px 6px 0;
		background: #c10909;
		color: #fff;
		font-size: 14px;
		font-weight: 500;
		height: 40px;
		line-height: 40px;
		padding: 0 20px;
	}
	.about_content_block {
		display: flex;
		box-shadow: 0 5px 25px rgb(0 0 0 / 20%);
		margin-top: 30px;
	}
	.about_content_left {
		background: #c10909;
		padding: 20px 30px;
		box-sizing: border-box;
		text-align: center;
		color: #fff;
	}
	.about_content_block h5 {
		font-weight: 500;
		font-size: 13px;
	}
	.about_content_block h4 {
		margin: 0;
		font-weight: 700;
		font-size: 14px;
	}
	.about_content_right {
		flex: 1 0 0%;
		background-color: #232323;
		padding: 20px 30px;
		box-sizing: border-box;
		color: #fff;
	}

	.about_image_wrap {
		max-width: 510px;
		display: block;
		margin: 0 auto;
		position: relative;
		z-index: 3;
	}
	.about_image_wrap:after {
		width: 200px;
		height: 400px;
		border: 12px solid #c10909;
		filter: drop-shadow(5px 5px 5px rgb(0 0 0 / 20%));
		content: '';
		position: absolute;
		top: 100px;
		left: -70px;
		z-index: -1;
	}
	.about_image {
		display: block;
		position: relative;
		width: 100%;
		padding-bottom: calc((4 / 3.5) * 100%);
	}
	.about_image img {
		position: absolute;
		object-fit: cover;
		width: 100%;
		height: 100%;
	}
	.year_of_experience {
		box-shadow: 0 30px 50px rgb(0 0 0 / 12%);
		display: flex;
		align-items: center;
		padding: 30px;
		background-color: #fff;
		border-left: 12px solid #c10909;
		position: absolute;
		bottom: -50px;
		right: -50px;
	}
	.year_of_experience h3, .year_of_experience p {
		margin: 0;
		color: #000;
	}
	.year_of_experience p {
		text-transform: uppercase;
		font-size: 16px !important;
		font-weight: 600 !important;
	}
	.year_of_experience h3 {
		margin-right: 15px;
		font-size: 60px;
		color: #c10909;
	}
	.franchise_content {
		padding: 100px 0;
		background: #f7f7f7;
	}
	.franchise_content p {
		font-size: 13px;
		font-weight: 500;
		line-height: 1.6;
		margin-bottom: 20px;
		color: #131313;
	}
	.franchise_content p:last-child {
		margin-bottom: 0;
	}
	.franchise_content h2 {
	    font-size: 32px;
	    font-weight: 700;
	    margin: 0 0 20px;
	    line-height: 1.2;
	    text-transform: uppercase;
	    color: #000;
	}
	.franchise_content h2 span {
		color: #c10909;
	}

	.requisite_block {
		display: flex;
		margin-bottom: 30px;
		align-items: center;
	}
	.franchise_gallery {
		padding: 0;
	}
	.franchise_gallery figure {
		width: 100%;
		padding-bottom: 100%;
		position: relative;
		margin-bottom: 30px;
	}
	.franchise_gallery img {
		width: 100%;
		height: 100%;
		position: absolute;
		top: 0;
		left: 0;
		object-fit: cover;
	}
	.franchise_gallery h2 {
	    font-size: 32px;
	    font-weight: 700;
	    margin: 0 0 60px;
	    line-height: 1.2;
	    text-transform: uppercase;
	    color: #000;
	}
	.contact_form {
		display: block;
		padding: 60px 0;
	}
	.contact_form h2 {
	    font-size: 32px;
	    font-weight: 700;
	    margin: 0 0 60px;
	    line-height: 1.2;
	    text-transform: uppercase;
	    color: #000;
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
	.account-card {
		height: 100px;
	}
	@media(max-width: 575px) {
		.franchise_banner {
			padding-top: 150px;
			padding-bottom: 50px;
		}
		.franchise_banner h1 {
			font-size: 30px;
			margin-bottom: 10px;
		}
		.franchise_banner p {
			font-size: 12px;
			line-height: 1.4;
		}
		.franchise_overview {
			padding: 50px 0;
		}
		.franchise_overview figure {
			padding-bottom: 66%;
		}
		.about_content_block {
			flex-direction: column;
		}
		.about_content_left, .about_content_right {
			padding: 10px 15px;
		}
		.franchise_content {
			padding: 50px 0;
		}
		.year_of_experience {
			border-left-width: 6px;
			padding: 15px;
		    bottom: -25px;
    		right: -10px;
		}
		.year_of_experience h3 {
		    margin-right: 10px;
    		font-size: 30px;
		}
		.year_of_experience p {
		    text-transform: uppercase;
		    font-size: 10px !important;
		    font-weight: 600 !important;
		    line-height: 1.5 !important;
		}
		.about_image_wrap:after {
			top: 70px;
			left: -50px;
		}
	}
</style>

<!-- <section class="listing-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1>Franchise</h1>
            </div>
            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Franchise</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>
 -->


<section class="franchise_banner">
	<div class="container">
		<div class="row">
			<div class="col-md-5">
				<h1>Become our strategic Exclusive Store partner</h1>
				<p>Become an ONN International partner, We would be glad to partner with you if you own a commercial or commercially converted/convertible retail space on a rental/revenue share basis.</p>

				<a href="#partnerForm">Enquire Now</a>
			</div>
		</div>
	</div>
</section>
<?php /* ?>
<section class="franchise_overview">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-md-6 mb-3 mb-md-0">
				<figure>
					<img src="{{asset('/img/onn_franchise06.jpg')}}" class="img-fluid">
				</figure>
			</div>
			<div class="col-md-6">
				<h6>About Brand</h6>
				<h2>Who We are</h2>
				<p>ONN is the new Premium Men Inners/ Athleisure Brand from the house of Lux Industries Limited. Crafted with the latest technology, the ONN range of Onn Premium wear effectively touches the style nerve of the fashionable Indian male. A vast range of designs, top notch quality and sensible styling has been presented to meet the standards and aspirations of today’s.</p>

				<p>Within a short stretch of time, ONN has strongly built its brand and emerged as a fierce competition for its contemporaries. It has consistently shown remarkable product development, and innovation in the markets over the years. We further aim to consistently improve and cater to complete customer satisfaction by creating top-notch products.</p>

				<div class="about_content_block">
					<div class="about_content_left">
						<h5>For further details Call</h5>
						<h4>+91-90070 21060</h4>
					</div>
					<div class="about_content_right">
						<h5>Any questions?</h5>
						<h4>Mail us at sagar.shah@luxinnerwear.com</h4>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="franchise_content">
	<div class="container">
		<div class="row">
			<div class="col-md-6 mb-5 mb-md-0">
				<div class="about_image_wrap">
					<figure class="about_image">
						<img src="{{asset('/img/onn_franchise04.jpg')}}">
					</figure>
					<div class="year_of_experience">
						<h3>45</h3>
						<p>Stores<br/>in India</p>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<h2>OUR MISSION & <span>VISION</span></h2>
				<p>We believe in providing our customers with the products to satisfy their needs of ultimate comfort and style at the same time. As the fashion is changing at rapid pace, we aim to make the products meet the desires and aspirations of the youth. We believe in consistently meeting and even surpassing the customer’s values and the expectations that they associate with our brand.</p>

				<p>We are focusing on maintaining and strengthening the brand image in accordance with the brand identity and brand values. We desire to become the best youthful Onn Premium wear-sportswear and leisure wear brand with top of the mind recall.</p>

				<p>ONN is the new Men’s Premium Inners/ Athleisure brand that comes from the house of Lux Industries limited. The House of Lux has been making a difference in the Hosiery Industry since the company was founded in 1957.</p>

				<p>Each and every product has been conceptualized and designed after an extensive market research and in accordance to the consumers’ perceptions and desires. The products quality can easily be compared with any of the existent globally popular brands in its range owing to the scientific methods of conceptualizing, designing, and manufacturing.</p>

				<p>The very essence of ONN premium Inners / Athleisure lies in the way it is designed and manufactured. An ISO 9001:2008 certified company; quality of a product forms the main crux of the company’s motto. Right from the basic designing to the final packaging, everything is done in-house in order to keep a check on the quality of the product. The best machinery is used to manufacture ONN brand of products.</p>
			</div>
		</div>
	</div>
</section>
<?php */ ?>

<!-- <section class="franchise_block pt-5 pt-0">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-md-5">
				<h2>Interested In Acquiring an ONN Franchise?</h2>
				<p>Drop your email here and get some feed back about ONN franchise policy, contact, location available and other contract.</p>

				<form action="{{route('front.franchise.mail')}}" method="POST" class="drop_form" id="franchiseMailForm">@csrf
					<input type="email" name="franchiseEmail" id="franchiseEmail" class="drop_email">
					<input type="submit" name="" value="Submit" class="drop_button">
				</form>
				<p class="mt-3" id="franchiseEmailResp"></p>
			</div>
			<div class="col-sm-6 offset-md-1">
				<img src="{{asset('/img/onn_store.png')}}">
			</div>
		</div>
	</div>
</section> -->


<section class="franchise_requisite">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<h2>Requisite</h2>
			</div>
			<div class="col-sm-6 col-md-6 col-lg-4">
				<div class="requisite_block">
					<figure>
						<img src="{{asset('/img/light-bulb.png')}}">
					</figure>
					<figcaption>
						<h5>Area Required</h5>
						<p>200-300 sq. ft. Carpet Area</p>
					</figcaption>
				</div>
			</div>
			<div class="col-sm-6 col-md-6 col-lg-4">
				<div class="requisite_block">
					<figure>
						<img src="{{asset('/img/salary.png')}}">
					</figure>
					<figcaption>
						<h5>Investment</h5>
						<p>INR 15-20 Lacs</p>
					</figcaption>
				</div>
			</div>
			<div class="col-sm-6 col-md-6 col-lg-4">
				<div class="requisite_block">
					<figure>
						<img src="{{asset('/img/blueprint.png')}}">
					</figure>
					<figcaption>
						<h5>Floor</h5>
						<p>Ground on High Street & as per zone in Malls</p>
					</figcaption>
				</div>
			</div>
			<div class="col-sm-6 col-md-6 col-lg-4">
				<div class="requisite_block">
					<figure>
						<img src="{{asset('/img/bag.png')}}">
					</figure>
					<figcaption>
						<h5>Taxes & Transport</h5>
						<p>On Company</p>
					</figcaption>
				</div>
			</div>
			<div class="col-sm-6 col-md-6 col-lg-4">
				<div class="requisite_block">
					<figure>
						<img src="{{asset('/img/handshake.png')}}">
					</figure>
					<figcaption>
						<h5>Format</h5>
						<p>Outright Sales</p>
					</figcaption>
				</div>
			</div>
			<div class="col-sm-6 col-md-6 col-lg-4">
				<div class="requisite_block">
					<figure>
						<img src="{{asset('/img/packages.png')}}">
					</figure>
					<figcaption>
						<h5>Stock Correction</h5>
						<p>2 Times in a Calendar Year</p>
					</figcaption>
				</div>
			</div>
		</div>
	</div>
</section>

<section id="sale" class="home-offers">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <h4>Become <span>franchise</span> of</h4>
                <h2><span>ONN</span> exclusive store</h2>
                <!-- <p>Offer valid upto 15th may</p> -->
            </div>
            <div class="col-sm-5 offset-sm-1 text-sm-right">
                <a href="#partnerForm" class="offer-button">Enquire Now</a>
            </div>
        </div>
    </div>
</section>

<section class="franchise_breakup_box">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<h2>Investment Breakup & Margin</h2>
			</div>
			<div class="col-sm-4 col-lg">
				<div class="breakup_box">
					<figure>
						<img src="{{asset('/img/salary.png')}}">
					</figure>
					<h5>Investment on Stocks</h5>
					<p>INR 3,000 per sq. ft.</p>
				</div>
			</div>
			<div class="col-sm-4 col-lg">
				<div class="breakup_box">
					<figure>
						<img src="{{asset('/img/clothes-rack.png')}}">
					</figure>
					<h5>Investment On Interiors</h5>
					<p>INR 2,000 – 2,200 per sq. ft. (approx.)</p>
				</div>
			</div>
			<div class="col-sm-4 col-lg">
				<div class="breakup_box">
					<figure>
						<img src="{{asset('/img/money-bag.png')}}">
					</figure>
					<h5>Franchisee Fees</h5>
					<p>INR 99,000 (non-refundable)</p>
				</div>
			</div>
			<div class="col-sm-6 col-lg">
				<div class="breakup_box">
					<figure>
						<img src="{{asset('/img/profit.png')}}">
					</figure>
					<h5>Margin</h5>
					<p>40% on Net Sales</p>
				</div>
			</div>
			<div class="col-sm-6 col-lg">
				<div class="breakup_box">
					<figure>
						<img src="{{asset('/img/tag.png')}}">
					</figure>
					<h5>Scheme and Promotion</h5>
					<p>Sharing-50%:50% with Franchisee and Company</p>
				</div>
			</div>
		</div>
	</div>
</section>



<section class="franchise_gallery">
	<div class="container-fluid">
		<div class="col-sm-12 text-center">
			<h2>Explore Our Stores</h2>
		</div>
		<!-- <div class="row justify-content-center">
			<div class="col-6 col-sm">
				<figure>
					<img src="{{asset('/img/onn_franchise01.jpg')}}">
				</figure>
			</div>
			<div class="col-6 col-sm">
				<figure>
					<img src="{{asset('/img/onn_franchise02.jpg')}}">
				</figure>
			</div>
			<div class="col-6 col-sm">
				<figure>
					<img src="{{asset('/img/onn_franchise03.jpg')}}">
				</figure>
			</div>
			<div class="col-6 col-sm">
				<figure>
					<img src="{{asset('/img/onn_franchise04.jpg')}}">
				</figure>
			</div>
			<div class="col-6 col-sm">
				<figure>
					<img src="{{asset('/img/onn_franchise05.jpg')}}">
				</figure>
			</div>
		</div> -->
		<div class="swiper our_store">
		    <div class="swiper-wrapper">
		      <div class="swiper-slide">
		      	<figure>
		      		<img src="{{asset('/img/onn_franchise01.jpg')}}">
		      	</figure>
		      </div>
		      <div class="swiper-slide">
		      	<figure>
		      		<img src="{{asset('/img/onn_franchise02.jpg')}}">
		      	</figure>
		      </div>
		      <div class="swiper-slide">
		      	<figure>
		      		<img src="{{asset('/img/onn_franchise03.jpg')}}">
		      	</figure>
		      </div>
		      <div class="swiper-slide">
		      	<figure>
		      		<img src="{{asset('/img/onn_franchise04.jpg')}}">
		      	</figure>
		      </div>
		      <div class="swiper-slide">
		      	<figure>
		      		<img src="{{asset('/img/onn_franchise05.jpg')}}">
		      	</figure>
		      </div>
		      <div class="swiper-slide">
		      	<figure>
		      		<img src="{{asset('/img/onn_franchise04.jpg')}}">
		      	</figure>
		      </div>
		      <div class="swiper-slide">
		      	<figure>
		      		<img src="{{asset('/img/onn_franchise05.jpg')}}">
		      	</figure>
		      </div>
		    </div>
		    <div class="swiper-pagination"></div>
		</div>
	</div>
</section>

<section class="franchise_requisite">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<h2>Marketing & Sales Promotion</h2>
			</div>
			<div class="col-sm-6 col-md-4">
				<div class="requisite_block">
					<figure>
						<img src="{{asset('/img/money-bag.png')}}">
					</figure>
					<figcaption>
						<h5>Local / Online Marketing</h5>
						<p>On Company</p>
					</figcaption>
				</div>
			</div>
			<div class="col-sm-6 col-md-4">
				<div class="requisite_block">
					<figure>
						<img src="{{asset('/img/profit.png')}}">
					</figure>
					<figcaption>
						<h5>Offers / Schemes</h5>
						<p>Budget will be shared between Company and Franchisee equally</p>
					</figcaption>
				</div>
			</div>
			<div class="col-sm-6 col-md-4">
				<div class="requisite_block">
					<figure>
						<img src="{{asset('/img/tag.png')}}">
					</figure>
					<figcaption>
						<h5>Launch Marketing Support</h5>
						<p>Total store launch budget will be shared between Company and Franchisee equally</p>
					</figcaption>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- 
<section class="franchise_segments">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-6 p-0">
				<figure>
					<img src="{{asset('/img/onn_platina.png')}}">
				</figure>
			</div>
			<div class="col-sm-6 p-0">
				<figure>
					<img src="{{asset('/img/onn_comfort.png')}}">
				</figure>
			</div>
		</div>
	</div>
</section>
 -->

<section class="contact_form" id="partnerForm">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<h2>BECOME A PARTNER</h2>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-9">
				<form id="partnerFormSubmit" action="{{ route('front.franchise.partner') }}" method="post">@csrf
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label>Name *</label>
								<input type="text" name="name" value="{{old('name')}}" placeholder="" class="form-control">
							</div>
							@error('name') <p class="text-danger">{{$message}}</p> @enderror
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Phone *</label>
								<input type="tel" name="phone" value="{{old('phone')}}" placeholder="" class="form-control">
							</div>
							@error('phone') <p class="text-danger">{{$message}}</p> @enderror
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Email *</label>
								<input type="email" name="email" value="{{old('email')}}" placeholder="" class="form-control">
							</div>
							@error('email') <p class="text-danger">{{$message}}</p> @enderror
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>City *</label>
								<input type="text" name="city" value="{{old('city')}}" placeholder="" class="form-control">
							</div>
							@error('city') <p class="text-danger">{{$message}}</p> @enderror
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Nature of Current Business *</label>
								<input type="text" name="business_nature" value="{{old('business_nature')}}" placeholder="" class="form-control">
							</div>
							@error('business_nature') <p class="text-danger">{{$message}}</p> @enderror
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Which territory or region are you interested in? *</label>
								<input type="text" name="region" value="{{old('region')}}" placeholder="" class="form-control">
							</div>
							@error('region') <p class="text-danger">{{$message}}</p> @enderror
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Is Property Available?</label>
								<select name="property_type" class="form-control">
									<option value="Owned" {{ (old('property_type') == "Owned") ? 'selected' : '' }}>Owned</option>
									<option value="Rented" {{ (old('property_type') == "Rented") ? 'selected' : '' }}>Rented</option>
								</select>
							</div>
							@error('property_type') <p class="text-danger">{{$message}}</p> @enderror
						</div>

						<div class="col-sm-6">
							<div class="form-group">
								<label>Available Capital?</label>
								<select name="capital" class="form-control">
									<option value="15L - 20L" {{ (old('property_type') == "15L - 20L") ? 'selected' : '' }}>15L - 20L</option>
									<option value="20L - 30L" {{ (old('property_type') == "20L - 30L") ? 'selected' : '' }}>20L - 30L</option>
									<option value="30L - 40L" {{ (old('property_type') == "30L - 40L") ? 'selected' : '' }}>30L - 40L</option>
									<option value="40L - 50L" {{ (old('property_type') == "40L - 50L") ? 'selected' : '' }}>40L - 50L</option>
								</select>
							</div>
							@error('capital') <p class="text-danger">{{$message}}</p> @enderror
						</div>

						<div class="col-sm-12">
							<div class="form-group">
								<label>How did you hear about our franchise opportunities?</label>
								<select name="source">
									<option value="Family &amp; Friends" {{ (old('property_type') == "Family &amp; Friends") ? 'selected' : '' }}>Family &amp; Friends</option>
									<option value="Facebook" {{ (old('property_type') == "Facebook") ? 'selected' : '' }}>Facebook</option>
									<option value="Instagram" {{ (old('property_type') == "Instagram") ? 'selected' : '' }}>Instagram</option>
									<option value="Twitter" {{ (old('property_type') == "Twitter") ? 'selected' : '' }}>Twitter</option>
									<option value="Other Social Media" {{ (old('property_type') == "Other Social Media") ? 'selected' : '' }}>Other Social Media</option>
								</select>
							</div>
							@error('source') <p class="text-danger">{{$message}}</p> @enderror
						</div>

						<div class="col-sm-12">
							<div class="form-group">
								<label>Comments</label>
								<textarea name="comment" class="form-control" value="{{old('comment')}}"></textarea>
							</div>
							@error('comment') <p class="text-danger">{{$message}}</p> @enderror
						</div>

						<div class="col-sm-12">
							<p class="" id="partnerResp"></p>
						</div>

						<div class="col-sm-12">
							<div class="form-group justify-content-center align-items-center">
								{{-- <button type="submit" class="form_submit">Submit Now</button> --}}
								<input type="submit" name="" class="form_submit" value="Submit Now">
							</div>
						</div>

						<div class="col-12 mt-3">
							<p>Disclaimer : All information provided would be kept strictly confidential and used only for the purpose of the franchise application. Upon receipt of your enquiry, we will be in touch with you shortly for further discussion.</p>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="container text-center mt-5">
		<h4>For further details, contact</h4>


		<div class="row justify-content-center mt-5">
			<div class="col-sm-4">
				<a href="tel:+91-99100 29963" class="account-card">
                    <figure>
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone-call"><path d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        </span>
                    </figure>
                    <figcaption>
                        <h4>+91-99100 29963</h4>
                    </figcaption>
                </a>
            </div>
            <div class="col-sm-4">
				<a href="mailto:ebo@onninternational.com" class="account-card">
                    <figure>
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        </span>
                    </figure>
                    <figcaption>
                        <h4>ebo@onninternational.com</h4>
                    </figcaption>
                </a>
            </div>
		</div>
	</div>
</section>

@endsection

@section('script')
<script>
    // subscription mail form
	$('#franchiseMailForm').on('submit', function(e) {
		e.preventDefault();
		$.ajax({
			url : $(this).attr('action'),
			method : $(this).attr('method'),
			data : {
				_token : '{{csrf_token()}}',
				email : $('input[name="franchiseEmail"]').val()
			},
			beforeSend : function() {
				$('#franchiseEmailResp').html('Please wait <i class="fas fa-spinner fa-pulse"></i>');
			},
			success : function(result) {
				result.resp == 200 ? $icon = '<i class="fas fa-check"></i> ' : $icon = '<i class="fas fa-info-circle"></i> ';
				$('#franchiseEmailResp').html('<span class="success_message">'+ $icon+result.message + '</span>');
				$('button').attr('disabled', false);
			}
		});
	});

    // partner form submit
	/* $('#partnerFormSubmit').on('submit', function(e) {
		e.preventDefault();
		$.ajax({
			url : $(this).attr('action'),
			method : $(this).attr('method'),
			data : {
				_token : '{{csrf_token()}}',
				email : $('input[name="franchiseEmail"]').val()
			},
			beforeSend : function() {
				$('#franchiseEmailResp').html('Please wait <i class="fas fa-spinner fa-pulse"></i>');
			},
			success : function(result) {
				result.resp == 200 ? $icon = '<i class="fas fa-check"></i> ' : $icon = '<i class="fas fa-info-circle"></i> ';
				$('#franchiseEmailResp').html('<span class="success_message">'+ $icon+result.message + '</span>');
				$('button').attr('disabled', false);
			}
		});
	}); */
</script>
@endsection
