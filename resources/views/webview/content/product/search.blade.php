<div class="products best-product">
    @if (count($searchcontents) > 0)
        @forelse ($searchcontents as $searchcontent)
            <div class="d-flex align-items-center gap-3 p-2 border-bottom" style="border-color: #f0f0f0 !important;">
                <a href="{{ url('product/' . $searchcontent->ProductSlug) }}" style="flex-shrink: 0;">
                    <img src="{{ asset($searchcontent->ProductImage) }}"
                        alt="{{ $searchcontent->ProductName }}"
                        style="height: 65px;width: 65px;object-fit: cover;border-radius: 8px;">
                </a>
                <div style="flex: 1;min-width: 0;">
                    <h2 class="product-name" style="font-size: 13px;margin: 0 0 4px;">
                        <a href="{{ url('product/' . $searchcontent->ProductSlug) }}" style="color: #1a1a1a;text-decoration: none;">{{ $searchcontent->ProductName }}</a>
                    </h2>
                    <div class="price-box" style="display:flex;align-items:center;gap:6px;">
                        @if($searchcontent->ProductRegularPrice > $searchcontent->ProductSalePrice)
                            <del style="font-size:12px;color:#999;">৳{{ round($searchcontent->ProductRegularPrice) }}</del>
                        @endif
                        <span style="font-size:14px;font-weight:700;color:#24a86c;">৳{{ round($searchcontent->ProductSalePrice) }}</span>
                    </div>
                </div>
            </div>
        @empty
        @endforelse
    @else
        <div class="text-center p-3" style="color:#999;">
            No Products found...
        </div>
    @endif
</div>
