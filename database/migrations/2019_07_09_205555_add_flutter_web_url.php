<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFlutterWebUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flutter_apps', function(Blueprint $table) {
            $table->boolean('is_mobile')->default(false);
            $table->boolean('is_web')->default(false);
            $table->string('flutter_web_url')->default('');
        });

        DB::statement('UPDATE flutter_apps SET is_mobile = 1;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('flutter_apps', function(Blueprint $table) {
            $table->dropColumn('is_mobile');
            $table->dropColumn('is_web');
            $table->dropColumn('flutter_web_url');
        });
    }
}
