<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFeaturedApps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flutter_apps', function(Blueprint $table) {
            $table->smallInteger('featured')->default(0);
            $table->integer('store_review_count')->default(0);
            $table->float('store_rating', 8, 7)->default(0);
        });

        Schema::table('users', function(Blueprint $table) {
            $table->boolean('is_editor')->default(false);
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
            $table->dropColumn('featured');
            $table->dropColumn('store_review_count');
            $table->dropColumn('store_rating');
        });

        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('is_editor');
        });
    }
}
