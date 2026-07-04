<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Addbanner;
use App\Models\Policymenu;
use App\Models\Servicepackage;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        // Categories
        $electronics = Category::create(['category_name' => 'Electronics', 'category_icon' => 'public/images/category/electronics.png']);
        $fashion = Category::create(['category_name' => 'Fashion', 'category_icon' => 'public/images/category/fashion.png']);
        $home = Category::create(['category_name' => 'Home & Kitchen', 'category_icon' => 'public/images/category/home.png']);
        $beauty = Category::create(['category_name' => 'Beauty & Health', 'category_icon' => 'public/images/category/beauty.png']);

        // Subcategories
        $mobile = Subcategory::create(['sub_category_name' => 'Mobile Phones', 'category_id' => $electronics->id]);
        $laptop = Subcategory::create(['sub_category_name' => 'Laptops', 'category_id' => $electronics->id]);
        $headphone = Subcategory::create(['sub_category_name' => 'Headphones', 'category_id' => $electronics->id]);

        $men = Subcategory::create(['sub_category_name' => 'Men\'s Wear', 'category_id' => $fashion->id]);
        $women = Subcategory::create(['sub_category_name' => 'Women\'s Wear', 'category_id' => $fashion->id]);
        $kids = Subcategory::create(['sub_category_name' => 'Kids Wear', 'category_id' => $fashion->id]);

        $furniture = Subcategory::create(['sub_category_name' => 'Furniture', 'category_id' => $home->id]);
        $kitchen = Subcategory::create(['sub_category_name' => 'Kitchen Appliances', 'category_id' => $home->id]);

        $skincare = Subcategory::create(['sub_category_name' => 'Skin Care', 'category_id' => $beauty->id]);

        // Brands
        $samsung = Brand::create(['brand_name' => 'Samsung', 'brand_icon' => 'public/images/brand/samsung.png']);
        $apple = Brand::create(['brand_name' => 'Apple', 'brand_icon' => 'public/images/brand/apple.png']);
        $nike = Brand::create(['brand_name' => 'Nike', 'brand_icon' => 'public/images/brand/nike.png']);

        // Products
        $products = [
            ['category_id' => $electronics->id, 'subcategory_id' => $mobile->id, 'brand_id' => $apple->id, 'ProductName' => 'iPhone 15 Pro Max', 'ProductSku' => 'IP15PM-256', 'ProductRegularPrice' => 159999, 'ProductSalePrice' => 149999, 'Discount' => '6', 'ProductImage' => 'public/images/product/iphone15.png', 'ViewProductImage' => 'public/images/product/iphone15.png', 'top_rated' => '1', 'frature' => '1'],
            ['category_id' => $electronics->id, 'subcategory_id' => $mobile->id, 'brand_id' => $samsung->id, 'ProductName' => 'Samsung Galaxy S24 Ultra', 'ProductSku' => 'SGS24U-256', 'ProductRegularPrice' => 139999, 'ProductSalePrice' => 129999, 'Discount' => '7', 'ProductImage' => 'public/images/product/s24ultra.png', 'ViewProductImage' => 'public/images/product/s24ultra.png', 'top_rated' => '1'],
            ['category_id' => $electronics->id, 'subcategory_id' => $laptop->id, 'brand_id' => $apple->id, 'ProductName' => 'MacBook Pro 16 M3 Pro', 'ProductSku' => 'MBP16-M3', 'ProductRegularPrice' => 289999, 'ProductSalePrice' => 279999, 'Discount' => '3', 'ProductImage' => 'public/images/product/mbp16.png', 'ViewProductImage' => 'public/images/product/mbp16.png', 'frature' => '1'],
            ['category_id' => $fashion->id, 'subcategory_id' => $men->id, 'brand_id' => $nike->id, 'ProductName' => 'Nike Air Max Casual Shoe', 'ProductSku' => 'NK-AM-001', 'ProductRegularPrice' => 12999, 'ProductSalePrice' => 9999, 'Discount' => '23', 'ProductImage' => 'public/images/product/nikeam.png', 'ViewProductImage' => 'public/images/product/nikeam.png', 'top_rated' => '1'],
            ['category_id' => $home->id, 'subcategory_id' => $furniture->id, 'ProductName' => 'Modern Wooden Bookshelf', 'ProductSku' => 'FUR-BS-001', 'ProductRegularPrice' => 24999, 'ProductSalePrice' => 19999, 'Discount' => '20', 'ProductImage' => 'public/images/product/bookshelf.png', 'ViewProductImage' => 'public/images/product/bookshelf.png'],
            ['category_id' => $beauty->id, 'subcategory_id' => $skincare->id, 'ProductName' => 'Vitamin C Brightening Serum', 'ProductSku' => 'BEA-VC-001', 'ProductRegularPrice' => 1499, 'ProductSalePrice' => 899, 'Discount' => '40', 'ProductImage' => 'public/images/product/vcserum.png', 'ViewProductImage' => 'public/images/product/vcserum.png', 'frature' => '1'],
        ];

        foreach ($products as $data) {
            Product::create($data);
        }

        // Sliders
        Slider::create(['slider_small_title' => 'New Arrivals', 'slider_title' => 'iPhone 15 Pro Max', 'slider_text' => 'Experience the power of A17 Pro chip', 'slider_btn_name' => 'Shop Now', 'slider_btn_link' => '/product/iphone-15-pro-max', 'slider_image' => 'public/images/slider/slide1.png']);
        Slider::create(['slider_small_title' => 'Best Deals', 'slider_title' => 'Summer Fashion Sale', 'slider_text' => 'Up to 50% off on trendy fashion', 'slider_btn_name' => 'Explore', 'slider_btn_link' => '/products/category/fashion', 'slider_image' => 'public/images/slider/slide2.png']);

        // Add Banners
        Addbanner::create(['add_link' => '/products/category/electronics', 'add_image' => 'public/images/addbanner/banner1.png']);
        Addbanner::create(['add_link' => '/products/category/fashion', 'add_image' => 'public/images/addbanner/banner2.png']);

        // Policy Menus
        Policymenu::create(['policy_menu_name' => 'About us', 'policy_text' => 'We are a leading e-commerce platform in Bangladesh.']);
        Policymenu::create(['policy_menu_name' => 'Contact Us', 'policy_text' => 'Contact our support team for any assistance.']);
        Policymenu::create(['policy_menu_name' => 'Terms & Condition', 'policy_text' => 'Please read our terms and conditions carefully.']);
        Policymenu::create(['policy_menu_name' => 'FAQ', 'policy_text' => 'Frequently asked questions about our services.']);

        // Service Packages
        Servicepackage::create(['servicepackage_name' => 'Free Shipping', 'package_text' => 'Free delivery on orders over Tk. 1000', 'roles' => 1]);
        Servicepackage::create(['servicepackage_name' => 'Easy Returns', 'package_text' => '7-day easy return policy', 'roles' => 1]);
        Servicepackage::create(['servicepackage_name' => '24/7 Support', 'package_text' => 'Round the clock customer support', 'roles' => 1]);

        // Menus
        Menu::create(['menu_name' => 'Home']);
        Menu::create(['menu_name' => 'Combo Offer']);
        Menu::create(['menu_name' => 'News Feed']);
        Menu::create(['menu_name' => 'Order Track']);
    }
}
