<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrade2Table extends Migration
{
    public function up()
    {
        Schema::create('grade2', function (Blueprint $table) {
            $table->string('STD_CODE', 20)->nullable();
            $table->string('SEMESTRY', 4)->nullable();
            $table->string('SUB_CODE', 9)->nullable();
            $table->string('METHOD', 1)->nullable();
            $table->string('LEARNING', 1)->nullable();
            $table->integer('TYP_CODE')->nullable();
            $table->integer('MIDTERM')->nullable();
            $table->integer('FINAL')->nullable();
            $table->integer('TOTAL')->nullable();
            $table->string('GRADE', 3)->nullable();
            $table->string('FLD_CODE', 4)->nullable();
            $table->string('ROOMNO', 3)->nullable();
            $table->string('GRP_CODE', 8)->nullable();
            $table->integer('MIDTERM1')->nullable();
            $table->integer('MIDTERM2')->nullable();
            $table->integer('MIDTERM3')->nullable();
            $table->integer('MIDTERM4')->nullable();
            $table->integer('MIDTERM5')->nullable();
            $table->integer('MIDTERM6')->nullable();
            $table->integer('MIDTERM7')->nullable();
            $table->integer('MIDTERM8')->nullable();
            $table->integer('MIDTERM9')->nullable();
            $table->integer('FINAL1')->nullable();
            $table->integer('FINAL2')->nullable();
            $table->string('BOOK', 1)->nullable();
            $table->string('DT_ADDMID')->nullable();
            $table->string('DT_UPDMID')->nullable();
            $table->string('USERNAME', 12)->nullable();
            $table->string('_NULLFLAGS')->nullable();
            $table->timestamps(); // This will add both `created_at` and `updated_at` columns
        });
    }

    public function down()
    {
        Schema::dropIfExists('grade2');
    }
}