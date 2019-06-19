<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTweetCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flutter_events', function(Blueprint $table) {
            $table->timestamp('archived_at')->nullable();
            $table->integer('twitter_click_count')->default(0);
            $table->integer('tweet_count')->default(0);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('flutter_events', function(Blueprint $table) {
            $table->dropColumn('archived_at');
            $table->dropColumn('twitter_click_count');
            $table->dropColumn('tweet_count');
        });
    }
}
