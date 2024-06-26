<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedule2Table extends Migration
{
    public function up()
    {
        Schema::create('schedule2', function (Blueprint $table) {
            $table->string('SEMESTRY', 4);
            $table->string('LEARNING', 1)->nullable();
            $table->string('SUB_CODE', 9)->nullable();
            $table->string('EXAM_DAY', 8)->nullable();
            $table->string('FLD_CODE', 4)->nullable();
            $table->integer('EXAM_START')->nullable();
            $table->integer('EXAM_END')->nullable();
            $table->timestamps(); // This will add both `created_at` and `updated_at` columns
        });
    }

    public function down()
    {
        Schema::dropIfExists('schedule2');
    }
}