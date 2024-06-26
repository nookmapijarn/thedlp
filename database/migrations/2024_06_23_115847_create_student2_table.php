<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudent2Table extends Migration
{
    public function up()
    {
        Schema::create('student2', function (Blueprint $table) {
            $table->string('ID', 10);
            $table->string('SCHOOL', 10)->nullable();
            $table->string('STD_CODE', 20);
            $table->string('PRENAMEID', 3)->nullable();
            $table->string('PRENAME', 35)->nullable();
            $table->string('NAME', 50)->nullable();
            $table->string('MIDNAME', 50)->nullable();
            $table->string('SURNAME', 50)->nullable();
            $table->string('GRP_CODE', 8)->nullable();
            $table->string('GENDER', 1)->nullable();
            $table->integer('AGE')->nullable();
            $table->string('BIRDAY', 10)->nullable();
            $table->string('RELIGION', 2)->nullable();
            $table->string('OCCP', 2)->nullable();
            $table->string('OCCTYP', 2)->nullable();
            $table->string('CRIPPLEID', 2)->nullable();
            $table->integer('TALL')->nullable();
            $table->integer('WEIGHT')->nullable();
            $table->integer('BROTHER')->nullable();
            $table->integer('STUDYBRO')->nullable();
            $table->string('FA_PRENAME', 35)->nullable();
            $table->string('FA_NAME', 20)->nullable();
            $table->string('FA_SURNAME', 30)->nullable();
            $table->string('FA_CRIPPLE', 2)->nullable();
            $table->string('FA_STATUS', 2)->nullable();
            $table->integer('FA_SALARY')->nullable();
            $table->string('FA_OCCP', 2)->nullable();
            $table->string('MO_PRENAME', 35)->nullable();
            $table->string('MO_NAME', 20)->nullable();
            $table->string('MO_SURNAME', 30)->nullable();
            $table->string('MO_CRIPPLE', 2)->nullable();
            $table->string('MO_STATUS', 2)->nullable();
            $table->integer('MO_SALARY')->nullable();
            $table->string('MO_OCCP', 2)->nullable();
            $table->string('MARRYSTAT', 1)->nullable();
            $table->string('PA_PRENAME', 35)->nullable();
            $table->string('PA_NAME', 20)->nullable();
            $table->string('PA_SURNAME', 30)->nullable();
            $table->integer('PA_SALARY')->nullable();
            $table->string('PA_OCCP', 2)->nullable();
            $table->string('EXPERTID', 3)->nullable();
            $table->text('EXPERT')->nullable();
            $table->string('METHOD', 1)->nullable();
            $table->string('S_GRAD', 2)->nullable();
            $table->string('S_YEAR', 4)->nullable();
            $table->string('S_SCHOOL', 100)->nullable();
            $table->string('S_PROVINCE', 20)->nullable();
            $table->string('T_GRAD', 1)->nullable();
            $table->string('T_YEAR', 4)->nullable();
            $table->text('T_SCHOOL')->nullable();
            $table->string('T_PROVINCE', 15)->nullable();
            $table->string('HOUSEID', 11)->nullable();
            $table->text('ADDR')->nullable();
            $table->string('TAMBONID', 6)->nullable();
            $table->string('ZIPCODE', 5)->nullable();
            $table->text('CURADDR')->nullable();
            $table->string('CTAMBONID', 6)->nullable();
            $table->string('CZIPCODE', 5)->nullable();
            $table->string('DEP_SEM', 4)->nullable();
            $table->date('APP_DATE')->nullable();
            $table->date('FIN_DATE')->nullable();
            $table->date('TRSCP_DATE')->nullable();
            $table->string('FIN_SEM', 4)->nullable();
            $table->integer('FIN_CAUSE')->nullable();
            $table->date('FIN_DATE2')->nullable();
            $table->date('TRN_DATE2')->nullable();
            $table->string('FIN_SEM2', 4)->nullable();
            $table->integer('CFIN_CAUSE')->nullable();
            $table->string('ACT_SEM', 4)->nullable();
            $table->integer('ACT_HOUR')->nullable();
            $table->string('NATION', 3)->nullable();
            $table->boolean('CARD')->nullable();
            $table->string('TRNGRP', 6)->nullable();
            $table->string('TRNRUN', 8)->nullable();
            $table->string('CERTNUM', 13)->nullable();
            $table->string('CARDID', 13)->nullable();
            $table->integer('BUDGETTYP')->nullable();
            $table->text('PHONE')->nullable();
            $table->text('CURPHONE')->nullable();
            $table->text('EMAIL')->nullable();
            $table->integer('ABLEVEL1')->nullable();
            $table->integer('EXPFLAG')->nullable();
            $table->string('EXPSEM', 4)->nullable();
            $table->integer('ABLEVEL2')->nullable();
            $table->string('LASTUPDATE')->nullable();
            $table->string('INSERTDATE')->nullable();
            $table->string('NT_SARA1', 5)->nullable();
            $table->string('NT_SARA2', 5)->nullable();
            $table->string('NT_SEM', 4)->nullable();
            $table->string('NT_NOSEM', 4)->nullable();
            $table->text('PINIDREM')->nullable();
            $table->text('PICFILE')->nullable();
            $table->boolean('COUPON')->nullable();
            $table->string('GPASEM', 4)->nullable();
            $table->integer('GPATYPE')->nullable();
            $table->string('ABLEV1_SEM', 4)->nullable();
            $table->integer('APPLTYPE')->nullable();
            $table->string('V_STATUS', 1)->nullable();
            $table->date('V_SENDDATE')->nullable();
            $table->date('V_RECVDATE')->nullable();
            $table->string('V_RECVDOC', 30)->nullable();
            $table->string('V_FOLDER', 10)->nullable();
            $table->text('V_SCHOOL')->nullable();
            $table->string('V_SCHSTAT', 1)->nullable();
            $table->date('V_REQDATE')->nullable();
            $table->string('V_REQDOC', 30)->nullable();
            $table->date('V_REPDATE')->nullable();
            $table->string('V_RETDOC', 30)->nullable();
            $table->date('V_RETDATE')->nullable();
            $table->string('SUBTYPE', 2)->nullable();
            $table->string('SUBFIELD', 4)->nullable();
            $table->string('BRANCH', 6)->nullable();
            $table->string('LEARNING', 1)->nullable();
            $table->text('DUALSCHOOL')->nullable();
            $table->string('TRNSERIES', 2)->nullable();
            $table->string('DISADV', 2)->nullable();
            $table->timestamps(); // This will add both `created_at` and `updated_at` columns
        });
    }

    public function down()
    {
        Schema::dropIfExists('student2');
    }
}