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
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->string('batch')->nullable();
            $table->string('reg_no')->index();
            $table->string('name')->nullable();
            $table->json('marks_data')->nullable();
            $table->string('total_marks')->nullable();
            $table->string('total_obt_marks')->nullable();
            $table->string('daiya_rank')->nullable();
            $table->string('college_rank')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};
