<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStreams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flutter_channels', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->enum('source', ['youtube', 'twitch']);
            $table->string('name');
            $table->string('channel_id');
            $table->text('description');
            $table->string('custom_url');
            $table->string('thumbnail_url');
            $table->string('country');
            $table->boolean('is_visible')->default(false);
            $table->boolean('is_english')->default(false);
        });

        Schema::create('flutter_streams', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('channel_id');
            $table->string('name');
            $table->text('description');
            $table->string('video_id');
            $table->dateTime('published_at');
            $table->dateTime('starts_at');
            $table->string('thumbnail_url');
            $table->integer('view_count');
            $table->integer('like_count');
            $table->integer('comment_count');
            $table->boolean('is_english')->default(false);
        });

        Schema::table('flutter_streams', function(Blueprint $table) {
            $table->foreign('channel_id')->references('id')->on('flutter_channels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('flutter_streams');
        Schema::drop('flutter_channels');
    }
}
