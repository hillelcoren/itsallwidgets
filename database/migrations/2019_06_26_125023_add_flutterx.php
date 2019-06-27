<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFlutterx extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flutter_artifacts', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('user_id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('url')->unique();
            $table->text('comment')->nullable();
            $table->enum('type', ['article', 'video', 'library']);
            $table->string('source_url')->nullable();
            $table->string('repo_url')->nullable();
            $table->string('image_url')->nullable();
            $table->string('gif_url')->nullable();
            $table->date('published_date');
            $table->boolean('is_visible')->default(true);
            $table->boolean('is_approved')->default(false);
            $table->text('meta_description')->nullable();
            $table->string('meta_author')->nullable();
            $table->string('meta_author_url')->nullable();
            $table->string('meta_author_twitter')->nullable();
            $table->string('meta_publisher')->nullable();
            $table->string('meta_publisher_twitter')->nullable();
        });

        Schema::table('flutter_artifacts', function(Blueprint $table) {
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
        Schema::drop('flutter_artifacts');
    }
}
