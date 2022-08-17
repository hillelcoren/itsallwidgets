<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDesktopLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flutter_apps', function(Blueprint $table) {
            $table->string('microsoft_url')->unique()->nullable();
            $table->string('snapcraft_url')->unique()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('flutter_apps', function(Blueprint $table) {
            $table->dropColumn('microsoft_url');
            $table->dropColumn('snapcraft_url');
        });
    }
}
