@extends('webview.master')

@section('maincontent')
@section('title')
    {{ env('APP_NAME') }}-Checkout
@endsection

    {{-- //no cart --}}
    @if (!Session::has('cart'))
        <div class="container pb-5 mb-sm-4">
            <div class="pt-5">
                <div class="card py-3 mt-sm-3" style="min-height: 309px;">
                    <div class="card-body text-center">
                        <h2 class="h4 pb-3">No products found</h2>
                        <a class="btn btn-primary mt-3" href="{{ url('/') }}">Browse Products</a>
                    </div>
                </div>
            </div>
        </div>
    @elseif(Cart::count() == 0)
        <div class="container pb-5 mb-sm-4">
            <div class="pt-5">
                <div class="card py-3 mt-sm-3" style="min-height: 309px;">
                    <div class="card-body text-center">
                        <h2 class="h4 pb-3">No products found</h2>
                        <a class="btn btn-primary mt-3" href="{{ url('/') }}">Browse Products</a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <br>
        <section class="section-content padding-y bg slidetop">
            <div class="container p-0">
                <div class="row">
                    <div class="col-md-6">
                        <aside class="card mb-4">
                            <article class="card-body">
                                <header class="mb-4">
                                    <h4 class="card-title" style="font-size: 18px; font-weight: 600; color: #333;">Fill out the form below to place your order</h4>
                                </header>
                                <form action="{{ url('checkout/store') }}" method="POST"
                                    class="from-prevent-multiple-submits">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label>Your Name</label>
                                            <input type="text" id="customerName" name="customerName" @if(Auth::id()) value="{{Auth::guard('web')->user()->name}}" @else @endif    placeholder="Enter your name"
                                                required class="form-control">
                                        </div>
                                        @if(Auth::id())
                                            <input type="text" id="user_id" name="user_id" @if(Auth::id()) value="{{Auth::guard('web')->user()->id}}" @else @endif hidden>
                                        @else

                                        @endif
                                        <div class="form-group col-sm-12">
                                            <label>Your Address</label>
                                            <input type="text" id="customerAddress" name="customerAddress"
                                                placeholder="Enter your address" required class="form-control">
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label>Your Mobile</label>
                                            <input type="text" minlength="11" maxlength="11" pattern="[0-1]{2}[0-9]{6}[0-9]{3}" id="customerPhone" @if(Auth::id()) value="{{Auth::guard('web')->user()->phone}}" @else @endif  name="customerPhone" required
                                                class="form-control" placeholder="Enter your mobile number">
                                        </div>
                                        <textarea id="ordersubtotalprice" name="subTotal" cols="10" rows="5" hidden>{{ Cart::subtotalFloat() }}</textarea>
                                        <div class="form-group col-sm-12">
                                            <label>Select Area </label>
                                            <select id="deliveryCharge" name="deliveryCharge" class="form-control"
                                                onchange="setdeliverychargr()">
                                                @if (isset($product->inside_dhaka) && isset($product->outside_dhaka))
                                                    <option value="{{ $product->inside_dhaka }}">Inside Dhaka
                                                        ({{ $product->inside_dhaka }}) </option>
                                                    <option value="{{ $product->outside_dhaka }}">Outside Dhaka
                                                        ({{ $product->outside_dhaka }})</option>
                                                @else
                                                    <option value="{{App\Models\Basicinfo::first()->inside_dhaka_charge}}">Inside Dhaka ({{App\Models\Basicinfo::first()->inside_dhaka_charge}}) </option>
                                                    <option value="{{App\Models\Basicinfo::first()->outside_dhaka_charge}}">Outside Dhaka ({{App\Models\Basicinfo::first()->outside_dhaka_charge}})</option>
                                                @endif

                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <button type="submit" id="orderConfirm"
                                                class="from-prevent-multiple-submits btn-block">
                                                <i class="spinner fa fa-spinner fa-spin"></i> Proceed to Payment
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </article> <!-- card-body.// -->
                        </aside>
                    </div>
                    <div class="col-md-6 orderDetails">
                        <aside class="card">


                            <article class="card-body">
                                <header class="mb-4">
                                    <h4 class="card-title" style="font-size: 16px;">Your Order</h4>
                                </header>
                                <div class="row">
                                    <table class="table border-bottom">
                                        @forelse ($cartProducts as $cartProduct)
                                            <tr class="cart-item" id="productcart{{ $cartProduct->rowId }}">
                                                <td class="product-image" id="proImgDiv">
                                                    <a href="#" class="mr-3">
                                                        <img class=" ls-is-cached lazyloaded"
                                                            src="{{ asset($cartProduct->image) }}" id="proImg">
                                                    </a>
                                                </td>

                                                <td class="product-total" style="width: 80px;" hidden>
                                                    <span>৳ <span id="pricetotal{{ $cartProduct->rowId }}"
                                                            class="price">{{ $cartProduct->qty * $cartProduct->price }}</span></span>
                                                </td>

                                                <td class="product-name">
                                                    <span class="pr-4 d-block w-100"
                                                        id="proName">{{ $cartProduct->name }}</span>
                                                    <div class="ext w-100">
                                                        <div class="price">
                                                            <span class="pr-3 d-block" id="proPrice">৳
                                                                {{ $cartProduct->price }}</span>
                                                        </div>
                                                        <div class="qtyinfo">
                                                            <div class="input-group input-group--style-2 pr-4"
                                                                style="width: 130px;float:left">
                                                                <span class="input-group-btn">
                                                                    <button class="btn btn-number" onclick="remnum('{{$cartProduct->rowId}}')" id="remqty" type="button" >
                                                                        <i class="fas fa-minus"></i>
                                                                    </button>
                                                                </span>
                                                                <input type="text"
                                                                    name="quantity[{{ $cartProduct->id }}]"
                                                                    id="QuantityPeo{{ $cartProduct->rowId }}"
                                                                    class="form-control input-number" placeholder="1"
                                                                    value="{{ $cartProduct->qty }}" min="1" max="10"
                                                                    onchange="updateQuantity('{{ $cartProduct->rowId }}', this)">
                                                                <span class="input-group-btn">
                                                                    <button class="btn btn-number" onclick="updatenum('{{$cartProduct->rowId}}')" id="äddqty" type="button" >
                                                                        <i class="fas fa-plus"></i>
                                                                    </button>
                                                                </span>
                                                            </div>
                                                            <a type="button" id="proDelCart"
                                                                style="width: 30px;font-size: 18px;"
                                                                onclick="removeFromCart('{{ $cartProduct->rowId }}')"
                                                                class="text-right pl-4">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <input type="text" name="productP" id="priceOf{{ $cartProduct->rowId }}"
                                                    value="{{ $cartProduct->price }}" hidden>

                                            </tr>
                                        @empty
                                        @endforelse
                                    </table>
                                </div>
                            </article>

                            <input type="text" name="size" value="{{ $cartProduct->options->size }}" hidden>
                            <input type="text" name="color" value="{{ $cartProduct->options->color }}" hidden>

                            <article class="card-body border-top">
                                <dl class="row">
                                    <dt class="col-sm-8">Subtotal: </dt>
                                    <dd class="col-sm-4 text-right"><strong>৳ <span
                                                id="subtotalprice">{{ Cart::subtotalFloat() }}</span> </strong></dd>

                                    <dt class="col-sm-8">Delivery charge: </dt>
                                    <dd class="col-sm-4 text-danger text-right"><strong>৳
                                            @if (isset($product->inside_dhaka))
                                                <span id="dinamicdalivery">{{ $product->inside_dhaka }}</span>
                                            @else
                                                <span id="dinamicdalivery">{{App\Models\Basicinfo::first()->inside_dhaka_charge}}</span>
                                            @endif
                                        </strong></dd>

                                    <dt class="col-sm-8">Total:</dt>
                                    <dd class="col-sm-4 text-right"><strong class="h5 text-dark">৳ <span
                                                id="totalamount"></span></strong></dd>
                                </dl>

                            </article>

                        </aside>
                    </div>

                </div>
            </div>
        </section>
        <br>
    @endif


    <style>
        .spinner {
            display: none;
        }

        #orderConfirm {
            background: var(--theme-color);
            color: #fff;
            font-size: 20px;
            font-weight: 600;
            padding: 14px 28px;
            border: none;
            border-radius: 8px;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 176, 155, 0.3);
            width: 100%;
            text-transform: uppercase;
        }

        #orderConfirm:hover {
            background: var(--theme-color);
            filter: brightness(0.9);
            box-shadow: 0 6px 20px rgba(0, 176, 155, 0.45);
            transform: translateY(-2px);
        }

        #orderConfirm:active {
            transform: translateY(0);
            box-shadow: 0 2px 10px rgba(0, 176, 155, 0.3);
        }

        #orderConfirm:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .card.mb-4,
        .orderDetails .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
        }

        .form-group label {
            font-weight: 500;
            margin-bottom: 0.3rem;
        }

        .form-control {
            border: 1px solid #ced4da;
            border-radius: 6px;
            padding: 10px 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            border-color: var(--theme-color);
            box-shadow: 0 0 0 3px rgba(0, 176, 155, 0.2);
        }

        table.table.border-bottom tr {
            border-bottom: 1px solid #eee;
            transition: background 0.15s;
        }

        table.table.border-bottom tr:hover {
            background: #f8f9fa;
        }

        @media only screen and (min-width: 768px) {
            #proName {
                font-size: 18px;
            }

            #proPrice {
                font-size: 18px;
                padding: 6px;
                padding-left: 0;
            }

            .input-number {
                height: 39px;
            }

            #proDelCart {
                width: 30px;
                padding-top: 2px;
                font-size: 20px;
            }

            #proImgDiv {
                max-width: 110px;
            }

            #proImg {
                max-width: 100px;
            }
        }

        @media only screen and (max-width: 767px) {
            .input-group--style-2 .input-group-btn>.btn {
                background: 0 0;
                border-color: #e6e6e6;
                color: #818a91;
                font-size: 8px;
                padding-top: 6px;
                padding-bottom: 6px;
                cursor: pointer;
            }

            .input-number {
                height: 26px;
            }

            #proDelCart {
                width: 30px;
                font-size: 18px;
            }

            #proImg {
                max-width: 50px;
            }
        }
    </style>
    <script>
        function updatenum(id){
            var num=$('#QuantityPeo'+id).val();
            var fv=Number(num)+1;
            if(fv>9){

            }else{
                $('#QuantityPeo'+id).val(fv);
                $.ajax({
                    type: 'POST',
                    url: 'update-cart',

                    data: {
                        _token: '{{ csrf_token() }}',
                        rowId: id,
                        qty: fv,
                    },

                    success: function(data) {
                        $('#QuantityPeo' + id).val(data.qty);
                        updateQuantity(id);

                    },
                    error: function(error) {
                        console.log('error');
                    }
                });
            }
        }
        function remnum(id){
            var num=$('#QuantityPeo'+id).val();
            var fv=Number(num)-1;
            if(fv<1){

            }else{
                $('#QuantityPeo'+id).val(fv);
                $.ajax({
                    type: 'POST',
                    url: 'update-cart',

                    data: {
                        _token: '{{ csrf_token() }}',
                        rowId: id,
                        qty: fv,
                    },

                    success: function(data) {
                        $('#QuantityPeo' + id).val(data.qty);
                        updateQuantity(id);

                    },
                    error: function(error) {
                        console.log('error');
                    }
                });

            }

        }
        function setdeliverychargr() {
            var deliverycharge = $('#deliveryCharge').val();
            $('#dinamicdalivery').html(deliverycharge);

            var subprice = $('#subtotalprice').html();
            var totalprice = subprice - (-deliverycharge);
            $('#totalamount').html(totalprice)
        }

        function updateQuantity(rowId) {
            var quantity = $('#QuantityPeo' + rowId).val();
            var price = $('#priceOf' + rowId).val();
            var producttotal = quantity * price;

            var prevPrice = $('#pricetotal' + rowId).html();
            if (producttotal > prevPrice) {
                var subPrice = $('#subtotalprice').html();
                var updatesubprice = subPrice - (-price);
                $('#subtotalprice').html(updatesubprice);
                //ordersubtotal
                $('#ordersubtotalprice').html(updatesubprice);
                //cart number
                var prevcart = $('#cartNumber').html();
                var cartUpdate = prevcart - (-1);
                $('#cartNumber').html(cartUpdate);

            } else {
                //cart number
                var prevcart = $('#cartNumber').html();
                var cartUpdate = prevcart - 1;
                $('#cartNumber').html(cartUpdate);

                var subPrice = $('#subtotalprice').html();
                var updatesubprice = subPrice - price;
                $('#subtotalprice').html(updatesubprice);
                $('#ordersubtotalprice').html(updatesubprice);

            }
            //mincart
            $('#minQty' + rowId).html(quantity);
            $('#minsubtotalprice').html(updatesubprice);
            //total price part
            var deliverycharge = $('#deliveryCharge').val();
            var totalprice = updatesubprice - (-deliverycharge);
            $('#totalamount').html(totalprice);


            $('#pricetotal' + rowId).html(producttotal);

            $.ajax({
                type: 'POST',
                url: 'update-cart',

                data: {
                    _token: '{{ csrf_token() }}',
                    rowId: rowId,
                    qty: quantity,
                },

                success: function(data) {
                    $('#QuantityPeo' + rowId).val(data.qty);

                },
                error: function(error) {
                    console.log('error');
                }
            });

        }

        function removeFromCart(rowId) {
            var thisprice = $('#pricetotal' + rowId).html();
            var subPrice = $('#subtotalprice').html();
            var updatesubprice = subPrice - thisprice;
            $('#subtotalprice').html(updatesubprice);

            //order subtotal
            $('#ordersubtotalprice').html(updatesubprice);

            var deliverycharge = $('#deliveryCharge').val();
            var totalprice = updatesubprice - (-deliverycharge);
            $('#totalamount').html(totalprice);
            //cart number
            var quantity = $('#QuantityPeo' + rowId).val();
            var prevcart = $('#cartNumber').html();
            var cartUpdate = prevcart - quantity;
            $('#cartNumber').html(cartUpdate);

            $.ajax({
                type: 'POST',
                url: 'remove-cart',
                data: {
                    _token: '{{ csrf_token() }}',
                    rowId: rowId,
                },

                success: function(data) {
                    $('#productcart' + rowId).css('display', 'none');
                    if (data == 'empty') {
                        location.reload();
                    }
                },
                error: function(error) {
                    console.log('error');
                }
            });
        }

        window.onload = (event) => {
            var subPrice = $('#subtotalprice').html();
            //total price part
            var deliverycharge = $('#deliveryCharge').val();
            var totalprice = subPrice - (-deliverycharge);
            $('#totalamount').html(totalprice)

        };
    </script>

    <script type="text/javascript">
        (function() {
            $('.from-prevent-multiple-submits').on('submit', function() {
                $('.from-prevent-multiple-submits').attr('disabled', 'true');
                $('.spinner').css('display', 'inline');
            })
        })();
    </script>
@endsection
