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
        Schema::create('pre_professional_practices', function (Blueprint $table) {
            $table->id();
            $table->integer('numero_horas_practica')->nullable();
            $table->boolean('estudiante_compromiso')->nullable();
            $table->dateTime('estudiante_compromiso_fecha')->nullable();
            $table->string('objetivos_practica')->nullable();
            $table->string('tareas')->nullable();
            $table->string('horario')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_finalizacion')->nullable();
            $table->boolean('empresa_compromiso')->nullable();
            $table->dateTime('empresa_compromiso_fecha')->nullable();
            $table->string('area_practicas')->nullable();
            $table->float('nota_final')->nullable();
            $table->unsignedBigInteger('estudiante_id');
            $table->morphs('evaluador');
            $table->timestamps();

            $table->foreign('estudiante_id')->references('id')->on('students')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_professional_practices');
    }
};
