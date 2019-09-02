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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_artifacts');
    }
}
