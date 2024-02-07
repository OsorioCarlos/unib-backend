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
            $table->unsignedBigInteger('student_id')->unique();
            $table->unsignedBigInteger('organization_id');
            $table->boolean('estudiante_carta_compromiso')->nullable();
            $table->dateTime('estudiante_carta_compromiso_fecha')->nullable();
            $table->boolean('estudiante_compromiso')->nullable();
            $table->dateTime('estudiante_compromiso_fecha')->nullable();
            $table->unsignedBigInteger('estado_id')->default(3);
            $table->string('area_practicas_solicitadas')->nullable();
            $table->integer('horas_practicas_solicitadas')->nullable();
            $table->integer('horas_practicas_realizadas')->nullable();
            $table->string('cumplimiento_objetivos')->nullable();
            $table->string('beneficios')->nullable();
            $table->string('aprendizajes')->nullable();
            $table->string('desarrollo_personal')->nullable();
            $table->string('comentarios')->nullable();
            $table->string('recomendaciones')->nullable();
            $table->string('fecha_informe_enviado')->nullable();
            $table->unsignedBigInteger('career_director_id')->nullable();
            $table->unsignedBigInteger('internship_representative_id')->nullable();
            $table->boolean('empresa_compromiso')->nullable();
            $table->dateTime('empresa_compromiso_fecha')->nullable();
            $table->string('area_practicas')->nullable();
            $table->string('objetivos_practicas')->nullable();
            $table->string('tareas')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->string('dias_laborables')->nullable();
            $table->string('horario')->nullable();
            $table->float('asistencia')->nullable();
            $table->float('nota_final')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('organization_id')->references('id')->on('organizations')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('internship_representative_id')->references('id')->on('internship_representatives')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('career_director_id')->references('id')->on('career_directors')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('estado_id')->references('id')->on('catalogues')
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
