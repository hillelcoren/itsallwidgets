<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->boolean('is_mentor')->default(false);
            $table->boolean('is_trainer')->default(false);
            $table->unsignedInteger('youtube_channel_id')->nullable()->unique();
        });

        Schema::table('users', function(Blueprint $table) {
            $table->foreign('channel_id')->references('id')->on('flutter_channels')->onDelete('cascade');
        });

        Schema::table('flutter_channels', function(Blueprint $table) {
            $table->boolean('match_all_videos')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('is_mentor');
            $table->dropColumn('is_trainer');
            $table->dropColumn('channel_id');
        });

        Schema::table('flutter_channels', function(Blueprint $table) {
            $table->dropColumn('match_all_videos');
        });
    }
}
