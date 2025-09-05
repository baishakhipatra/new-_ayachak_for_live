@extends('front.profile.layouts.app')

@section('profile-content')
<style>
.order-card-footer .cart_btn.missingVariationSelection {
    pointer-events: none;
    cursor: not-allowed;
    opacity: 0.6;
}
</style>

<div class="col-sm-7">
    <div class="profile-card">
        <h3>Wishlist</h3>

        {{-- @if (Session::get('success'))
            <div class="alert alert-success"> {{Session::get('success')}} </div>
        @endif
        @if (Session::get('failure'))
            <div class="alert alert-danger"> {{Session::get('failure')}} </div>
        @endif --}}

        @forelse ($data as $wishlistKey => $wishlistValue)
        <div class="order-card">
            <div class="order-card-body">
                <div class="order-product-card">
                    <figure>
                        <a href="{{route('front.product.detail', $wishlistValue->productDetails->slug)}}"><img src="{{asset($wishlistValue->productDetails->image)}}" /></a>
                    </figure>
                    <figcaption>
                        <h6>Style #{{$wishlistValue->productDetails->style_no}}</h6>
                        <h4><a href="{{route('front.product.detail', $wishlistValue->productDetails->slug)}}">{{$wishlistValue->productDetails->name}}</a></h4>
                        <h5>
                            Price: <span>&#8377;{{$wishlistValue->productDetails->offer_price}}</span> 
                            {{-- | Size: <span>XXL</span> | Color: <span>Red</span>  --}}
                            | Qty: <span>1</span>
                        </h5>
                    </figcaption>
                </div>
            </div>
            <div class="order-card-footer">
                <div class="row">
                    <div class="col-sm-6">
                        <form method="POST" action="{{route('front.cart.add')}}" class="d-flex">@csrf
                            <input type="hidden" name="product_id" value="{{$wishlistValue->productDetails->id}}">
                            <input type="hidden" name="product_name" value="{{$wishlistValue->productDetails->name}}">
                            <input type="hidden" name="product_style_no" value="{{$wishlistValue->productDetails->style_no}}">
                            <input type="hidden" name="product_image" value="{{asset($wishlistValue->productDetails->image)}}">
                            <input type="hidden" name="product_slug" value="{{$wishlistValue->productDetails->slug}}">
                            <input type="hidden" name="product_variation_id" value="">
                            <input type="hidden" name="price" value="{{$wishlistValue->productDetails->price}}">
                            <input type="hidden" name="offer_price" value="{{$wishlistValue->productDetails->offer_price}}">
                            <input type="hidden" name="qty" value="1">
                            <button type="submit" id="addToCart__btn" class="cart_btn @if(count($wishlistValue->productDetails->colorSize) > 0) missingVariationSelection @endif">Add to Cart</button>
                        </form>
                        @if(count($wishlistValue->productDetails->colorSize) > 0) <a href="{{route('front.product.detail', $wishlistValue->productDetails->slug)}}" class="small text-muted mb-0 mt-2">Click here to select color & size first</a> @endif
                        {{-- <button class="cart_btn">Add to Cart</button> --}}
                    </div>
                    <div class="col-sm-6 text-sm-right">
                        <form method="POST" action="{{route('front.wishlist.remove')}}">@csrf
                            <input type="hidden" name="product_id" value="{{$wishlistValue->product_id}}">
                            <button type="submit" class="remove_btn">Remove from List</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
		@empty
		<section class="cart-wrapper">
			<div class="container">
				<div class="complele-box">
					<figure>
						<img src="{{ asset('img/close.svg') }}" height="100">
					</figure>
					<figcaption>
						<h2>You have not wishlisted any products yet</h2>
						<p>You can stay here or get back to home.</p>
						<a href="{{ URL::to('/') }}">Back to Home</a>
					</figcaption>
				</div>
			</div>
		</section>
        @endforelse

    </div>
</div>
@endsection