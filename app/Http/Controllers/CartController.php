<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Cart;
use App\Models\Order;
use Session;

class CartController extends Controller
{
    public function addtocart(Request $request){ 
        $pid = $request->product_id;
        $cartProduct = Product::where('id',$pid)->first();

        Cart::add([
            'id' => $request->product_id,
            'name' => $cartProduct->ProductName,
            'code' => $cartProduct->ProductSku,
            'price' => $cartProduct->ProductSalePrice,
            'qty' => $request->qty,
            'weight' => 1,
            'image' => $cartProduct->ProductImage,
            'options' => [
                'size' => $request->size,
                'color' => $request->color,
            ],

        ]);
        
        if ($request->ajax()) {
            return response()->json('success',200);
        }
        return redirect('checkout');
    }


    public function updatecart(Request $request){
        $rowId = $request->rowId;
        Cart::update($rowId, $request->qty);

        $quentity = [
            'qty' => $request->qty,
        ];
        return response()->json($quentity , 200);
    }

    public function destroy(Request $request){
        Cart::remove($request->rowId);
        $olditem = count(Cart::content());
        if($olditem == '0'){
            Cart::destroy();
            return response()->json('empty',200);
        }
        $cartProducts = Cart::content();
        return view('webview.content.product.cartproductmodal')->with('cartProducts', $cartProducts);

    }

    public function getcartcontent(){
        $cartProducts = Cart::content();
        return view('webview.content.product.cartproductmodal')->with('cartProducts', $cartProducts);
    }

    public function getcheckcartcontent(){
        $cartProducts = Cart::content();
        return view('webview.content.product.checkcartview')->with('cartProducts', $cartProducts);
    }

    public function cartcontent(){
        $cartProducts = Cart::content();
        $num=count($cartProducts);
        $am=Cart::subtotal();
        $arr=['item'=>$num,'amount'=>$am];
        return response()->json($arr, 200);
    }

    public function cart(){
        return view('webview.content.cart.cart');
    }

    public function loadcart(){
        $cartProducts = Cart::content();
        return view('webview.content.cart.summery')->with('cartProducts', $cartProducts);
    }

    public function checkout(){
        $cartProducts = Cart::content();
        return view('webview.content.cart.checkout')->with('cartProducts', $cartProducts);
    }

    public function storeCheckout(Request $request)
    {
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

     public function payment(){
        return redirect('/checkout');
    }

    public function complete(){
        $id=Session::get('order_id');
        $order =  Order::with([
                        'orderproducts'=>function ($query) { $query->select('id','order_id','productName','quantity','productPrice');},
                        'admins'=> function ($query) { $query->select('id','name');},
                    ])->join('customers', 'customers.order_id', '=', 'orders.id')
                    ->select('orders.*', 'customers.order_id','customers.customerPhone', 'customers.customerName', 'customers.customerAddress')
                    ->where('orders.id', $id)
                    ->first();
        return view('webview.content.cart.complete',['order'=>$order]);
    }



}