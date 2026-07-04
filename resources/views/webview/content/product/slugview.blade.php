<div class="row g-3 pt-2 pb-2" style="background: white;">

    @forelse ($slugproducts as $categoryproduct)
    <div class="col-6 col-lg-2">
        <div class="product-card">
            <div class="product-image-wrapper">
                <a href="{{ url('product/' . $categoryproduct->ProductSlug) }}">
                    <img src="{{ asset($categoryproduct->ProductImage) }}"
                        alt="{{ $categoryproduct->ProductName }}" loading="lazy">
                </a>
                @if($categoryproduct->Discount > 0)
                    <span class="discount-badge">-{{ $categoryproduct->Discount }}%</span>
                @endif
            </div>
            <div class="product-info">
                <h2 class="product-name text-truncate">
                    <a href="{{ url('product/' . $categoryproduct->ProductSlug) }}">{{ $categoryproduct->ProductName }}</a>
                </h2>
                <div class="price-box">
                    @if($categoryproduct->ProductRegularPrice > $categoryproduct->ProductSalePrice)
                        <del class="old-price">৳{{ round($categoryproduct->ProductRegularPrice) }}</del>
                    @endif
                    <span class="sale-price">৳{{ round($categoryproduct->ProductSalePrice) }}</span>
                </div>
            </div>
            <form name="form" action="{{url('add-to-cart')}}" method="POST">
                @method('POST')
                @csrf
                <input type="text" name="color" id="product_colorold" hidden>
                <input type="text" name="size" id="product_sizeold" hidden>
                <input type="text" name="product_id" value="{{ $categoryproduct->id }}" hidden>
                <input type="text" name="qty" value="1" id="qtyor" hidden>
                <button class="btn-add-cart" type="submit">অর্ডার করুন</button>
            </form>
        </div>
    </div>
    @empty
        <h2 class="p-4 text-center"><b>No Products found...</b></h2>
    @endforelse
</div>
