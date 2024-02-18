<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pre_professional_practice_id');
            $table->unsignedBigInteger('user_id');
            $table->float('nota_promedio');
            $table->float('porcentaje_asistencia');
            $table->text('observaciones')->nullable();
            $table->text('recomendaciones')->nullable();
            $table->timestamps();

            $table->foreign('pre_professional_practice_id')->references('id')->on('pre_professional_practices')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('user_id')->references('id')->on('users')
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
