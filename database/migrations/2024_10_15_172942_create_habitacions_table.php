<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('habitaciones', function (Blueprint $table) {
            $table->id(); // Automatically creates an unsigned big integer 'id' column
            $table->string('numero_habitacion')->unique(); // Unique room number
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('habitaciones');
    }
};
