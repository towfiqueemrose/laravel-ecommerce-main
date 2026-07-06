<div class="cart-modal-header">
    <button type="button" class="cart-modal-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
    <div class="cart-success-icon">
        <svg class="cart-check-circle" viewBox="0 0 24 24" width="56" height="56">
            <circle class="cart-check-bg" cx="12" cy="12" r="11" fill="none" stroke="#28a745" stroke-width="2"/>
            <path class="cart-check-mark" d="M7 12l3 3 7-7" fill="none" stroke="#28a745" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>
    <h3 class="cart-success-text"><span id="itemCount">{{ count($cartProducts) }}</span> Item(s) added to your cart!</h3>
</div>

<div class="cart-modal-body">
    <h4 class="cart-items-heading">
        <i class="fas fa-shopping-bag me-2"></i>Cart Items
    </h4>

    <div class="cart-items-list" id="itemlest">
        @forelse ($cartProducts as $item)
            <div class="cart-item">
                <div class="cart-item-image">
                    <img src="{{ asset($item->image) }}" alt="{{ $item->name }}">
                </div>
                <div class="cart-item-details">
                    <a href="#" class="cart-item-name">{{ $item->name }}</a>
                    <div class="cart-item-meta">
                        <span class="cart-item-qty">Qty: {{ $item->qty }}</span>
                        @if($item->options && $item->options->has('color'))
                            <span class="cart-item-color">Color: {{ $item->options->get('color') }}</span>
                        @endif
                        @if($item->options && $item->options->has('size'))
                            <span class="cart-item-size">Size: {{ $item->options->get('size') }}</span>
                        @endif
                    </div>
                    <span class="cart-item-price">৳{{ number_format($item->qty * $item->price, 2) }}</span>
                </div>
                <div class="cart-item-remove">
                    <button type="button" onClick="removeFromCartItem('{{ $item->rowId }}')" title="Remove item">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        @empty
        @endforelse
    </div>
</div>

<div class="cart-modal-footer">
    <div class="cart-subtotal">
        <span class="cart-subtotal-label">Subtotal</span>
        <span class="cart-subtotal-amount">৳ <span id="totalAmountCart">{{ Cart::subtotal() }}</span></span>
    </div>
    <div class="cart-actions">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Continue Shopping</button>
        <a href="{{ url('checkout') }}" class="btn btn-primary">Submit Order</a>
    </div>
</div>