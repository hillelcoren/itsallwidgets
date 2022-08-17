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
            $table->boolean('has_desktop_gif')->default(false);
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
            $table->dropColumn('has_desktop_gif');            
        });
    }
}
