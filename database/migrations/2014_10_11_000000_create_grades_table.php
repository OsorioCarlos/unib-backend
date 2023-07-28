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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->float('promedio');
            $table->unsignedBigInteger('pre_profesional_practice_id');
            $table->unsignedBigInteger('evaluator_id');
            $table->timestamps();

            $table->foreign('pre_profesional_practice_id')->references('id')->on('pre_profesional_practices')->onDelete('cascade');
            $table->foreign('evaluator_id')->references('id')->on('career_directors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
