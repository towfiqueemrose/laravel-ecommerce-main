@extends('webview.master')

@section('maincontent')
@section('title')
    {{ App\Models\Basicinfo::first()->meta_tittle }} | {{ App\Models\Basicinfo::first()->site_sologan }}
@endsection

@section('meta')
@php
	$basicinfo=DB::table('basicinfos')->first();
@endphp
    <meta name="description" content="{{ App\Models\Basicinfo::first()->meta_description }}">
    <meta name="keywords" content="{{ App\Models\Basicinfo::first()->meta_keyword }}">
    <meta itemprop="name" content="{{ App\Models\Basicinfo::first()->site_sologan }}">
    <meta itemprop="description" content="{{ App\Models\Basicinfo::first()->meta_description }}">
    <meta itemprop="image" content="{{ asset($basicinfo->og_images ??'') }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ App\Models\Basicinfo::first()->meta_tittle }}">
    <meta property="og:description" content="{{ App\Models\Basicinfo::first()->meta_description }}">
    <meta property="og:image" content="{{ asset($basicinfo->og_images ??'') }}">
    <meta property="image" content="{{ asset($basicinfo->og_images ??'') }}" />
	<meta property="image" content="{{ asset($basicinfo->og_images ??'') }}" />
    <meta property="url" content="{{ url('/') }}">
    <meta itemprop="image" content="{{ asset($basicinfo->og_images ??'') }}">
    <meta property="twitter:card" content="{{ asset($basicinfo->og_images ??'') }}" />
    <meta property="twitter:title" content="{{ App\Models\Basicinfo::first()->meta_tittle }}" />
    <meta property="twitter:url" content="{{ url('/') }}">
    <meta name="twitter:image" content="{{ asset($basicinfo->og_images ??'') }}">
@endsection
<style>
    #featureimagess{
        width: 100%;
        padding: 2px;
        padding-top: 0;
        max-height:200px;
    }
</style>
<div class="container-fluid" style="padding:0;background:#24a86c">
    <div class="container">
    <div class="row" style="background:#24a86c">
        <div class="col-lg-3 d-none d-lg-block sidebar pe-0 ps-0">
            <div class="side-menu animate-dropdown">
                <div class="head"><i class="icon fas fa-align-justify fa-fw"></i> Categories</div>
            </div>
        </div>
        <div class="col-lg-9 col-12 ps-0 pe-0" id="mainslider">
            <div class="col-lg-12 position-static order-2 order-lg-0 d-none d-lg-block" style="background: #24a86c;">
                <div id="menu">
                    <ul>
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ url('/combo-offer') }}">Combo Offer</a></li>
                        <li><a href="{{ url('/') }}">News Feed</a></li>
                        <li><a href="{{ url('/track-order') }}">Order Track</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<div class="container">
    <div class="row bg-white">
        <div class="col-lg-3 d-none d-lg-block sidebar pe-0 ps-0">
            <div class="side-menu animate-dropdown outer-bottom-xs">
                <nav class="yamm megamenu-horizontal" role="navigation" style="padding-top: 6px;">
                    <ul class="nav m-0">
                        @forelse ($categories as $maincategory)
                            @if (count($maincategory->subcategories) > 0)
                                <li class="dropdown menu-item">
                                    <a href="{{ url('products/category/' . $maincategory->slug) }}"
                                        class="dropdown-toggle" data-bs-hover="dropdown"> <img
                                            src="{{ asset($maincategory->category_icon) }}"
                                            alt="{{ $maincategory->category_name }}"
                                            style="width: 22px !important;margin-top: -5px;">
                                        <span style="margin-left:6px">{{ $maincategory->category_name }}</span></a>
                                    <ul class="dropdown-menu mega-menu">
                                        <li class="yamm-content" style="padding-bottom: 5px;padding-top: 5px;">
                                            <ul class="links list-unstyled">
                                                <div class="row">
                                                    @foreach ($maincategory->subcategories as $subcategory)
                                                        <div class="col-sm-12 col-md-4 pt-1 pb-1" id="subcategoryhover" style="width: 100%;">
                                                            <li><a href="{{ url('products/sub/category/' . $subcategory->slug) }}"
                                                                    style="color:#666666">{{ $subcategory->sub_category_name }}</a>
                                                            </li>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </ul>
                                            <!-- /.row -->
                                        </li>
                                        <!-- /.yamm-content -->
                                    </ul>
                                    <!-- /.dropdown-menu -->
                                </li>
                            @else
                                <li class="dropdown menu-item">
                                    <a href="{{ url('products/category/' . $maincategory->slug) }}"
                                        class="dropdown-toggle text-truncate" data-bs-hover="dropdown"><img
                                            src="{{ asset($maincategory->category_icon) }}"
                                            alt="{{ $maincategory->category_name }}"
                                            style="width: 22px !important;margin-top: -5px;"><span style="margin-left:6px">{{ $maincategory->category_name }}</span></a>
                                    <!-- /.dropdown-menu -->
                                </li>
                            @endif
                        @empty
                        @endforelse
                    </ul>
                </nav>
            </div>
        </div>
        <div class="col-lg-9 col-12 ps-0 pe-0" id="mainslider">

            <div class="col-12">
                <div class="owl-carousel owl-theme" id="slider">
                    @forelse ($sliders as $slider)
                        <div class="item" style="margin:0 !important;">
                            <img  src="{{ asset($slider->slider_image) }}"
                                alt="{{ $slider->slider_title }}">
                        </div>
                    @empty
                    @endforelse
                </div>

            </div>
        </div>
    </div>
</div>

<div class="container mt-lg-4 mt-2 p-0 mb-4">
    <div class="row">
        <div class="col-12">
            <div class="owl-carousel " id="categorySlide">
                @forelse ($categories as $category)
                    <div class="item">
                        <a href="{{ url('products/category/' . $category->slug) }}" >
                            <div id="cath">
                                <div class="d-flex justify-content-center" >
                                    <img  src="{{ asset($category->category_icon) }}" id="catimg">
                                </div>

                                <p id="catp">{{ $category->category_name }}</p>
                            </div>
                        </a>
                    </div>
                @empty

                @endforelse
            </div>
        </div>
    </div>
</div>
 

@if(count($featuredproducts)>0)
<!-- Promotional Products -->
<div class="container pt-0 pb-4">
    <div class="row bg-white pb-4">
        <div class="col-12" style="border-bottom: 1px solid #24a86c;padding-left: 0;display: flex;justify-content: space-between;">
            <div class="px-2 p-md-3 pt-0 d-flex justify-content-between" style="padding-bottom:4px !important;padding-top: 8px !important;">
                <h4 class="m-0"><b>Featured Products</b></h4>
            </div>
            <a href="{{ url('featured/products') }}" class="btn btn-danger btn-sm mb-0" style="padding: 2px;height: 26px;color: white;font-weight: bold;margin-top:9px;background:#24a86c;border:1px solid #24a86c">VIEW ALL</a>
        </div>
        <div class="col-12">
            <div class="owl-carousel" id="featuredProductSlide">
                @forelse ($featuredproducts as $promotional)
                    <div class="item">
                        <div class="product-card">
                            <div class="product-image-wrapper">
                                <a href="{{ url('product/' . $promotional->ProductSlug) }}">
                                    <img src="{{ asset($promotional->ViewProductImage) }}"
                                        alt="{{ $promotional->ProductName }}" loading="lazy">
                                </a>
                                @if($promotional->Discount > 0)
                                    <span class="discount-badge">-{{ $promotional->Discount }}%</span>
                                @endif
                            </div>
                            <div class="product-info">
                                <h2 class="product-name text-truncate">
                                    <a href="{{ url('product/' . $promotional->ProductSlug) }}">{{ $promotional->ProductName }}</a>
                                </h2>
                                <div class="price-box">
                                    @if($promotional->ProductRegularPrice > $promotional->ProductSalePrice)
                                        <del class="old-price">৳{{ round($promotional->ProductRegularPrice) }}</del>
                                    @endif
                                    <span class="sale-price">৳{{ round($promotional->ProductSalePrice) }}</span>
                                </div>
                            </div>
                            <form name="form" action="{{url('add-to-cart')}}" method="POST">
                                @method('POST')
                                @csrf
                                <input type="text" name="color" id="product_colorold" hidden>
                                <input type="text" name="size" id="product_sizeold" hidden>
                                <input type="text" name="product_id" value="{{ $promotional->id }}" hidden>
                                <input type="text" name="qty" value="1" id="qtyor" hidden>
                                <button class="btn-add-cart" type="submit">অর্ডার করুন</button>
                            </form>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
</div>
@else

@endif

<!-- add section -->
<div class="container mb-lg-3 mb-2">
    <div class="row gutters-10">
        @if (count($adds) == '2')
            @forelse ($adds as $add)
                <div class="col-lg-6 col-6 ps-0">
                    <div class="media-banner mb-1 mb-lg-0">
                        <a href="{{ $add->add_link }}" target="_blank" class="banner-container">
                            <img src="{{ asset($add->add_image) }}" alt="{{ env('APP_NAME') }}"
                                class="img-fluid ls-is-cached lazyloaded">
                        </a>
                    </div>
                </div>
            @empty
            @endforelse
        @else
            @forelse ($adds as $add)
                <div class="col-lg-12 col-12 ps-0">
                    <div class="media-banner mb-1 mb-lg-0">
                        <a href="{{ $add->add_link }}" target="_blank" class="banner-container">
                            <img src="{{ asset($add->add_image) }}" alt="{{ env('APP_NAME') }}"
                                class="img-fluid ls-is-cached lazyloaded">
                        </a>
                    </div>
                </div>
            @empty
            @endforelse
        @endif
    </div>
</div>

@if(count($topproducts)>0)
<!-- Promotional Products -->
<div class="container pt-0 pb-4">
    <div class="row bg-white pb-4">
        <div class="col-12" style="border-bottom: 1px solid #24a86c;padding-left: 0;display: flex;justify-content: space-between;">
            <div class="px-2 p-md-3 pt-0 d-flex justify-content-between" style="padding-bottom:4px !important;padding-top: 8px !important;">
                <h4 class="m-0"><b>Promotional Offers</b></h4>
            </div>
            <a href="{{ url('promotional/products') }}" class="btn btn-danger btn-sm mb-0" style="padding: 2px;height: 26px;color: white;font-weight: bold;margin-top:9px;">VIEW ALL</a>
        </div>
        <div class="col-12">
            <div class="owl-carousel" id="promotionalofferSlide">
                @forelse ($topproducts as $promotional)
                    <div class="item">
                        <div class="product-card">
                            <div class="product-image-wrapper">
                                <a href="{{ url('product/' . $promotional->ProductSlug) }}">
                                    <img src="{{ asset($promotional->ViewProductImage) }}"
                                        alt="{{ $promotional->ProductName }}" loading="lazy">
                                </a>
                                @if($promotional->Discount > 0)
                                    <span class="discount-badge">-{{ $promotional->Discount }}%</span>
                                @endif
                            </div>
                            <div class="product-info">
                                <h2 class="product-name text-truncate">
                                    <a href="{{ url('product/' . $promotional->ProductSlug) }}">{{ $promotional->ProductName }}</a>
                                </h2>
                                <div class="price-box">
                                    @if($promotional->ProductRegularPrice > $promotional->ProductSalePrice)
                                        <del class="old-price">৳{{ round($promotional->ProductRegularPrice) }}</del>
                                    @endif
                                    <span class="sale-price">৳{{ round($promotional->ProductSalePrice) }}</span>
                                </div>
                            </div>
                            <form name="form" action="{{url('add-to-cart')}}" method="POST">
                                @method('POST')
                                @csrf
                                <input type="text" name="color" id="product_colorold" hidden>
                                <input type="text" name="size" id="product_sizeold" hidden>
                                <input type="text" name="product_id" value="{{ $promotional->id }}" hidden>
                                <input type="text" name="qty" value="1" id="qtyor" hidden>
                                <button class="btn-add-cart" type="submit">অর্ডার করুন</button>
                            </form>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
</div>
@else

@endif

@forelse ($categoryproducts as $key=>$categoryproduct)
    @if (count($categoryproduct->products) > 0)
        <!-- Category Products -->
        <div class="container pt-0 pb-4">
            <div class="row bg-white pb-0">
                <div class="col-12" style="border-bottom: 1px solid #24a86c;padding-left: 0;display: flex;justify-content: space-between;">
                    <div class="px-2 p-md-3 pt-0 d-flex justify-content-between" style="padding-bottom:4px !important;padding-top: 8px !important;">
                        <h4 class="m-0"><b>{{ $categoryproduct->category_name }}</b></h4>
                    </div>
                    <a href="{{url('products/category/'.$categoryproduct->slug)}}" class="btn btn-danger btn-sm mb-0" style="padding: 2px;height: 26px;color: white;font-weight: bold;margin-top:9px;background: #24a86c;border: 1px solid #24a86c;">VIEW ALL</a>
                </div>


                @forelse ($categoryproduct->products->take(12) as $product)
                    <div class="col-6 col-md-4 col-lg-2 mb-4">
                        <div class="product-card">
                            <div class="product-image-wrapper">
                                <a href="{{ url('product/' . $product->ProductSlug) }}">
                                    <img src="{{ asset($product->ViewProductImage) }}"
                                        alt="{{ $product->ProductName }}" loading="lazy">
                                </a>
                                @if($product->Discount > 0)
                                    <span class="discount-badge">-{{ $product->Discount }}%</span>
                                @endif
                            </div>
                            <div class="product-info">
                                <h2 class="product-name text-truncate">
                                    <a href="{{ url('product/' . $product->ProductSlug) }}">{{ $product->ProductName }}</a>
                                </h2>
                                <div class="price-box">
                                    @if($product->ProductRegularPrice > $product->ProductSalePrice)
                                        <del class="old-price">৳ {{ round($product->ProductRegularPrice) }}</del>
                                    @endif
                                    <span class="sale-price">৳ {{ round($product->ProductSalePrice) }}</span>
                                </div>
                            </div>
                            <form name="form" action="{{url('add-to-cart')}}" method="POST">
                                @method('POST')
                                @csrf
                                <input type="text" name="color" id="product_colorold" hidden>
                                <input type="text" name="size" id="product_sizeold" hidden>
                                <input type="text" name="product_id" value="{{ $product->id }}" hidden>
                                <input type="text" name="qty" value="1" id="qtyor" hidden>
                                <button class="btn-add-cart" type="submit">অর্ডার করুন</button>
                            </form>
                        </div>
                    </div>
                @empty
                @endforelse

                </div>
            </div>
        </div>
    @else
    @endif

@empty
@endforelse


@endsection
