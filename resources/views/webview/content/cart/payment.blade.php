@extends('webview.master')

@section('maincontent')
@section('title')
    {{ env('APP_NAME') }}-Payment
@endsection

<br>
<section class="section-content padding-y bg slidetop">
    <div class="container p-0">
        <div class="row">
            <div class="col-md-6">
                <aside class="card mb-4">
                    <article class="card-body">
                        <header class="mb-4">
                            <h4 class="card-title" style="font-size: 18px; font-weight: 600; color: #333;">Choose Payment Method</h4>
                        </header>

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="card payment-option" style="border: 2px solid #e0e0e0; border-radius: 10px; cursor: pointer; transition: all 0.3s;">
                                    <div class="card-body text-center py-4">
                                        <i class="fas fa-money-bill-wave" style="font-size: 48px; color: #28a745;"></i>
                                        <h5 class="mt-3" style="font-weight: 600;">Cash on Delivery</h5>
                                        <p class="text-muted mb-3">Pay when you receive your order</p>
                                        <form action="{{ url('order/confirm-cod') }}" method="POST" class="from-prevent-multiple-submits">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-lg btn-block from-prevent-multiple-submits" style="border-radius: 8px; font-weight: 600;">
                                                <i class="spinner fa fa-spinner fa-spin"></i> Confirm Order
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="card payment-option" style="border: 2px solid #e0e0e0; border-radius: 10px; cursor: pointer; transition: all 0.3s;">
                                    <div class="card-body text-center py-4">
                                        <i class="fab fa-cc-stripe" style="font-size: 48px; color: #6772e5;"></i>
                                        <h5 class="mt-3" style="font-weight: 600;">Pay with Stripe</h5>
                                        <p class="text-muted mb-3">Secure payment via credit/debit card</p>
                                        <form action="{{ url('stripe/checkout') }}" method="POST" class="from-prevent-multiple-submits">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-lg btn-block from-prevent-multiple-submits" style="background: #6772e5; border-color: #6772e5; border-radius: 8px; font-weight: 600;">
                                                <i class="spinner fa fa-spinner fa-spin"></i> Pay with Stripe
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </aside>
            </div>

            <div class="col-md-6 orderDetails">
                <aside class="card mb-4">
                    <article class="card-body">
                        <header class="mb-4">
                            <h4 class="card-title" style="font-size: 16px;">Shipping Details</h4>
                        </header>
                        <p><strong>Name:</strong> {{ $checkoutInfo['customerName'] }}</p>
                        <p><strong>Address:</strong> {{ $checkoutInfo['customerAddress'] }}</p>
                        <p><strong>Phone:</strong> {{ $checkoutInfo['customerPhone'] }}</p>
                        <p><strong>Delivery Charge:</strong> ৳ {{ $checkoutInfo['deliveryCharge'] }}</p>
                    </article>
                </aside>

                <aside class="card">
                    <article class="card-body">
                        <header class="mb-4">
                            <h4 class="card-title" style="font-size: 16px;">Your Order</h4>
                        </header>
                        <div class="row">
                            <table class="table border-bottom">
                                @forelse ($cartProducts as $cartProduct)
                                    <tr>
                                        <td class="product-image" style="width: 80px;">
                                            <img src="{{ asset($cartProduct->image) }}" style="max-width: 60px;">
                                        </td>
                                        <td class="product-name">
                                            <span class="d-block">{{ $cartProduct->name }}</span>
                                            <small class="text-muted">Qty: {{ $cartProduct->qty }} × ৳{{ $cartProduct->price }}</small>
                                        </td>
                                        <td class="text-right">
                                            ৳{{ $cartProduct->qty * $cartProduct->price }}
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </table>
                        </div>
                    </article>
                    <article class="card-body border-top">
                        <dl class="row">
                            <dt class="col-sm-8">Subtotal:</dt>
                            <dd class="col-sm-4 text-right"><strong>৳ {{ Cart::subtotalFloat() }}</strong></dd>
                            <dt class="col-sm-8">Delivery charge:</dt>
                            <dd class="col-sm-4 text-danger text-right"><strong>৳ {{ $checkoutInfo['deliveryCharge'] }}</strong></dd>
                            <dt class="col-sm-8">Total:</dt>
                            <dd class="col-sm-4 text-right"><strong class="h5 text-dark">৳ {{ Cart::subtotalFloat() + $checkoutInfo['deliveryCharge'] }}</strong></dd>
                        </dl>
                    </article>
                </aside>
            </div>
        </div>
    </div>
</section>
<br>

<style>
    .payment-option:hover {
        border-color: var(--theme-color) !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .spinner {
        display: none;
    }
</style>

<script type="text/javascript">
    (function() {
        $('.from-prevent-multiple-submits').on('submit', function() {
            $('.from-prevent-multiple-submits').attr('disabled', 'true');
            $('.spinner').css('display', 'inline');
        })
    })();
</script>
@endsection
