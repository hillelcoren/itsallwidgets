<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLandingPageFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flutter_apps', function(Blueprint $table) {
            $table->string('background_color1')->default('7468E6');
            $table->string('background_color2')->default('C44B85');
            $table->string('background_rotation')->default('45');
            $table->string('font_color')->default('FFFFFF');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('background_color1');
            $table->dropColumn('background_color2');
            $table->dropColumn('background_rotation');
            $table->dropColumn('font_color');
        });
    }
}
