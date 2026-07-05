# Stripe Payment Gateway Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add Stripe payment gateway alongside Cash on Delivery, with a dedicated payment selection page.

**Architecture:** Restructure checkout flow: form collects shipping info first -> session -> payment page with COD/Stripe choice. Stripe uses Checkout Sessions (hosted page). On success, order created with "Paid" status. On COD, order created with "Processing" status.

**Tech Stack:** Laravel 8, Blade, jQuery, Stripe PHP SDK, bumbummen99/shoppingcart

---

### Task 1: Install Stripe PHP SDK & Configure Environment

**Files:**
- Modify: `composer.json`
- Create: None (composer handles)

- [ ] **Install Stripe SDK**

Run: `composer require stripe/stripe-php`

- [ ] **Add Stripe config keys to `.env`**

Append to `/home/wizard/Programming/ecommerce3/.env`:
```
STRIPE_KEY=pk_test_xxxxxxxxxxxxxxxxxxxxxxxxxxxxx
STRIPE_SECRET=sk_test_xxxxxxxxxxxxxxxxxxxxxxxxxxxxx
STRIPE_WEBHOOK_SECRET=
```

- [ ] **Add Stripe service to `config/services.php`**

Add before the closing `];`:
```php
'stripe' => [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
],
```

### Task 2: Create StripeController

**Files:**
- Create: `app/Http/Controllers/StripeController.php`

- [ ] **Create StripeController with createCheckoutSession, handleSuccess, handleCancel methods**

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\StripeClient;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Orderproduct;
use App\Models\Comment;
use App\Models\Admin;
use Cart;
use Session;

class StripeController extends Controller
{
    public function createCheckoutSession()
    {
        if (!Session::has('cart') || Cart::count() == 0) {
            return redirect('/cart')->with('error', 'Your cart is empty');
        }

        $checkoutInfo = Session::get('checkout_info');
        if (!$checkoutInfo) {
            return redirect('/checkout')->with('error', 'Please fill in your shipping details');
        }

        $stripe = new StripeClient(config('services.stripe.secret'));

        $lineItems = [];
        foreach (Cart::content() as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'bdt',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price * 100,
                ],
                'quantity' => $item->qty,
            ];
        }

        $deliveryCharge = $checkoutInfo['deliveryCharge'];
        if ($deliveryCharge > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'bdt',
                    'product_data' => [
                        'name' => 'Delivery Charge',
                    ],
                    'unit_amount' => $deliveryCharge * 100,
                ],
                'quantity' => 1,
            ];
        }

        $session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => url('/stripe/success?session_id={CHECKOUT_SESSION_ID}'),
            'cancel_url' => url('/stripe/cancel'),
            'metadata' => [
                'order_subtotal' => Cart::subtotalFloat(),
                'delivery_charge' => $deliveryCharge,
            ],
        ]);

        return redirect($session->url);
    }

    public function handleSuccess(Request $request)
    {
        $sessionId = $request->session_id;
        if (!$sessionId) {
            return redirect('/cart')->with('error', 'Invalid payment session');
        }

        $stripe = new StripeClient(config('services.stripe.secret'));

        try {
            $session = $stripe->checkout->sessions->retrieve($sessionId);
        } catch (\Exception $e) {
            return redirect('/payment')->with('error', 'Could not verify payment');
        }

        if ($session->payment_status !== 'paid') {
            return redirect('/payment')->with('error', 'Payment was not completed');
        }

        $checkoutInfo = Session::get('checkout_info');
        if (!$checkoutInfo) {
            return redirect('/checkout')->with('error', 'Session expired, please checkout again');
        }

        $existingOrder = Order::where('payment_id', $session->payment_intent)->first();
        if ($existingOrder) {
            Session::put('order_id', $existingOrder->id);
            return redirect('/order/complete');
        }

        $admin = Admin::whereHas('roles', function ($q) {
            $q->where('name', 'user');
        })->where('status', 'Active')->inRandomOrder()->first();

        if (!$admin) {
            $admin = Admin::findOrFail(1);
        }

        $order = new Order();
        $order->user_id = $checkoutInfo['user_id'] ?? null;
        $order->store_id = 1;
        $order->invoiceID = $this->generateInvoiceId();
        $order->subTotal = $checkoutInfo['subTotal'];
        $order->deliveryCharge = $checkoutInfo['deliveryCharge'];
        $order->orderDate = date('Y-m-d');
        $order->Payment = 'Stripe';
        $order->status = 'Paid';
        $order->payment_id = $session->payment_intent;
        $order->payment_type_id = null;
        $order->paymentAmount = $session->amount_total / 100;
        $order->admin_id = $admin->id;
        $order->save();

        $customer = new Customer();
        $customer->order_id = $order->id;
        $customer->customerName = $checkoutInfo['customerName'];
        $customer->customerPhone = $checkoutInfo['customerPhone'];
        $customer->customerAddress = $checkoutInfo['customerAddress'];
        $customer->save();

        foreach (Cart::content() as $product) {
            $orderProduct = new Orderproduct();
            $orderProduct->order_id = $order->id;
            $orderProduct->product_id = $product->id;
            $orderProduct->productCode = $product->code;
            if ($product->options['color'] !== 'undefined') {
                $orderProduct->color = $product->options['color'];
            }
            if ($product->options['size'] !== 'undefined') {
                $orderProduct->size = $product->options['size'];
            }
            $orderProduct->productName = $product->name;
            $orderProduct->quantity = $product->qty;
            $orderProduct->productPrice = $product->price;
            $orderProduct->save();
        }

        $notification = new Comment();
        $notification->order_id = $order->id;
        $notification->comment = $order->invoiceID . ' Order Has Been Created with Stripe Payment';
        $notification->admin_id = $order->admin_id;
        $notification->save();

        Cart::destroy();
        Session::forget('checkout_info');
        Session::put('ordersubtotal', $checkoutInfo['subTotal']);
        Session::put('orderdeliverycharge', $checkoutInfo['deliveryCharge']);
        Session::put('order_id', $order->id);

        toastr()->info('Payment Successful! Order Confirmed.', 'Success', ["positionClass" => "toast-top-center"]);

        return redirect('/order/complete');
    }

    public function handleCancel()
    {
        return redirect('/payment')->with('error', 'Payment was cancelled. Please try again.');
    }

    private function generateInvoiceId()
    {
        $lastOrder = Order::latest('id')->first();
        $orderID = $lastOrder ? $lastOrder->id + 1 : 1;
        return 'BG77' . $orderID;
    }
}
```

### Task 3: Add storeCheckout & showPayment to CartController

**Files:**
- Modify: `app/Http/Controllers/CartController.php`

- [ ] **Add storeCheckout method**

Add after `checkout()` method:
```php
public function storeCheckout(Request $request)
{
    $products = Cart::content();
    if (!Session::has('cart') || Cart::count() == 0) {
        return redirect('/empty-cart');
    }

    $request->validate([
        'customerName' => 'required|string|max:255',
        'customerAddress' => 'required|string|max:500',
        'customerPhone' => 'required|string|min:11|max:11',
        'deliveryCharge' => 'required|numeric',
    ]);

    Session::put('checkout_info', [
        'customerName' => $request->customerName,
        'customerAddress' => $request->customerAddress,
        'customerPhone' => $request->customerPhone,
        'deliveryCharge' => $request->deliveryCharge,
        'subTotal' => $request->subTotal,
        'user_id' => $request->user_id,
    ]);

    return redirect('/payment');
}
```

- [ ] **Add showPayment method**

Add after `storeCheckout()`:
```php
public function showPayment()
{
    if (!Session::has('cart') || Cart::count() == 0) {
        return redirect('/cart')->with('error', 'Your cart is empty');
    }

    $checkoutInfo = Session::get('checkout_info');
    if (!$checkoutInfo) {
        return redirect('/checkout');
    }

    $cartProducts = Cart::content();
    return view('webview.content.cart.payment', compact('cartProducts', 'checkoutInfo'));
}
```

- [ ] **Add Request import at top of file**

Add `use Illuminate\Http\Request;` if not already present (it is).

### Task 4: Add confirmCod to OrderController

**Files:**
- Modify: `app/Http/Controllers/OrderController.php`

- [ ] **Add confirmCod method**

Add after `updatepaymentmethood()`:
```php
public function confirmCod()
{
    if (!Session::has('cart') || Cart::count() == 0) {
        return redirect('/empty-cart');
    }

    $checkoutInfo = Session::get('checkout_info');
    if (!$checkoutInfo) {
        return redirect('/checkout')->with('error', 'Please fill in your shipping details');
    }

    $products = Cart::content();

    $admin = Admin::whereHas('roles', function ($q) {
        $q->where('name', 'user');
    })->where('status', 'Active')->inRandomOrder()->first();

    if (!$admin) {
        $admin = Admin::findOrFail(1);
    }

    $order = new Order();
    $order->user_id = $checkoutInfo['user_id'] ?? null;
    $order->store_id = 1;
    $order->invoiceID = $this->uniqueID();
    $order->subTotal = $checkoutInfo['subTotal'];
    $order->deliveryCharge = $checkoutInfo['deliveryCharge'];
    $order->orderDate = date('Y-m-d');
    $order->Payment = 'Cash On Delivery';
    $order->status = 'Processing';
    $order->admin_id = $admin->id;
    $order->save();

    $customer = new Customer();
    $customer->order_id = $order->id;
    $customer->customerName = $checkoutInfo['customerName'];
    $customer->customerPhone = $checkoutInfo['customerPhone'];
    $customer->customerAddress = $checkoutInfo['customerAddress'];
    $customer->save();

    foreach ($products as $product) {
        $orderProduct = new Orderproduct();
        $orderProduct->order_id = $order->id;
        $orderProduct->product_id = $product->id;
        $orderProduct->productCode = $product->code;
        if ($product->options['color'] !== 'undefined') {
            $orderProduct->color = $product->options['color'];
        }
        if ($product->options['size'] !== 'undefined') {
            $orderProduct->size = $product->options['size'];
        }
        $orderProduct->productName = $product->name;
        $orderProduct->quantity = $product->qty;
        $orderProduct->productPrice = $product->price;
        $orderProduct->save();
    }

    $notification = new Comment();
    $notification->order_id = $order->id;
    $notification->comment = $order->invoiceID . ' Order Has Been Created for ' . $admin->name;
    $notification->admin_id = $order->admin_id;
    $notification->save();

    Cart::destroy();
    Session::forget('checkout_info');
    Session::put('ordersubtotal', $checkoutInfo['subTotal']);
    Session::put('orderdeliverycharge', $checkoutInfo['deliveryCharge']);
    Session::put('order_id', $order->id);

    toastr()->info('Order Confirmed! Pay on delivery.', 'Success', ["positionClass" => "toast-top-center"]);

    return redirect('/order/complete');
}
```

### Task 5: Update Routes

**Files:**
- Modify: `routes/web.php`

- [ ] **Add new routes and update existing one**

Add these routes after the existing cart/order routes:
```php
Route::post('checkout/store', [CartController::class, 'storeCheckout']);
Route::get('payment', [CartController::class, 'showPayment']);
Route::post('order/confirm-cod', [OrderController::class, 'confirmCod']);
Route::post('stripe/checkout', [StripeController::class, 'createCheckoutSession']);
Route::get('stripe/success', [StripeController::class, 'handleSuccess']);
Route::get('stripe/cancel', [StripeController::class, 'handleCancel']);
```

Add `use App\Http\Controllers\StripeController;` at top.

### Task 6: Update checkout.blade.php Form Action

**Files:**
- Modify: `resources/views/webview/content/cart/checkout.blade.php`

- [ ] **Change form action and button text**

Change line 42 from:
```html
<form action="{{ url('press/order') }}" method="POST"
```
to:
```html
<form action="{{ url('checkout/store') }}" method="POST"
```

Change line 88 from:
```html
<i class="spinner fa fa-spinner fa-spin"></i> Confirm Order
```
to:
```html
<i class="spinner fa fa-spinner fa-spin"></i> Proceed to Payment
```

### Task 7: Replace payment.blade.php with Payment Selection Page

**Files:**
- Modify: `resources/views/webview/content/cart/payment.blade.php`

- [ ] **Replace content with full payment page with COD and Stripe options**

New content:
```blade
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
```

### Task 8: Update complete.blade.php to Show Order Details

**Files:**
- Modify: `resources/views/webview/content/cart/complete.blade.php`

- [ ] **Replace content to show actual order info**

New content:
```blade
@extends('webview.master')

@section('maincontent')
@section('title')
    {{ env('APP_NAME') }}-Order Complete
@endsection
<br>
<div class="container pb-5 mb-sm-4 mt-4 mb-4">
    <div class="pt-5 pb-5" style="margin-bottom:5px">
        <div class="card py-3 mt-sm-3">
            <div class="card-body text-center">
                @if(isset($order))
                    <i class="fas fa-check-circle" style="font-size: 64px; color: #28a745;"></i>
                    <h2 class="h4 pb-2 mt-3" style="color:green">Your order has been placed successfully!</h2>
                    <p class="mb-1"><strong>Invoice:</strong> {{ $order->invoiceID }}</p>
                    <p class="mb-1"><strong>Payment Method:</strong> {{ $order->Payment }}</p>
                    <p class="mb-1"><strong>Status:</strong> {{ $order->status }}</p>
                    <p class="mb-3"><strong>Total:</strong> ৳ {{ $order->subTotal + $order->deliveryCharge }}</p>
                    @if($order->Payment == 'Cash On Delivery')
                        <p style="color: #856404;">Our call center will call you to confirm your order.</p>
                    @else
                        <p style="color: #155724;">Payment received. Your order is confirmed.</p>
                    @endif
                    <a class="btn btn-primary mt-3" href="{{ url('/') }}">Browse Products</a>
                @else
                    <h2 class="h4 pb-3" style="color:green">Your order has been placed successfully.</h2>
                    <a class="btn btn-primary mt-3" href="{{ url('/') }}">Browse Products</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
```

### Task 9: Update trackorder.blade.php Payment Display

**Files:**
- Modify: `resources/views/webview/content/cart/trackorder.blade.php`

- [ ] **Update payment method display to handle new values**

Change lines 103-110 from:
```blade
<td class="w-50 strong-600">Payment method:</td>
<td>
    @if ($orders->Payment == 'C-O-D')
        Cash On Delivery
    @else
        Online Payment
    @endif
</td>
```
to:
```blade
<td class="w-50 strong-600">Payment method:</td>
<td>
    @if ($orders->Payment == 'Cash On Delivery' || $orders->Payment == 'C-O-D')
        Cash On Delivery
    @elseif ($orders->Payment == 'Stripe')
        Stripe (Card)
    @else
        Online Payment
    @endif
</td>
```

### Task 10: Verify the Implementation

- [ ] **Check PHP syntax**

Run: `php -l app/Http/Controllers/StripeController.php`
Run: `php -l app/Http/Controllers/CartController.php`
Run: `php -l app/Http/Controllers/OrderController.php`

- [ ] **Check routes are registered**

Run: `php artisan route:list | grep -E "stripe|checkout/store|payment|confirm-cod"`

- [ ] **Confirm Stripe SDK is installed**

Run: `php -r "require 'vendor/autoload.php'; echo class_exists('Stripe\StripeClient') ? 'OK' : 'MISSING';"`
