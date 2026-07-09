<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('help_request_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('help_request_id');
            $table->unsignedBigInteger('user_id');
            $table->string('status');
            $table->string('action_detail');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help_request_logs');
    }
};
