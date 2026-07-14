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
        Schema::create('study_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('short_video_id')->nullable();
            $table->string('type'); // 'classroom' or 'short_video'
            $table->timestamp('accessed_at')->useCurrent();
            $table->timestamp('exited_at')->useCurrent();
            $table->integer('duration')->default(0); // in seconds
            $table->timestamps();

            $table->index('user_id');
            $table->index('course_id');
            $table->index('short_video_id');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('study_sessions');
    }
};
