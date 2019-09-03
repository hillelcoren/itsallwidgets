<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProfileFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->boolean('is_pro_iaw')->default(false);
            $table->boolean('is_pro_fe')->default(true);
            $table->boolean('is_pro_fx')->default(true);
        });

        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('profile_url');
            $table->dropColumn('website_url');
            $table->dropColumn('github_url');
            $table->dropColumn('youtube_url');
            $table->dropColumn('twitter_url');
            $table->dropColumn('medium_url');
            $table->dropColumn('linkedin_url');
        });

        Schema::table('users', function(Blueprint $table) {
            $table->string('profile_url')->nullable()->default('');
            $table->string('website_url')->nullable()->default('');
            $table->string('github_url')->nullable()->default('');
            $table->string('youtube_url')->nullable()->default('');
            $table->string('twitter_url')->nullable()->default('');
            $table->string('medium_url')->nullable()->default('');
            $table->string('linkedin_url')->nullable()->default('');
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
            $table->dropColumn('is_pro_iaw');
            $table->dropColumn('is_pro_fe');
            $table->dropColumn('is_pro_fx');
        });
    }
}
