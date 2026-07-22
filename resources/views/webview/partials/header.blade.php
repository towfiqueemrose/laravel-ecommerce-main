<header class="header-style-1">




    <div class="main-header" id="myHeader" style="background: #fff;border-bottom: 1px solid #e9e9e9;">
        <div class="container">
            <div class="row align-items-center" style="margin: 0">
                <div class="col-9 col-sm-9 col-md-9 col-lg-2 logo-holder ps-0">
                    <!-- ============================================================= LOGO ============================================================= -->
                    <div class="logo">
                        <button type="button" onclick="openNav()" id="menubutton" class="d-lg-none">
                            <img src="{{asset('public/menuooo.png')}}" style="width:40px">
                        </button>

                        <a href="{{ url('/') }}" id="logoimage">
                            @if ($basicinfo && $basicinfo->logo)
                                <img src="{{ asset($basicinfo->logo) }}" alt="" id="logosm" style="max-height: 60px; width: auto; object-fit: contain;">
                            @else
                                <span style="font-size: 20px; font-weight: 700; color: var(--theme-color);">{{ env('APP_NAME') }}</span>
                            @endif
                        </a>
                    </div>
                    <!-- /.logo -->
                    <!-- ============================================================= LOGO : END ============================================================= -->
                </div>
                <!-- /.logo-holder -->

                <div class="col-2 col-sm-2 col-md-2  col-lg-6 top-search-holder" id="d-sm-none">
                    <!-- /.contact-row -->
                    <!-- ============================================================= SEARCH AREA ============================================================= -->
                    <div class="search-area" id="d-sm-none">
                        <form method="GET" name="form" action="{{url('search')}}">
                            @csrf
                            <div class="control-group">

                                <input class="search-field" placeholder="Search here..." name="search">

                                <button class="search-button" type="submit"></button>

                            </div>
                        </form>
                    </div>
                    <!-- /.search-area -->
                    <!-- ============================================================= SEARCH AREA : END ============================================================= -->
                </div>
                <!-- /.top-search-holder -->

                <div class="col-3 col-sm-3 col-md-3 col-lg-4 top-cart-row p-0">
                    <div class="d-flex align-items-center justify-content-end gap-3" style="height:100%;">

                        @auth
                            <a href="{{ url('user/dashboard') }}" class="d-none d-lg-inline-block" style="font-size:13px;color:#333;text-decoration:none;font-weight:500;white-space:nowrap;">Dashboard</a>
                        @else
                            <a href="{{ url('login') }}" class="d-none d-lg-inline-block" style="font-size:13px;color:#333;text-decoration:none;font-weight:500;white-space:nowrap;">Login</a>
                            <span class="d-none d-lg-inline-block" style="color:#ddd;font-size:13px;">|</span>
                            <a href="{{ url('register') }}" class="d-none d-lg-inline-block" style="font-size:13px;color:#333;text-decoration:none;font-weight:500;white-space:nowrap;">Sign Up</a>
                        @endauth

                        <div class="dropdown-cart position-relative" style="padding-left:0;">
                            <a href="#" class="dropdown" onclick="checkcart(this)" data-bs-toggle="dropdown" id="smcarticon">
                                <i class="fa-solid fa-cart-plus" style="color:var(--theme-color);font-size:24px;"></i>
                                <span class="count" style="position:absolute;top:-8px;right:-12px;background:var(--secondary-color);color:#fff;font-size:10px;width:18px;height:18px;display:flex;align-items:center;justify-content:center;border-radius:50%;">{{ count(Cart::content()) }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" style="min-width: 320px; padding: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.15); border: none; border-radius: 8px;">
                                <li id="checkcartview"></li>
                            </ul>
                        </div>



                        <a class="d-lg-none" data-bs-toggle="modal" data-bs-target="#searchPopup" href="#">
                            <i class="fas fa-search" style="color:var(--theme-color);font-size:20px;"></i>
                        </a>
                    </div>
                </div>
                <!-- /.top-cart-row -->
            </div>
            <!-- /.row -->

        </div>
        <!-- /.container -->

    </div>


    <!-- side bar panel start -->
    <div id="mySidepanel" class="sidepanel d-lg-none">
        <div class="side-menu-header ">
            <div class="side-menu-close" onclick="closeNav()">
                <i class="fas fa-close"></i>
            </div>
            <div class="side-login px-3 pb-3" style="padding-top: 12px;padding-bottom: 15px; padding-left: 10px;">
                <a href=""></a>
                <a style="font-size: 16px" href="#">Categories</a>
            </div>
        </div>
        <ul class="level1-styles collapse show" id="id0">

            @forelse ($categories as $category)
                @if (count($category->subcategories) > 0)
                    <li>
                        <a href="{{ url('products/category/' . $category->slug) }}" class="collapsed"
                            data-bs-toggle="collapse"
                            data-bs-target="#id{{ $category->id }}">{{ $category->category_name }}<i
                                class="fas fa-plus" aria-hidden="true" id="plusicon"></i></a>
                        <ul class="collapse level2-styles" id="id{{ $category->id }}">
                            @foreach ($category->subcategories as $subcategory)
                                <li>
                                    <a
                                        href="{{ url('products/sub/category/' . $subcategory->slug) }}">{{ $subcategory->sub_category_name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    <li>
                        <a
                            href="{{ url('products/category/' . $category->slug) }}">{{ $category->category_name }}</a>
                    </li>
                @endif
            @empty
            @endforelse
        </ul>
    </div>
    <!-- side bar panel end -->
</header>

<!-- Search Popup Modal -->
<div class="modal fade" id="searchPopup" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 0px !important">
            <div class="modal-body" style="padding: 0px;">
                <div class="modalsearch-area">
                    <div class="control-group d-flex justify-content-between">
                        <input class="search-field mb-0" id="modalsearchinput" onkeyup="searchproduct()"
                            placeholder="Search here...">
                        <a class="search-button" data-bs-dismiss="modal" href="#"></a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div id="searchproductlist" style="background: white;margin: 10px;height: auto;overflow: scroll;">

    </div>
</div>

<style>
    .modalsearch-area .search-field {
        border: medium none;
        padding: 10px;
        border-right: none;
        float: left;
    }

    .modalsearch-area .search-button {
        display: inline-block;
        float: left;
        margin-top: -1px;
        padding: 6px 15px 7px;
        text-align: center;
        background-color: #e62e04;
        border: 1px solid #e62e04;
    }

    .modalsearch-area .search-button:after {
        color: #fff;
        content: "\f00d";
        font-family: fontawesome;
        font-size: 24px;
        line-height: 9px;
        vertical-align: middle;
    }
</style>
