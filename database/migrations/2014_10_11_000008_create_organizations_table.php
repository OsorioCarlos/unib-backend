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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('ruc');
            $table->string('razon_social');
            $table->string('representante_legal');
            $table->string('direccion');
            $table->string('telefono');
            $table->string('email');
            $table->string('area_dedicacion');
            $table->string('horario');
            $table->string('dias_laborables');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
