
<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCampaigns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flutter_apps', function(Blueprint $table) {
            $table->dropColumn('campaign_question1');
            $table->dropColumn('campaign_question2');

            $table->integer('campaign_content_score')->nullable();
            $table->integer('campaign_video_score')->nullable();
            $table->integer('campaign_support_score')->nullable();
            $table->boolean('campaign_subscribe')->nullable();
            $table->text('campaign_comments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('flutter_apps', function(Blueprint $table) {
            $table->dropColumn('campaign_content_score');
            $table->dropColumn('campaign_video_score');
            $table->dropColumn('campaign_support_score');
            $table->dropColumn('campaign_subscribe');
            $table->dropColumn('campaign_comments');

            $table->text('campaign_question1')->nullable();
            $table->text('campaign_question2')->nullable();
        });
    }
}
