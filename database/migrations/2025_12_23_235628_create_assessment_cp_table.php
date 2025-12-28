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
        Schema::create('assessment_cp', function (Blueprint $table) {
            $table->foreignId('cp_element_id')->constrained();
            $table->morphs('assessable'); // Menciptakan assessable_id dan assessable_type
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_cp');
    }
};
