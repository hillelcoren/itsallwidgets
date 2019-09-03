<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserActivities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_activities', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('activity_id');
            $table->enum('activity_type', ['flutter_app', 'flutter_artifact', 'flutter_event']);
            $table->boolean('is_visible')->default(true);
            $table->unique(['user_id', 'activity_id', 'activity_type']);
        });

        Schema::table('user_activities', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('users', function(Blueprint $table) {
            $table->string('handle')->nullable()->unique();
            $table->text('bio')->nullable();
            $table->boolean('is_pro')->default(false);
            $table->string('profile_url')->default('');
            $table->string('website_url')->default('');
            $table->string('github_url')->default('');
            $table->string('youtube_url')->default('');
            $table->string('twitter_url')->default('');
            $table->string('medium_url')->default('');
            $table->string('linkedin_url')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_activities');

        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('handle');
            $table->dropColumn('bio');
            $table->dropColumn('is_pro');
            $table->dropColumn('profile_url');
            $table->dropColumn('website_url');
            $table->dropColumn('github_url');
            $table->dropColumn('youtube_url');
            $table->dropColumn('twitter_url');
            $table->dropColumn('medium_url');
            $table->dropColumn('linkedin_url');
        });
    }
}
