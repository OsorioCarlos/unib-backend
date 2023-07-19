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
            $table->integer('numero_horas_practica');
            $table->boolean('estudiante_compromiso');
            $table->dateTime('estudiante_compromiso_fecha');
            $table->string('objetivos_practica');
            $table->string('tareas');
            $table->string('horario');
            $table->date('fecha_inicio');
            $table->date('fecha_finalizacion');
            $table->boolean('empresa_compromiso');
            $table->dateTime('empresa_compromiso_fecha');
            $table->string('area_practicas');
            $table->float('nota_final');
            $table->unsignedBigInteger('student_id');
            $table->timestamps();
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
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
