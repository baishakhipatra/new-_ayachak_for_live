<ul class="product-list product-list__shop">
    @forelse($data as $product)
        <li>
            <div class="pro-inner">
                <figure>
                    <a href="{{ route('front.shop.detail', $product->slug) }}">
                        <img src="{{ $product->image ? asset($product->image) : asset('assets/images/placeholder-product.jpg') }}" alt="{{ $product->name }}">
                    </a>
                </figure>
                <figcaption>
                    <a href="{{ route('front.shop.detail', $product->slug) }}">
                        <h3>{{ ucwords($product->name) }}</h3>
                    </a>
                    <div class="price-group">
                        @if($product->offer_price > 0 && $product->offer_price < $product->price)
                            <span class="original-price strike">₹{{ number_format($product->price, 2) }}</span>
                            <span class="sale-price">₹{{ number_format($product->offer_price, 2) }}</span>
                            <div class="sale-persentage">
                                {{ round((($product->price - $product->offer_price) / $product->price) * 100) }}% save
                            </div>
                            <div class="sale-badge">Sale</div>
                        @else
                            <span class="original-price">₹{{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>
                    <a href="{{ route('front.shop.detail', $product->slug) }}" class="bton btn-fill">Shop Now</a>
                </figcaption>
            </div>
        </li>
    @empty
        <li>No products found.</li>
    @endforelse
</ul>

{{-- Pagination --}}
<div class="pagination-stack">
    {{ $data->links() }}
</div>
