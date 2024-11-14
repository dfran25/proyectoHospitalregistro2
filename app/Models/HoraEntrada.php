<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoraEntrada extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'hora_entrada';

    // Campos que se pueden asignar masivamente
    protected $fillable = ['id_visitante', 'id_habitacion', 'fecha_entrada', 'hora_entrada'];

    // Relación con el modelo Visitante
    public function visitante()
    {
        return $this->belongsTo(Visitante::class, 'id_visitante');
    }

    // Relación con el modelo Habitacion
    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class, 'id_habitacion');
    }
}
