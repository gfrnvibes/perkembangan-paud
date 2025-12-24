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
        Schema::create('curriculum_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained();
    $table->integer('semester'); // 1 atau 2
    $table->integer('week_number'); // 1-17
    $table->string('theme');
    $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curriculum_plans');
    }
};
