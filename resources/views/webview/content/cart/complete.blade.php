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
