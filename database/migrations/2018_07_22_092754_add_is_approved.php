<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsApproved extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->boolean('is_admin')->default(false);
        });

        Schema::table('flutter_apps', function(Blueprint $table) {
            $table->boolean('is_approved')->default(false);
            $table->string('instagram_url')->nullable();
            $table->text('hash_tags')->nullable();
        });

        DB::statement('update flutter_apps set is_approved = true;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
