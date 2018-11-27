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
            $table->string('avatar_url');
            $table->text('short_description');
            $table->text('long_description')->nullable();
            $table->text('private_notes')->nullable();
            $table->smallInteger('episode')->nullable()->unique();
            $table->smallInteger('download_count')->default(0);
            $table->integer('file_size')->default(0);
            $table->string('file_duration')->default(0);
            $table->string('reddit_url')->nullable();
            $table->string('website_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('app_url')->nullable();
            $table->boolean('is_visible')->default(false);
            $table->boolean('is_uploaded')->default(false);
            $table->timestamp('published_at')->nullable();
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
