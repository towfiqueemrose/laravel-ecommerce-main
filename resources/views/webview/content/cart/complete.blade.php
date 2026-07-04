@extends('webview.master')

@section('maincontent')
@section('title')
    {{ env('APP_NAME') }}-Complete
@endsection
     <br>
    <div class="container pb-5 mb-sm-4 mt-4 mb-4">
        <div class="pt-5 pb-5" style="margin-bottom:5px">
            <div class="card py-3 mt-sm-3">
                <div class="card-body text-center">
                    <h2 class="h4 pb-3" style="color:green">Your order has been placed successfully. Our call center will call you to confirm your order.</h2>
                    <a class="btn btn-primary mt-3" href="{{url('/')}}">Browse Products</a>
                </div>
            </div>
        </div>
    </div>
@endsection
