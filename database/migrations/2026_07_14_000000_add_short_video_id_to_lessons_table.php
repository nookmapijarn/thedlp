<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('lessons')) {
            Schema::table('lessons', function (Blueprint $table) {
                if (!Schema::hasColumn('lessons', 'short_video_id')) {
                    $table->unsignedBigInteger('short_video_id')->nullable()->after('quiz_id');
                    $table->foreign('short_video_id')->references('id')->on('short_videos')->onDelete('set null');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('lessons')) {
            Schema::table('lessons', function (Blueprint $table) {
                if (Schema::hasColumn('lessons', 'short_video_id')) {
                    $table->dropForeign(['short_video_id']);
                    $table->dropColumn('short_video_id');
                }
            });
        }
    }
};
