<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flutter_events', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('user_id');
            $table->string('event_name');
            $table->string('slug');
            $table->text('banner');
            $table->text('description')->nullable();
            $table->date('event_date');
            $table->text('address');
            $table->decimal('latitude', 10, 8)->default(0);
            $table->decimal('longitude', 11, 8)->default(0);
            $table->string('event_url')->unique()->nullable();
            $table->string('twitter_url')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->boolean('is_approved')->default(false);
            $table->integer('view_count')->default(0);
            $table->integer('click_count')->default(0);
        });

        Schema::table('flutter_events', function(Blueprint $table) {
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
        Schema::drop('flutter_events');
    }
}
