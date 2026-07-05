@extends('webview.master')

@section('maincontent')
@section('title')
    {{ $productdetails->ProductName }}
@endsection

@section('meta')
    <meta name="description" content="{{ App\Models\Basicinfo::first()->meta_description }}">
    <meta name="keywords" content="{{ App\Models\Basicinfo::first()->meta_keyword }}">

    <meta itemprop="name" content="{{ $productdetails->ProductName }}">
    <meta itemprop="description" content="{{$productdetails->ProductBreaf}}">
    <meta itemprop="image" content="{{ $productdetails->ProductImage }}">

    <meta property="og:url" content="{{ url('product/' . $productdetails->ProductSlug) }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $productdetails->ProductName }}">
    <meta property="og:description" content="{{$productdetails->ProductBreaf}}">
    <meta property="og:image" content="{{ url('/') }}/{{ $productdetails->ProductImage }}">
    <meta property="image" content="{{ url('/') }}/{{ $productdetails->ProductImage }}" />
    <meta property="url" content="{{ url('product/' . $productdetails->ProductSlug) }}">
    <meta itemprop="image" content="{{ url('/') }}/{{ $productdetails->ProductImage }}">
    <meta property="twitter:card" content="{{ url('/') }}/{{ $productdetails->ProductImage }}" />
    <meta property="twitter:title" content="{{ $productdetails->ProductName }}" />
    <meta property="twitter:url" content="{{ url('product/' . $productdetails->ProductSlug) }}">
    <meta name="twitter:image" content="{{ url('/') }}/{{ $productdetails->ProductImage }}">
@endsection
<style>
    .product-info .name {
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
        letter-spacing: -0.5px;
    }
    .product-price {
        font-size: 24px;
        font-weight: 800;
        color: var(--theme-color);
    }
    .product-price del {
        font-size: 18px;
        color: #999;
        margin-right: 10px;
        font-weight: 400;
    }
    .selection-label {
        font-size: 14px;
        font-weight: 600;
        color: #666;
        margin-bottom: 8px;
        display: block;
    }
    .sizetext, .colortext {
        cursor: pointer;
        border: 1px solid #ddd;
        padding: 6px 16px;
        border-radius: 50px;
        margin-right: 8px;
        margin-bottom: 8px;
        display: inline-block;
        transition: all 0.3s ease;
        font-size: 14px;
        background: #fff;
        color: #333;
    }
    .sizetext:hover, .colortext:hover {
        border-color: var(--theme-color);
        color: var(--theme-color);
    }
    .sizetext.selected, .colortext.selected {
        background-color: var(--theme-color);
        border-color: var(--theme-color);
        color: #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .btn-action {
        border-radius: 8px;
        padding: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        border: none;
    }
    .btn-add-cart {
        background-color: #fff;
        border: 2px solid var(--theme-color);
        color: var(--theme-color);
    }
    .btn-add-cart:hover {
        background-color: var(--theme-color);
        color: #fff;
    }
    .btn-order-now {
        background-color: var(--theme-color);
        color: #fff;
    }
    .btn-order-now:hover {
        background-color: var(--secondary-color);
        color: #fff;
    }
    .delivery-info-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-top: 20px;
    }
    .delivery-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 10px;
        font-size: 13px;
        color: #555;
    }
    .delivery-item i {
        color: var(--theme-color);
        margin-top: 3px;
        margin-right: 10px;
    }
    #sync2 .items img {
        cursor: pointer;
        border: 2px solid transparent;
        transition: 0.3s;
    }
    #sync2 .current .items img {
        border-color: var(--theme-color);
    }

    .product-tabs .nav-link {
        color: #000;
        background: #fff;
        border-color: #ddd;
    }
    .product-tabs .nav-link:hover {
        border-color: var(--theme-color);
    }
    .product-tabs .nav-link.active {
        background-color: var(--theme-color) !important;
        color: #fff !important;
        border-color: var(--theme-color) !important;
    }
</style>
<!-- Body -->

<div class="body-content mt-4" id="top-banner-and-menu">
    <div class='container'>
        <div class='row single-product'>
            <div class='col-md-12 p-0'>
                <div class="detail-block bg-white shadow-sm p-3 p-md-4">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 gallery-holder">
                            <div class="product-item-holder size-big single-product-gallery small-gallery">
                                @if (isset($productdetails->PostImage))
                                    <div id="sync1" class="owl-carousel owl-theme">
                                        <div class="items">
                                            <img class="w-100 h-100" src="{{ asset($productdetails->ProductImage) }}" alt="" style="border-radius: 8px;">
                                        </div>
                                        @forelse (json_decode($productdetails->PostImage) as $productImage)
                                            <div class="items">
                                                <img class="w-100 h-100"
                                                    src="{{ asset('public/images/product/slider/') }}/{{ $productImage }}" alt="" style="border-radius: 8px;">
                                            </div>
                                        @empty
                                        @endforelse
                                    </div>
                                    <div id="sync2" class="owl-carousel owl-theme" style="padding-top: 15px;">
                                        @forelse (json_decode($productdetails->PostImage) as $productImage)
                                            <div class="items">
                                                <img class="w-100 h-100" style="border-radius: 6px;"
                                                    src="{{ asset('public/images/product/slider/') }}/{{ $productImage }}" alt="">
                                            </div>
                                        @empty
                                        @endforelse
                                    </div>
                                @else
                                    <div class="items">
                                        <img class="w-100 h-100" src="{{ asset($productdetails->ProductImage) }}" alt="" style="border-radius: 8px;">
                                    </div>
                                @endif

                            </div>
                            <!-- /.single-product-gallery -->
                        </div>
                        <!-- /.gallery-holder -->
                        <div class="col-sm-12 col-md-6 product-info-block" id="paddingnone">
                            <div class="product-info ps-md-4">
                                <h1 class="name" style="font-size: 24px; line-height: 1.3;">
                                    {{ $productdetails->ProductName }}</h1>

                                <div class="price-container my-3">
                                    <div class="product-price">
                                        @if($productdetails->ProductRegularPrice > $productdetails->ProductSalePrice)
                                            <del>৳{{intval($productdetails->ProductRegularPrice)}}</del>
                                        @endif
                                        ৳ {{ $productdetails->ProductSalePrice }}
                                    </div>
                                </div>

                                <div class="options-container my-4">
                                    @if (!empty($productdetails->color))
                                        <div class="mb-3">
                                            <span class="selection-label">Select Color</span>
                                            <div class="colorinfo d-flex flex-wrap">
                                                @forelse (json_decode($productdetails->color) as $color)
                                                    <input type="radio" hidden id="color{{ $color }}" name="color_radio">
                                                    <label class="colortext" id="colortext{{ $color }}" for="color{{ $color }}" onclick="getcolor('{{ $color }}')">{{ $color }}</label>
                                                @empty
                                                @endforelse
                                            </div>
                                        </div>
                                    @endif

                                    @if (!empty($productdetails->size))
                                        <div class="mb-3">
                                            <span class="selection-label">Select Size</span>
                                            <div class="sizeinfo d-flex flex-wrap">
                                                @forelse (json_decode($productdetails->size) as $size)
                                                    <input type="radio" hidden id="size{{ $size }}" name="size_radio">
                                                    <label class="sizetext" id="sizetext{{ $size }}" for="size{{ $size }}" onclick="getsize('{{ $size }}')">{{ $size }}</label>
                                                @empty
                                                @endforelse
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="quantity-container mb-4 d-flex align-items-center">
                                    <span class="selection-label mb-0 me-3">Quantity:</span>
                                    <div class="quant-input d-flex align-items-center border rounded" style="width: 120px;">
                                        <button type="button" class="btn btn-sm px-3" onclick="downQuantity()">-</button>
                                        <input type="number" id="proQuantity" value="1" class="form-control border-0 text-center bg-transparent p-0" readonly style="box-shadow: none;">
                                        <button type="button" class="btn btn-sm px-3" onclick="upQuantity()">+</button>
                                    </div>
                                </div>

                                <div class="action-buttons row g-2">
                                    <div class="col-6">
                                        <form name="form" id="AddToCartForm" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="color" id="product_color">
                                            <input type="hidden" name="size" id="product_size">
                                            <input type="hidden" name="product_id" value="{{ $productdetails->id }}">
                                            <input type="hidden" name="qty" value="1" id="qty">
                                            <button type="submit" class="btn btn-action btn-add-cart w-100">
                                                <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-6">
                                        <form name="form" action="{{url('add-to-cart')}}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="color" id="product_colorOr">
                                            <input type="hidden" name="size" id="product_sizeOr">
                                            <input type="hidden" name="product_id" value="{{ $productdetails->id }}">
                                            <input type="hidden" name="qty" value="1" id="qtyor">
                                            <button type="submit" class="btn btn-action btn-order-now w-100">
                                                Order Now
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="delivery-info-card shadow-sm border-0">
                                    <div class="delivery-item">
                                        <i class="fas fa-truck"></i>
                                        <span>Delivery inside Dhaka: <b>৳{{ $shipping->inside_dhaka_charge }}</b></span>
                                    </div>
                                    <div class="delivery-item">
                                        <i class="fas fa-shipping-fast"></i>
                                        <span>Delivery outside Dhaka: <b>৳{{ $shipping->outside_dhaka_charge }}</b></span>
                                    </div>
                                    <div class="delivery-item mb-0">
                                        <i class="fas fa-shield-alt"></i>
                                        <span>100% Authentic Product</span>
                                    </div>
                                </div>
                            </div>
                            <!-- /.product-info -->
                        </div>
                        <!-- /.col-sm-7 -->
                    </div>
                    <!-- /.row -->
                </div>
            </div>
            <!-- /.col -->
            <div class="clearfix"></div>
        </div>
        <div class="row single-product mt-1">
            <div class="col-md-12 p-0">
                <div class="product-tabs inner-bottom-xs">
                    <div class="row">
                        <div class="col-sm-12 mt-4">
                            <ul id="product-tabs" class="nav nav-tabs border-bottom-0 mb-3" style="gap: 10px;">
                                <li class="nav-item">
                                    <a class="nav-link active rounded-pill px-4 py-2 border shadow-sm" data-bs-toggle="tab" id="istteb" href="#description" style="font-weight: 600; font-size: 14px;">DESCRIPTION</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link rounded-pill px-4 py-2 border shadow-sm" data-bs-toggle="tab" href="#review" style="font-weight: 600; font-size: 14px;">REVIEW</a>
                                </li>
                                <li class="nav-item d-lg-none">
                                    <a class="nav-link rounded-pill px-4 py-2 border shadow-sm" data-bs-toggle="tab" href="#shipping-info" style="font-weight: 600; font-size: 14px;">SHIPPING INFO</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-sm-12">
                            <div class="tab-content bg-white p-4 rounded shadow-sm">
                                <div id="description" class="tab-pane active">
                                    <div class="product-tab">
                                        <div class="text-muted" style="line-height: 1.6;">{!! $productdetails->ProductDetails !!}</div>
                                        @if (isset($productdetails->youtube_embade))
                                            <div class="mt-4 rounded overflow-hidden">
                                                <iframe width="100%" height="450"
                                                    src="https://www.youtube.com/embed/{{ $productdetails->youtube_embade }}"
                                                    frameborder="0" allowfullscreen>
                                                </iframe>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div id="review" class="tab-pane">
                                    <div class="product-tab text-center py-4">
                                        <p class="text-muted">No reviews yet.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <section class="mt-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="m-0" style="font-weight: 700; font-size: 20px;">Related Products</h3>
                        <div class="border-top flex-grow-1 mx-3 d-none d-md-block"></div>
                    </div>
                    <div class="owl-carousel related-owl-carousel featured-carousel owl-theme" id="relatedCarousel">
                        @forelse ($relatedproducts as $relatedproduct)
                            <div class="item">
                                @include('webview.partials.product-card', ['product' => $relatedproduct])
                            </div>
                        @empty
                        @endforelse
                    </div>
                </section>

            </div>
        </div>
        <!-- ============================================== BRANDS CAROUSEL : END ============================================== -->
    </div>
    <!-- /.container -->
</div>
<!-- /.body-content -->

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-sm-12 p-0">
            <section>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="m-0" style="font-weight: 700; font-size: 20px;">Promotional Offers</h3>
                    <a href="{{ url('promotional/products') }}" class="btn btn-sm rounded-pill px-3 py-1 border shadow-sm" style="font-size: 12px; font-weight: 600; color: var(--theme-color);">VIEW ALL</a>
                </div>
                <div class="owl-carousel" id="promotionalofferSlide">
                    @forelse ($topproducts as $promotional)
                        <div class="item">
                            @include('webview.partials.product-card', ['product' => $promotional])
                        </div>
                    @empty
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</div>
<!-- Body end -->


{{-- modal for process and cart --}}




{{-- csrf --}}
<input type="hidden" name="_token" value="{{ csrf_token() }}" />

<script>
    $(document).ready(function() {

        var sync1 = $("#sync1");
        var sync2 = $("#sync2");
        var slidesPerPage = 4; //globaly define number of elements per page
        var syncedSecondary = true;

        sync1.owlCarousel({
            items: 1,
            slideSpeed: 2000,
            autoplay: true,
            dots: false,
            loop: true,
            responsiveRefreshRate: 200,
            navText: [
                '<svg width="100%" height="100%" viewBox="0 0 11 20"><path style="fill:none;stroke-width: 1px;stroke: #000;" d="M9.554,1.001l-8.607,8.607l8.607,8.606"/></svg>',
                '<svg width="100%" height="100%" viewBox="0 0 11 20" version="1.1"><path style="fill:none;stroke-width: 1px;stroke: #000;" d="M1.054,18.214l8.606,-8.606l-8.606,-8.607"/></svg>'
            ],
        }).on('changed.owl.carousel', syncPosition);

        sync2
            .on('initialized.owl.carousel', function() {
                sync2.find(".owl-item").eq(0).addClass("current");
            })
            .owlCarousel({
                margin:6,
                items: slidesPerPage,
                dots: false,
                nav: true,
                smartSpeed: 200,
                slideSpeed: 500,
                slideBy: slidesPerPage, //alternatively you can slide by 1, this way the active slide will stick to the first item in the second carousel
                responsiveRefreshRate: 100
            }).on('changed.owl.carousel', syncPosition2);

        function syncPosition(el) {
            //if you set loop to false, you have to restore this next line
            //var current = el.item.index;

            //if you disable loop you have to comment this block
            var count = el.item.count - 1;
            var current = Math.round(el.item.index - (el.item.count / 2) - .5);

            if (current < 0) {
                current = count;
            }
            if (current > count) {
                current = 0;
            }

            //end block

            sync2
                .find(".owl-item")
                .removeClass("current")
                .eq(current)
                .addClass("current");
            var onscreen = sync2.find('.owl-item.active').length - 1;
            var start = sync2.find('.owl-item.active').first().index();
            var end = sync2.find('.owl-item.active').last().index();

            if (current > end) {
                sync2.data('owl.carousel').to(current, 100, true);
            }
            if (current < start) {
                sync2.data('owl.carousel').to(current - onscreen, 100, true);
            }
        }

        function syncPosition2(el) {
            if (syncedSecondary) {
                var number = el.item.index;
                sync1.data('owl.carousel').to(number, 100, true);
            }
        }

        sync2.on("click", ".owl-item", function(e) {
            e.preventDefault();
            var number = $(this).index();
            sync1.data('owl.carousel').to(number, 300, true);
        });


        $('#AddToCartForm').submit(function(e) {
            e.preventDefault();
            $('#processing').css({
                'display': 'flex',
                'justify-content': 'center',
                'align-items': 'center'
            })
            $('#processing').modal('show');
            $.ajax({
                type: 'POST',
                url: '{{ url('add-to-cart') }}',
                processData: false,
                contentType: false,
                data: new FormData(this),

                success: function(data) {
                    updatecart();
                    $.ajax({
                        type: 'GET',
                        url: '{{ url('get-cart-content') }}',

                        success: function(response) {
                            $('#cartViewModal .modal-body').empty().append(
                                response);
                        },
                        error: function(error) {
                            console.log('error');
                        }
                    });
                    $('#processing').modal('hide');
                    $('#cartViewModal').modal('show');
                },
                error: function(error) {
                    console.log('error');
                }
            });
        });

        $('#OrderNow').submit(function(e) {
            e.preventDefault();
            $('#processing').css({
                'display': 'flex',
                'justify-content': 'center',
                'align-items': 'center'
            })
            $('#processing').modal('show');
            $.ajax({
                type: 'POST',
                url: '{{ url('add-to-cart') }}',
                processData: false,
                contentType: false,
                data: new FormData(this),

                success: function(data) {
                    updatecart();
                    window.location.href = '{{ url('checkout') }}';
                },
                complete: function() {
                    $('#processing').modal('hide');
                }
            });
        });


        // document.getElementById("istteb").click();
        $('#owl-single-product').owlCarousel({
            items: 1,
            itemsTablet: [768, 1],
            itemsDesktop: [1199, 1],
            autoplay: true,
            loop:true,
            autoplayTimeout: 1000,
            autoplayHoverPause: true,
            responsiveClass: true,
            dots: true,

        });
        $('#relatedCarousel').owlCarousel({
            loop: true,
            margin: 10,
            autoplay: true,
            autoplayTimeout: 1000,
            autoplayHoverPause: true,
            responsiveClass: true,
            nav: true,
            dots: false,
            responsive: {
                0: {
                    items: 2,
                },
                600: {
                    items: 2,
                },
                1000: {
                    items: 6,
                }
            }
        });
        $('#featuredCarousel').owlCarousel({
            loop: true,
            margin: 10,
            autoplay: true,
            autoplayTimeout: 1000,
            autoplayHoverPause: true,
            responsiveClass: true,
            nav: true,
            dots: false,
            responsive: {
                0: {
                    items: 2,
                },
                600: {
                    items: 2,
                },
                1000: {
                    items: 6,
                }
            }
        });

        $('#BestSelling').owlCarousel({
            loop: true,
            margin: 10,
            autoplay: true,
            autoplayTimeout: 1000,
            autoplayHoverPause: true,
            responsiveClass: true,
            dots: false,
            nav: true,
            responsive: {
                0: {
                    items: 2,
                },
                600: {
                    items: 2,
                },
                1000: {
                    items: 6,
                }
            }
        });





    });

    function getcolor(color) {
        $('#product_color').val(color);
        $('#product_colorOr').val(color);

        $('.colortext').removeClass('selected');
        $('#colortext' + color).addClass('selected');
    }

    function getsize(size) {
        $('#product_size').val(size);
        $('#product_sizeOr').val(size);

        $('.sizetext').removeClass('selected');
        $('#sizetext' + size).addClass('selected');
    }

</script>

@endsection
