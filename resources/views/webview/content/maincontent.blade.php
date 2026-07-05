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
    .section {
        padding-bottom: 28px;
    }
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 2px solid #eee;
        padding: 10px 0 12px;
        margin-bottom: 16px;
    }
    .section-title {
        font-size: 18px;
        font-weight: 700;
        margin: 0;
        color: #111;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .btn-view-all {
        font-size: 12px;
        font-weight: 600;
        color: #fff;
        background: var(--theme-color);
        padding: 5px 16px;
        text-decoration: none;
        transition: opacity 0.2s;
        line-height: 1.5;
        display: inline-block;
        border: none;
    }
    .btn-view-all:hover {
        opacity: 0.85;
        color: #fff;
    }
</style>

<div class="container px-0">
    <div class="row g-0">
        <div class="col-12">
            <div class="owl-carousel owl-theme" id="slider">
                @forelse ($sliders as $slider)
                    <div class="item" style="margin:0 !important;">
                        <img src="{{ asset($slider->slider_image) }}"
                            alt="{{ $slider->slider_title }}">
                    </div>
                @empty
                @endforelse
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
<div class="container section">
    <div class="section-header">
        <h4 class="section-title">Featured Products</h4>
        <a href="{{ url('featured/products') }}" class="btn-view-all">VIEW ALL</a>
    </div>
    <div class="owl-carousel" id="featuredProductSlide">
        @forelse ($featuredproducts as $promotional)
            <div class="item">
                @include('webview.partials.product-card', ['product' => $promotional])
            </div>
        @empty
        @endforelse
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
<div class="container section">
    <div class="section-header">
        <h4 class="section-title">Promotional Offers</h4>
        <a href="{{ url('promotional/products') }}" class="btn-view-all">VIEW ALL</a>
    </div>
    <div class="owl-carousel" id="promotionalofferSlide">
        @forelse ($topproducts as $promotional)
            <div class="item">
                @include('webview.partials.product-card', ['product' => $promotional])
            </div>
        @empty
        @endforelse
    </div>
</div>
@else

@endif

@forelse ($categoryproducts as $key=>$categoryproduct)
    @if (count($categoryproduct->products) > 0)
        <div class="container section">
            <div class="section-header">
                <h4 class="section-title">{{ rtrim($categoryproduct->category_name, '.') }}</h4>
                <a href="{{url('products/category/'.$categoryproduct->slug)}}" class="btn-view-all">VIEW ALL</a>
            </div>
            <div class="owl-carousel" id="CategoryProductSlide{{ $key }}">
                @forelse ($categoryproduct->products->take(12) as $product)
                    <div class="item">
                        @include('webview.partials.product-card', ['product' => $product])
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    @endif

@empty
@endforelse

<input type="hidden" id="CountSlider" value="{{ count($categoryproducts) }}">

@endsection
