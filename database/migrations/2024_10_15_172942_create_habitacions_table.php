<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('habitaciones', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('numero_habitacion')->unique(); // Unique room number
            $table->integer('capacidad')->default(1); // Room capacity, default is 1
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('habitaciones');
    }
};
