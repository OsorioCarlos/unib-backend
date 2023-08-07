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
        Schema::create('grading_criterias', function (Blueprint $table) {
            $table->id();
            $table->float('calificacion');
            $table->unsignedBigInteger('calificacion_id');
            $table->unsignedBigInteger('criterio_id');
            $table->timestamps();

            $table->foreign('calificacion_id')->references('id')->on('grades')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('criterio_id')->references('id')->on('catalogues')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grading_criteria');
    }
};
