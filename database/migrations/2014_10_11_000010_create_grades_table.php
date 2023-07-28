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
            $table->unsignedBigInteger('practica_preprofesional_id');
            $table->unsignedBigInteger('evaluador_id');
            $table->timestamps();

            $table->foreign('practica_preprofesional_id')->references('id')->on('pre_professional_practices')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('evaluador_id')->references('id')->on('career_directors')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
