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
        Schema::table('short_videos', function (Blueprint $table) {
            $table->string('type')->default('video')->after('course_id');
            $table->json('images')->nullable()->after('video_path');
        });

        // Make video_path nullable using raw SQL statement
        Illuminate\Support\Facades\DB::statement('ALTER TABLE short_videos MODIFY video_path VARCHAR(255) NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('short_videos', function (Blueprint $table) {
            $table->dropColumn(['type', 'images']);
        });

        Illuminate\Support\Facades\DB::statement('ALTER TABLE short_videos MODIFY video_path VARCHAR(255) NOT NULL;');
    }
};
