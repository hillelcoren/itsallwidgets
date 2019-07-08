<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flutter_events', function(Blueprint $table) {
            $table->integer('rsvp_limit')->default(0);
            $table->integer('rsvp_yes')->default(0);
            $table->integer('rsvp_waitlist')->default(0);
            $table->string('meetup_id')->nullable();
            $table->integer('meetup_group_id')->default(0);
            $table->text('directions')->nullable();
            $table->string('meetup_group_name')->nullable();
            $table->string('image_url')->nullable();
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
            $table->dropColumn('rsvp_limit');
            $table->dropColumn('rsvp_yes');
            $table->dropColumn('rsvp_waitlist');
            $table->dropColumn('meetup_id');
            $table->dropColumn('meetup_group_id');
            $table->dropColumn('directions');
            $table->dropColumn('meetup_group_name');
            $table->dropColumn('image_url');
        });
    }
}
