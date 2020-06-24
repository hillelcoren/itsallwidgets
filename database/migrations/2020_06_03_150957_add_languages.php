<?php

use App\Models\Language;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLanguages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name');
            $table->string('locale')->unique();
        });

        $languages = [
            ['name' => 'English', 'locale' => 'en'],
            ['name' => 'Italian', 'locale' => 'it'],
            ['name' => 'German', 'locale' => 'de'],
            ['name' => 'French', 'locale' => 'fr'],
            ['name' => 'Portuguese - Brazilian', 'locale' => 'pt'],
            ['name' => 'Dutch', 'locale' => 'nl'],
            ['name' => 'Spanish', 'locale' => 'es'],
            ['name' => 'Norwegian', 'locale' => 'nb'],
            ['name' => 'Danish', 'locale' => 'da'],
            ['name' => 'Japanese', 'locale' => 'ja'],
            ['name' => 'Swedish', 'locale' => 'sv'],
            ['name' => 'Lithuanian', 'locale' => 'lt'],
            ['name' => 'Polish', 'locale' => 'pl'],
            ['name' => 'Czech', 'locale' => 'cs'],
            ['name' => 'Croatian', 'locale' => 'hr'],
            ['name' => 'Albanian', 'locale' => 'sq'],
            ['name' => 'Greek', 'locale' => 'el'],
            ['name' => 'Slovenian', 'locale' => 'sl'],
            ['name' => 'Finnish', 'locale' => 'fi'],
            ['name' => 'Romanian', 'locale' => 'ro'],
            ['name' => 'Turkish', 'locale' => 'tr'],
            ['name' => 'Thai', 'locale' => 'th'],
            ['name' => 'Macedonian', 'locale' => 'mk'],
            ['name' => 'Serbian', 'locale' => 'sr'],
            ['name' => 'Bulgarian', 'locale' => 'bg'],
        ];

        foreach ($languages as $language) {
            Language::create($language);
        }

        Schema::table('flutter_channels', function(Blueprint $table) {
            $table->unsignedInteger('language_id')->default(1);
            $table->dropColumn('is_english');
        });

        Schema::table('flutter_channels', function(Blueprint $table) {
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
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
            $table->dropColumn('language_id');
        });

        Schema::drop('languages');
    }
}
