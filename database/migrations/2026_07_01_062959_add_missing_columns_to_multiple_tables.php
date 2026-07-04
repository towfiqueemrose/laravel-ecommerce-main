<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToMultipleTables extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'brand_id')) {
                $table->bigInteger('brand_id')->unsigned()->nullable()->after('subcategory_id');
            }
            if (!Schema::hasColumn('products', 'youtube_embade')) {
                $table->text('youtube_embade')->nullable()->after('PostImage');
            }
        });

        Schema::table('brands', function (Blueprint $table) {
            if (!Schema::hasColumn('brands', 'slug')) {
                $table->string('slug')->nullable()->after('brand_name');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
        });

        Schema::table('basicinfos', function (Blueprint $table) {
            if (!Schema::hasColumn('basicinfos', 'service_payment_status')) {
                $table->string('service_payment_status')->nullable()->after('marquee_text');
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['brand_id', 'youtube_embade']);
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
        });

        Schema::table('basicinfos', function (Blueprint $table) {
            $table->dropColumn('service_payment_status');
        });
    }
}
