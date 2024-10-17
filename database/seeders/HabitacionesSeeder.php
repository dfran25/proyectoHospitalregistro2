<?php

namespace Database\Seeders;

use App\Models\Habitacion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HabitacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Habitacion::create(['numero_habitacion' => '101']);
        Habitacion::create(['numero_habitacion' => '102']);
        Habitacion::create(['numero_habitacion' => '103']);
    }
}
