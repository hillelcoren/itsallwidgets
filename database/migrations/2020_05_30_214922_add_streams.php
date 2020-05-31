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
        Schema::create('flutter_streams', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('user_id')->nullable();
            $table->enum('source', ['youtube', 'twitch']);
            $table->string('name');
            $table->text('description');
            $table->string('video_id');
            $table->dateTime('published_at');
            $table->dateTime('starts_at');
            $table->string('channel_id');
            $table->string('thumbnail_url');
            $table->string('channel_name');
            $table->boolean('is_visible')->default(false);
            $table->integer('view_count');
            $table->integer('like_count');
            $table->integer('comment_count');
        });

        Schema::table('flutter_streams', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
    }
}
