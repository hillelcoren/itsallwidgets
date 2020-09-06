<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRussian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $languages = [
            ['name' => 'Russian', 'locale' => 'ru'],
            ['name' => 'Vietnamese', 'locale' => 'vi'],
        ];

        foreach ($languages as $language) {
            Language::create($language);
        }
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
