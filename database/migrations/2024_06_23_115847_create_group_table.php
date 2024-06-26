<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupTable extends Migration
{
    public function up()
    {
        Schema::create('group', function (Blueprint $table) {
            $table->string('GRP_CODE', 8);
            $table->string('GRP_ABNAME', 20)->nullable();
            $table->string('GRP_ADVIS', 80)->nullable();
            $table->string('GRP_FIELD', 4)->nullable();
            $table->string('GRP_MEET', 100)->nullable();
            $table->integer('GRP_CLASS')->nullable();
            $table->string('GRP_LEARN', 1)->nullable();
            $table->integer('GRP_NUM')->nullable();
            $table->integer('GRP_SIZE')->nullable();
            $table->string('TAMBONID', 6)->nullable();
            $table->string('AMPHURID', 4)->nullable();
            $table->string('PROVINCE', 2)->nullable();
            $table->boolean('COUPON')->nullable();
            $table->string('IGRP_CODE', 8)->nullable();
            $table->timestamps(); // This will add both `created_at` and `updated_at` columns
        });
    }

    public function down()
    {
        Schema::dropIfExists('group');
    }
}