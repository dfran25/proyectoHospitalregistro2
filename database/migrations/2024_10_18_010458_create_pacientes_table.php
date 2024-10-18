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
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id('id_paciente'); // ID del paciente
            $table->string('documento')->unique(); // Documento único del paciente
            $table->string('nombre'); // Nombre del paciente
            $table->unsignedBigInteger('id_habitacion'); // Llave foránea hacia la tabla habitaciones
            $table->timestamps();

            // Relación con la tabla habitaciones
            $table->foreign('id_habitacion')->references('id_habitacion')->on('habitaciones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
