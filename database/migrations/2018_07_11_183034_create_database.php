<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flutter_apps', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->string('short_description');
            $table->text('long_description');
            $table->string('screenshot1_url');
            $table->string('website_url')->nullable();
            $table->string('repo_url')->nullable();
            $table->string('apple_url')->nullable();
            $table->string('google_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('flutter_apps');
    }
}
