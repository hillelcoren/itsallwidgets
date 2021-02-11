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
            $table->string('widget')->nullable()->unique();
            $table->string('widget_youtube_url')->default('');
            $table->string('widget_api_url')->default('');
            $table->string('widget_inheritance')->default('');
            $table->string('widget_version_added')->default('');
            $table->integer('widget_week')->default(0);
            $table->text('widget_description')->nullable();
            $table->text('widget_code_sample')->nullable();
            $table->text('widget_tip')->nullable();
            $table->text('widget_youtube_comment')->nullable();
            $table->text('widget_youtube_handle')->nullable();
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
            $table->dropColumn('widget');
            $table->dropColumn('widget_youtube_url');
            $table->dropColumn('widget_api_url');
            $table->dropColumn('widget_inheritance');
            $table->dropColumn('widget_version_added');
            $table->dropColumn('widget_week');
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
