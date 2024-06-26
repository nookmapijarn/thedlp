<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivity2Table extends Migration
{
    public function up()
    {
        Schema::create('activity2', function (Blueprint $table) {
            $table->string('STD_CODE', 20);
            $table->string('ACTIVITY', 80)->nullable();
            $table->string('SEMESTRY', 4)->nullable();
            $table->integer('HOUR')->nullable();
            $table->integer('TRANSFER')->nullable();
            $table->integer('TRNTYPE')->nullable();
            $table->timestamps(); // This will add both `created_at` and `updated_at` columns
        });        
    }

    public function down()
    {
        Schema::dropIfExists('activity2');
    }
}