<?php

use App\Models\Language;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRtlLanguages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $languages = [
            ['name' => 'Arabic', 'locale' => 'ar'],
            ['name' => 'Hebrew', 'locale' => 'he'],
        ];

        foreach ($languages as $language) {
            Language::create($language);
        }

        Schema::table('flutter_streams', function(Blueprint $table) {
            $table->boolean('was_tweeted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('flutter_streams', function(Blueprint $table) {
            $table->dropColumn('was_tweeted');
        });
    }
}
