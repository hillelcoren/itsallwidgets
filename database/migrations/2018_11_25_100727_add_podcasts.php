<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPodcasts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('podcast_episodes', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('title');
            $table->string('email');
            $table->string('short_description');
            $table->text('long_description')->nullable();
            $table->text('private_notes')->nullable();
            $table->smallInteger('episode')->nullable()->unique();
            $table->string('reddit_url')->nullable();
            $table->string('website_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('app_url')->nullable();
            $table->boolean('is_visible')->default(false);
            $table->boolean('is_uploaded')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('podcast_episodes');
    }
}
