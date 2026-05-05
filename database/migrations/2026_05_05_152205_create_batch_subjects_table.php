<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batch_subjects', function (Blueprint $table) {
            $table->id();
            $table->string('batch', 100);
            $table->string('name', 100);
            $table->integer('max_te')->default(100);
            $table->integer('max_ce')->default(0);
            $table->integer('pass_mark')->default(35);
            $table->timestamps();
            
            // A subject name must be unique within a batch
            $table->unique(['batch', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batch_subjects');
    }
};
