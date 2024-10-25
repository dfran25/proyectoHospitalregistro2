<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitantes', function (Blueprint $table) {
            $table->id(); // Automatically creates an unsigned big integer 'id' column
            $table->string('nombre');
            $table->string('identificacion')->unique(); // Unique identification
            $table->text('foto'); // Path to the visitor's photo
            $table->unsignedBigInteger('habitacion_id'); // Foreign key to habitaciones table
            $table->timestamps();

            // Foreign key relationship
            $table->foreign('habitacion_id')->references('id')->on('habitaciones')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitantes');
    }
};
