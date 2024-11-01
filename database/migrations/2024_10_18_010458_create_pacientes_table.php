<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id(); // Automatically creates an unsigned big integer 'id' column
            $table->string('documento')->unique(); // Unique document for the patient
            $table->string('nombre'); // Patient's name
            $table->unsignedBigInteger('id_habitacion'); // Foreign key to habitaciones table
            $table->timestamps();

            // Foreign key relationship
            $table->foreign('id_habitacion')->references('id')->on('habitaciones')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
