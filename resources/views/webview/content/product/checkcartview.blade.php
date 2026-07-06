@if (count($cartProducts) > 0)
    <div class="product-summary" style="max-height: 350px; overflow-y: auto; overflow-x: hidden; display: flex; flex-direction: column;">
        @forelse ($cartProducts as $item)
            <div class="row align-items-center mb-3">
                <div class="col-3">
                    <div class="image">
                        <a href="#">
                            <img src="{{ asset($item->image) }}" alt="" style="width: 100%; height: 60px; object-fit: cover; border-radius: 6px; border: 1px solid #eee;">
                        </a>
                    </div>
                </div>
                <div class="col-8" style="padding-left: 0">
                    <h3 class="name" style="margin: 0; margin-bottom: 4px; line-height: 1.3;">
                        <a href="#" style="font-size: 13px; font-weight: 500; color: #333; text-decoration: none; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $item->name }}</a>
                    </h3>
                    <div class="price" style="font-size: 14px; font-weight: 700; color: var(--theme-color);">৳{{ $item->price }} <span style="font-size: 12px; color: #888; font-weight: 400;">x {{ $item->qty }}</span></div>
                </div>
                <div class="col-1 text-end action"> 
                    <a type="button" style="cursor: pointer; color: #dc3545; transition: 0.2s;" onclick="removeFromCartItemHead('{{ $item->rowId }}')" title="Remove Item">
                        <i class="fa fa-trash"></i>
                    </a> 
                </div>
            </div>
        @empty
        @endforelse

    </div>
    <!-- /.cart-item -->
    <hr style="margin: 10px 0; border-color: #eee;">
    <div class="clearfix cart-total">
        <div class="d-flex justify-content-between align-items-center mb-3"> 
            <span class="text" style="font-size: 14px; font-weight: 600; color: #555;">Sub Total :</span>
            <span class='price' style="font-size: 16px; font-weight: 700; color: #333;">৳{{ Cart::subtotal() }}</span>
        </div>
        <div class="clearfix"></div>
        <a href="{{ url('/cart') }}" class="btn btn-primary d-flex justify-content-center align-items-center" style="width: 100%; border-radius: 6px; font-weight: 600; padding: 10px; background: var(--theme-color); border-color: var(--theme-color);">View Cart</a>
    </div>
    <!-- /.cart-total-->
@else
    <div class="clearfix cart-total text-center" style="padding: 30px 10px;">
        <i class="fa-solid fa-cart-shopping mb-2" style="font-size: 40px; color: #ddd;"></i>
        <p style="font-size: 16px; font-weight: 500; color: #888; margin: 0;">Your cart is empty!</p>
    </div>
@endif
