<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubject1Table extends Migration
{
    public function up()
    {
        Schema::create('subject1', function (Blueprint $table) {
            $table->string('SUB_CODE', 9);
            $table->string('ESUB_CODE', 9)->nullable();
            $table->string('SUB_NAME', 80)->nullable();
            $table->string('ESUB_NAME', 100)->nullable();
            $table->integer('SUB_TYPE')->nullable();
            $table->integer('ELECT_TYPE')->nullable();
            $table->integer('SUB_CREDIT')->nullable();
            $table->string('PREREQ', 9)->nullable();
            $table->integer('WORDSEP')->nullable();
            $table->integer('EWORDSEP')->nullable();
            $table->string('CANCEL_SEM', 4)->nullable();
            $table->integer('NUMCHOICE')->nullable();
            $table->boolean('DISABLE')->nullable();
            $table->string('SARAGRP', 1)->nullable();
            $table->integer('COURSE')->nullable();
            $table->integer('LEVEL')->nullable();
            $table->integer('MIDTERM')->nullable();
            $table->integer('FINAL')->nullable();
            $table->integer('MINFIN')->nullable();
            $table->integer('RMIDTERM')->nullable();
            $table->integer('RFINAL')->nullable();
            $table->integer('RMINFIN')->nullable();
            $table->integer('SUB_ID')->nullable();
            $table->boolean('DEFMIDFIN')->nullable();
            $table->boolean('DEFRMIDFIN')->nullable();
            $table->boolean('DEFMINFIN')->nullable();
            $table->boolean('DEFRMINFIN')->nullable();
            $table->timestamps(); // This will add both `created_at` and `updated_at` columns
        });
    }

    public function down()
    {
        Schema::dropIfExists('subject1');
    }
}