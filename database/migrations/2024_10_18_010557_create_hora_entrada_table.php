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
        Schema::create('hora_entrada', function (Blueprint $table) {
            $table->id('id_horaentrada'); // ID de la entrada
            $table->unsignedBigInteger('id_visitante'); // Llave foránea hacia la tabla de visitantes
            $table->unsignedBigInteger('id_habitacion'); // Llave foránea hacia la tabla de habitaciones
            $table->date('fecha_entrada'); // Fecha de entrada
            $table->time('hora_entrada'); // Hora de entrada
            $table->timestamps();

            // Relación con la tabla visitantes
            $table->foreign('id_visitante')->references('id_visitante')->on('visitantes')->onDelete('cascade');
            // Relación con la tabla habitaciones
            $table->foreign('id_habitacion')->references('id_habitacion')->on('habitaciones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hora_entrada');
    }
};

