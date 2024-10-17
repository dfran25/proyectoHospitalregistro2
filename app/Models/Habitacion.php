<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habitacion extends Model
{
    use HasFactory;

    // Especificar el nombre de la tabla si no sigue la convención por defecto
    protected $table = 'habitaciones'; // Correcto, para hacer referencia a la tabla correcta

    protected $fillable = ['numero_habitacion'];

    // Relación con Visitante (una habitación tiene muchos visitantes)
    public function visitantes()
    {
        return $this->hasMany(Visitante::class);
    }
}
