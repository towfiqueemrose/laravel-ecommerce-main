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
