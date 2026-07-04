<div class="product-card">
    <div class="product-card__image">
        <a href="{{ url('product/' . $product->ProductSlug) }}">
            <img src="{{ asset($product->ViewProductImage ?? $product->ProductImage) }}"
                 alt="{{ $product->ProductName }}" loading="lazy">
        </a>
        @if($product->Discount > 0)
            <span class="product-card__badge">-{{ $product->Discount }}%</span>
        @endif
        <div class="product-card__actions">
            <button class="product-card__add-cart"
                    onclick="{{ $action ?? 'addtocart' }}({{ $product->id }})"
                    type="button">
                <i class="fas fa-shopping-cart"></i>
                {{ $buttonText ?? 'Add to Cart' }}
            </button>
        </div>
    </div>
    <div class="product-card__info">
        <h3 class="product-card__name">
            <a href="{{ url('product/' . $product->ProductSlug) }}">{{ $product->ProductName }}</a>
        </h3>
        <div class="product-card__price">
            @if(($product->ProductRegularPrice ?? 0) > ($product->ProductSalePrice ?? 0))
                @php
                    $regular = $product->ProductRegularPrice;
                    $sale = $product->ProductSalePrice;
                    $percent = $regular > 0 ? round((($regular - $sale) / $regular) * 100) : 0;
                @endphp
                <span class="product-card__price-old">৳{{ round($regular) }}</span>
                <span class="product-card__price-off">-{{ $percent }}%</span>
            @endif
            <span class="product-card__price-sale">৳{{ round($product->ProductSalePrice) }}</span>
        </div>
    </div>
</div>
