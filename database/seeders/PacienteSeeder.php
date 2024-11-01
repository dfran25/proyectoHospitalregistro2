<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PacienteSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pacientes')->insert([
            ['Documento' => '123456789', 'Nombre' => 'Juan Pérez', 'id_habitacion' => 1],
            ['Documento' => '987654321', 'Nombre' => 'Ana García', 'id_habitacion' => 2],
            ['Documento' => '112233445', 'Nombre' => 'Luis Martínez', 'id_habitacion' => 3],
            // Agrega más pacientes según lo necesites
        ]);
    }
}
