<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSecondaryColorToBasicinfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('basicinfos', function (Blueprint $table) {
            $table->string('secondary_color')->default('#ff0000')->after('theme_color');
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
            $table->dropColumn('secondary_color');
        });
    }
}
