@extends('webview.master')

@section('maincontent')
@section('title')
    {{ env('APP_NAME') }}-{{ $categorysingle->brand_name }}
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
                                    > bramd > <span class="active"></span>{{ $categorysingle->brand_name }}</span>
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
                <div class="container">

                    <div class="row g-3 pt-2 pb-2" style="background: white;">
                    
                        @forelse ($categoryproducts as $categoryproduct)
                            <div class="col-6 col-lg-2">
                                <div class="product-card">
                                    <div class="product-image-wrapper">
                                        <a href="{{ url('product/' . $categoryproduct->ProductSlug) }}">
                                            <img src="{{ asset($categoryproduct->ProductImage) }}"
                                                alt="{{ $categoryproduct->ProductName }}" loading="lazy">
                                        </a>
                                        @if($categoryproduct->Discount > 0)
                                            <span class="discount-badge">-{{ $categoryproduct->Discount }}%</span>
                                        @endif
                                    </div>
                                    <div class="product-info">
                                        <h2 class="product-name text-truncate">
                                            <a href="{{ url('product/' . $categoryproduct->ProductSlug) }}">{{ $categoryproduct->ProductName }}</a>
                                        </h2>
                                        <div class="price-box">
                                            <span class="sale-price">৳{{ round($categoryproduct->ProductSalePrice) }}</span>
                                        </div>
                                    </div>
                                    <form name="form" action="{{url('add-to-cart')}}" method="POST">
                                        @method('POST')
                                        @csrf
                                        <input type="text" name="color" id="product_colorold" hidden>
                                        <input type="text" name="size" id="product_sizeold" hidden>
                                        <input type="text" name="product_id" value="{{ $categoryproduct->id }}" hidden>
                                        <input type="text" name="qty" value="1" id="qtyor" hidden>
                                        <button class="btn-add-cart" type="submit">অর্ডার করুন</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <h2 class="p-4 text-center"><b>No Products found...</b></h2>
                        @endforelse
                    </div>

                </div>
            </div>
            <!-- /.col -->
        </div>

        <!-- ============================================== BRANDS CAROUSEL : END ============================================== -->
    </div>
    <!-- /.container -->

</div>



{{-- csrf --}}
<input type="hidden" name="_token" value="{{ csrf_token() }}" />

<script>
    var token = $("input[name='_token']").val();
 

 
</script>

<style>
    @media only screen and (max-width: 768px) {
        #cateoryProSidebar {
            padding-right: 0;
        }

        #cateoryPro {
            padding-left: 0;
        }
    }

    #cateoryProSidebar {
        padding-left: 0;
    }

    #cateoryPro {
        padding-right: 0px;
    }

    .sidebar-widget .sidebar-widget-body .accordion .accordion-group .accordion-heading .accordion-toggle.collapsed:after {
        color: #636363;
        content: "\f067";
        font-family: fontawesome;
        font-weight: normal;
    }

    .sidebar .sidebar-module-container .sidebar-widget .sidebar-widget-body .accordion .accordion-group .accordion-heading .accordion-toggle:after {
        content: "\f068";
        float: right;
        font-family: fontawesome;
    }

    .widget-title {
        font-size: 16px;
        text-align: center;
        border-bottom: 1px solid #e9e9e9;
        padding-bottom: 10px;
        margin: 0;
    }

    .list {
        list-style: none;
    }

    #liaside {
        color: #858585;
        font-weight: bold;
    }

    .breadcrumb {
        padding: 5px 0;
        border-bottom: 1px solid #e9e9e9;
        background-color: #fafafa;
    }
</style>

@endsection
