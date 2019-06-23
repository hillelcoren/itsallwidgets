<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventCity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flutter_events', function(Blueprint $table) {
            $table->string('city')->default('');
            $table->string('banner_color')->default('');
            $table->integer('tweet_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('flutter_events', function(Blueprint $table) {
            $table->dropColumn('city');
            $table->dropColumn('banner_color');
            $table->dropColumn('tweet_count');
        });

    }
}
