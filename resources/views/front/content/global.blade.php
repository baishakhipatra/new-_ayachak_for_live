@extends('layouts.app')

@section('page', 'FAQ')

@section('content')
<section class="cart-header mb-3 mb-sm-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h4>Global</h4>
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
                            <li class="{{ request()->is('global') ? 'active' : '' }}"><a href="{{route('front.content.global')}}">Global</a></li>
                            <li><a href="{{route('front.content.contact')}}">Contact</a></li>
                            <!-- <li><a href="{{route('front.content.career')}}">Career</a></li> -->
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="col-sm-9">
                <div class="cms_context">
                    <h5>The Global Presence</h5>

                    <p>ONN is the Men’s Premium Innerwear/Athleisure brand that comes from the house of Lux Industries Limited. The House of Lux has been making a difference in the Hosiery Industry since the company was founded in 1957.</p>

                    <p>Now, Lux has expanded its offices to the Middle-East, Africa, Australia, and Europe and exports products to 47 countries. We rank No. 1 among innerwear exporters from India and uphold an uncompromising stance on quality. With a workforce of over 18,000 direct and indirect skilled and dedicated employees working together as a team, the organization embodies skilled CAs, MBAs & Engineers from the top-tier institutes of the country. The Government of India has also honored Lux Industries Limited as “Star Export House” in 2010.</p>

                    <p>Keeping the dream of Late Shri Girdharilalji Todi alive, Lux Industries Limited is diversifying its product base and that is how ONN is born with a key focus on today’s youth.</p>

                    <h5>The Global Standards</h5>

                    <p>The very essence of ONN premium wear lies in the way it is designed and manufactured. As an ISO 9001:2008 certified company, the quality of a product forms the main crux of the company’s motto. All the processes involved in manufacturing a perfect piece of garment, from product designing and cloth dyeing to accessories selection and the final packaging, everything is done under expert supervision in order to keep a check on the quality of the product. The best quality machinery imported from various parts of the globe is used to manufacture the ONN brand of products.</p>

                    <p>Lux Industries Limited has always been extensively particular about the uncompromising quality of the product and the very same is reflected in the offering from the house of ONN premium wear.</p>

                    <h5>The Global Appeal</h5>
                    
                    <p>All products from the house of ONN are of the finest quality and have a global appeal owing to the contemporary designs and the wide range of colour availability. Each product has been conceptualised and designed after extensive market research and in accordance with the consumers’ standards and desires. The product quality of ONN is at par with any of the existent globally popular brands in its range owing to the scientific methods of conceptualising, designing, and manufacturing. We have a clear vision to maintain our brand identity and brand value and appeal to the international markets with our quality and consistency.</p>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
