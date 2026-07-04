<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThemeColorToBasicinfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('basicinfos', function (Blueprint $table) {
            $table->string('theme_color')->default('#24a86c')->after('marquee_text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('basicinfos', function (Blueprint $table) {
            $table->dropColumn('theme_color');
        });
    }
}
