<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFlutterCards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->string('rss_feed_url')->nullable();
            $table->string('widget')->nullable()->unique();
            $table->string('widget_voice')->nullable();
            $table->string('widget_youtube_url')->nullable();
            $table->string('widget_inheritance')->nullable();
            $table->string('widget_version_added')->nullable();
            $table->text('widget_description')->nullable();
            $table->text('widget_code_sample')->nullable();
            $table->text('widget_tip')->nullable();
            $table->string('widget_youtube_handle')->nullable();
            $table->text('widget_youtube_comment')->nullable();
        });

        Schema::table('flutter_apps', function(Blueprint $table) {
            $table->integer('campaign_id')->default(0);
            $table->text('campaign_question1')->nullable();
            $table->text('campaign_question2')->nullable();
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
            $table->dropColumn('rss_feed_url');
            $table->dropColumn('widget');
            $table->dropColumn('widget_voice');
            $table->dropColumn('widget_youtube_url');
            $table->dropColumn('widget_inheritance');
            $table->dropColumn('widget_version_added');
            $table->dropColumn('widget_description');
            $table->dropColumn('widget_code_sample');
            $table->dropColumn('widget_tip');
            $table->dropColumn('widget_youtube_comment');
            $table->dropColumn('widget_youtube_handle');
        });

        Schema::table('flutter_apps', function(Blueprint $table) {
            $table->dropColumn('campaign_id');
            $table->dropColumn('campaign_question1');
            $table->dropColumn('campaign_question2');
        });
    }
}
