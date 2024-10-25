<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hora_entrada', function (Blueprint $table) {
            $table->id(); // Automatically creates an unsigned big integer 'id' column
            $table->unsignedBigInteger('id_visitante'); // Foreign key to visitantes table
            $table->unsignedBigInteger('id_habitacion'); // Foreign key to habitaciones table
            $table->date('fecha_entrada'); // Entry date
            $table->time('hora_entrada'); // Entry time
            $table->timestamps();

            // Foreign key relationships
            $table->foreign('id_visitante')->references('id')->on('visitantes')->onDelete('cascade');
            $table->foreign('id_habitacion')->references('id')->on('habitaciones')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hora_entrada');
    }
};
