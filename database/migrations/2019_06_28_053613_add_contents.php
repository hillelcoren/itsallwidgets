<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flutter_artifacts', function(Blueprint $table) {
            $table->mediumText('contents')->nullable();
        });

        DB::statement('ALTER TABLE flutter_artifacts ADD FULLTEXT fulltext_index (contents, title, comment, meta_author, meta_publisher, meta_description, meta_author_twitter, meta_publisher_twitter)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('flutter_artifacts', function(Blueprint $table) {
            $table->dropColumn('contents');
        });
    }
}
