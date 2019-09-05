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
            $table->boolean('is_pro_iaw')->default(true);
            $table->boolean('is_pro_fe')->default(true);
            $table->boolean('is_pro_fx')->default(true);
            $table->boolean('is_subscribed')->default(true);
            $table->string('profile_key')->nullable()->unique();
            $table->string('country_code')->nullable()->default('');
            $table->boolean('is_for_hire')->default(false);
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
            $table->string('instagram_url')->nullable()->default('');
        });

        DB::statement('ALTER TABLE users ADD FULLTEXT fulltext_index (name, handle, bio, country_code, website_url, github_url, youtube_url, twitter_url, medium_url, linkedin_url, instagram_url)');
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
            $table->dropColumn('profile_key');
            $table->dropColumn('is_subscribed');
            $table->dropColumn('country_code');
            $table->dropColumn('is_for_hire');
            $table->dropColumn('instagram_url');
        });
    }
}
