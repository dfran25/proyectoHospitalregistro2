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
        Schema::create('visitantes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('identificacion')->unique();
            $table->text('foto'); // Ruta de la imagen del visitante
            $table->unsignedBigInteger('habitacion_id'); // Llave foránea a la tabla habitaciones
            $table->timestamps();

            // Relación con la tabla habitaciones
            $table->foreign('habitacion_id')->references('id')->on('habitaciones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitantes');
    }
};
