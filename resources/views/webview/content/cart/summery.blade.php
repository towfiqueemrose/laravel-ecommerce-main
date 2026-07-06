<div class="container">
    <div class="row cols-xs-space cols-sm-space cols-md-space">
        <div class="col-md-8" id="smp">
            <div class="form-default bg-white px-1 py-3" style="padding-top: 10px;margin-bottom: 20px;">
                <div class="">
                    <div class="">
                        <table class="table-cart border-bottom">
                            <thead>
                                <tr>
                                    <th class="product-image" style="width: 80px; padding: 10px 0 10px 8px;">Product</th>
                                    <th class="product-name" style="width: 40%; padding: 10px 0 10px 15px;">Product Name</th>
                                    <th class="product-price text-center d-none d-md-table-cell" style="width: 15%; padding: 10px 0;">Price</th>
                                    <th class="product-quantity text-center d-none d-md-table-cell" style="width: 15%; padding: 10px 0;">Quantity</th>
                                    <th class="product-total text-center" style="width: 15%; padding: 10px 0;">Total</th>
                                    <th class="product-remove text-center" style="width: 15%; padding: 10px 0;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cartProducts as $cartProduct)
                                    <tr>
                                        <td class="product-image" style="padding: 12px 0 12px 8px;">
                                            <a href="#" class="d-flex align-items-center justify-content-center">
                                                <img style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px; display: block;" loading="lazy" src="{{ asset($cartProduct->image) }}">
                                            </a>
                                        </td>

                                        <td class="product-name" id="cartpron" style="padding: 12px 0 12px 15px;">
                                            <span class="d-block" id="cartproname" style="padding-right: 12px; font-weight: 500; font-size: 14px;">{{ $cartProduct->name }}</span>
                                        </td>

                                        <td class="product-price text-center d-none d-md-table-cell" style="padding: 12px 0; font-size: 15px;">
                                            <span class="pr-3" id="qtyPro{{ $cartProduct->rowId }}">{{ $cartProduct->qty }}</span>
                                            <span class="pr-3 text-muted">&times; ৳{{ $cartProduct->price }}</span>
                                            <input type="hidden" name="priceOf" id="priceOf{{ $cartProduct->rowId }}" value="{{ $cartProduct->price }}">
                                        </td>

                                        <td class="product-quantity text-center d-none d-md-table-cell" style="padding: 12px 0;">
                                            <div class="input-group input-group--style-2 mx-auto" id="quantityup" style="width: 100px; display: inline-flex;">
                                                <input type="number" name="quantity" class="form-control input-number text-center" id="proQuantity{{ $cartProduct->rowId }}" placeholder="1" value="{{ $cartProduct->qty }}" min="1" max="10" onchange="updateQuantity('{{ $cartProduct->rowId }}', this)">
                                            </div>
                                        </td>
                                        <td class="product-total text-center" style="padding: 12px 0; font-weight: 700; color: var(--theme-color);">
                                            <span>৳<span id="pricePro{{ $cartProduct->rowId }}">{{ $cartProduct->qty * $cartProduct->price }}</span></span>
                                        </td>

                                        <td class="product-remove text-center" style="padding: 12px 0;">
                                            <a type="button" style="cursor: pointer; transition: 0.2s;" onclick="removeFromCartItemHead('{{ $cartProduct->rowId }}')">
                                                <i class="fa fa-trash" style="color: #e74c3c; font-size: 16px;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>

                <div class="row align-items-center pt-4">
                    <div class="col-6 col-md-6 col-sm-6" style="margin-top: 15px;">
                        <a href="{{ url('/') }}" class="link link--style-3" style="color:#e62e04;margin: 12px;">
                            <i class="la la-mail-reply"></i>
                            Return to shop
                        </a>
                    </div>
                    <div class=" col-6 col-md-6 col-sm-6 text-right" style="padding-right: 26px;">
                        <a @if (count($cartProducts) > 0) @else disabled @endif href="{{ url('/checkout') }}"
                            class="btn btn-primary" style="margin-top: 10px; margin-bottom: 10px; background-color: var(--theme-color); border-color: var(--theme-color);">Next Step</a>
                    </div>
                </div>
            </div>
            <!-- </form> -->
        </div>

        <div class="col-md-4 ml-lg-auto" id="smp">
            <div class="card sticky-top">
                <div class="card-title py-3">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h3 class="heading heading-3 strong-400 mb-0">
                                <span style="padding: 10px;font-size: 16px;font-weight: bold;">Summary</span>
                            </h3>
                        </div>

                        <div class="col-6 text-right">
                            <span class="badge badge-md badge-success" style="padding: 6px; padding-right: 10px;">{{ count($cartProducts) }} Items</span>
                        </div>
                    </div>
                </div>

                <div class="card-body">


                    <table class="table-cart table-cart-review">
                        <thead>
                            <tr>
                                <th class="product-name" style="padding: 6px;">Product</th>
                                <th class="product-total text-right" style="padding: 6px;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cartProducts as $cartProduct)
                                <tr class="cart_item">
                                    <td class="product-name" style="padding-left: 6px;font-size: 13px !important;">
                                        {{ $cartProduct->name }}
                                        <strong class="product-quantity">× {{ $cartProduct->qty }}</strong>
                                    </td>
                                    <td class="product-total text-right" style="padding-right: 6px;">
                                        <span class="pl-4"
                                            style="font-size: 13px !important;">৳{{ $cartProduct->qty * $cartProduct->price }}</span>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>

                    <table class="table-cart table-cart-review">

                        <tfoot>
                            <tr class="cart-subtotal">
                                <th style="font-weight: normal;padding-bottom: 8px;">Subtotal</th>
                                <td class="text-right">
                                    <span class="strong-600"
                                        style="font-weight: normal;">৳{{ Cart::subtotal() }}</span>
                                </td>
                            </tr>

                            <tr class="cart-shipping">
                                <th style="font-weight: normal;padding-bottom: 8px;">Tax</th>
                                <td class="text-right">
                                    <span class="text-italic" style="font-weight: normal;">৳0</span>
                                </td>
                            </tr>

                            <tr class="cart-shipping">
                                <th style="font-weight: normal;padding-bottom: 8px;">Total Shipping</th>
                                <td class="text-right">
                                    <span class="text-italic shiop" style="font-weight: normal;">৳0</span>
                                </td>
                            </tr>



                            <tr class="cart-total">
                                <th><span class="strong-600">Total</span></th>
                                <td class="text-right">
                                    <strong>৳ <span class="g_total">{{ Cart::subtotal() }}</span></strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
