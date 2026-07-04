<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToBasicinfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('basicinfos', function (Blueprint $table) {
            $table->string('usd_rate')->nullable()->after('email');
            $table->text('app')->nullable()->after('address');
            $table->text('copyright')->nullable()->after('app');
            $table->string('meta_tittle')->nullable()->after('copyright');
            $table->text('meta_description')->nullable()->after('meta_tittle');
            $table->text('meta_keyword')->nullable()->after('meta_description');
            $table->string('site_sologan')->nullable()->after('meta_keyword');
            $table->text('favicon')->nullable()->after('logo');
            $table->text('og_images')->nullable()->after('favicon');
            $table->text('marquee_text')->nullable()->after('og_images');
        });
    }

    public function down()
    {
        Schema::table('basicinfos', function (Blueprint $table) {
            $table->dropColumn([
                'usd_rate', 'app', 'copyright', 'meta_tittle',
                'meta_description', 'meta_keyword', 'site_sologan',
                'favicon', 'og_images', 'marquee_text'
            ]);
        });
    }
}
