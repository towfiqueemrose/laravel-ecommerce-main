@extends('webview.master')

@section('maincontent')
@section('title')
    {{ env('APP_NAME') }}-Search Products
@endsection

<div class="body-content outer-top-xs">
    <div class="breadcrumb pt-2">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-inner p-0">
                        <ul class="list-inline list-unstyled mb-0">
                            <li><a href="#"
                                    style="text-transform: capitalize !important;color: #888;padding-right: 12px;font-size: 12px;">Home
                                    > Search > <span class="active"></span>Products</span>
                                </a></li>
                        </ul>
                    </div>
                </div>
                <!-- /.breadcrumb-inner -->
            </div>
        </div>
        <!-- /.container -->
    </div>
  
    
    <div class='container'>
        <div class='row'> 
            <!-- /.sidebar -->
            <div class='col-md-12' id="cateoryPro">
                <div class="container" >
                    
                    <div class="row g-3 pt-2 pb-2" style="background: white;">
                    
                        @forelse ($searchproducts as $categoryproduct)
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="product-image-wrapper">
                                        <a href="{{ url('product/' . $categoryproduct->ProductSlug) }}">
                                            <img src="{{ asset($categoryproduct->ProductImage) }}"
                                                alt="{{ $categoryproduct->ProductName }}" loading="lazy">
                                        </a>
                                    </div>
                                    <div class="product-info">
                                        <h2 class="product-name text-truncate">
                                            <a href="{{ url('product/' . $categoryproduct->ProductSlug) }}">{{ $categoryproduct->ProductName }}</a>
                                        </h2>
                                        <div class="price-box">
                                            @if($categoryproduct->ProductRegularPrice > $categoryproduct->ProductSalePrice)
                                                <del class="old-price">৳{{ round($categoryproduct->ProductRegularPrice) }}</del>
                                            @endif
                                            <span class="sale-price">৳{{ round($categoryproduct->ProductSalePrice) }}</span>
                                        </div>
                                    </div>
                                    <button class="btn-add-cart" onclick="buynow({{ $categoryproduct->id }})">অর্ডার করুন</button>
                                </div>
                            </div>
                        @empty
                            <h2 class="p-4 text-center"><b>No Products found...</b></h2>
                        @endforelse
                    </div>

                </div>
                <!-- /.category-product -->


                <!-- /.tab-content -->
                <div class="clearfix filters-container">
                    <div class="text-right">
                        <div class="pagination-container">

                        </div>
                        <!-- /.pagination-container -->
                    </div>
                    <!-- /.text-right -->

                </div>
                <!-- /.filters-container -->

            </div>
            <!-- /.col -->
        </div>

        <!-- ============================================== BRANDS CAROUSEL : END ============================================== -->
    </div>
    <!-- /.container -->

</div>

@endsection
