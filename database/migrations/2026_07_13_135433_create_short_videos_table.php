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
        Schema::create('short_videos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('teacher_id');
            $table->unsignedInteger('course_id')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('video_path');
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('likes_count')->default(0);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('short_videos');
    }
};
