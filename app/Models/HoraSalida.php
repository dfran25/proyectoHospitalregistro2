<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoraSalida extends Model
{
    use HasFactory;

    protected $fillable = ['id_visitante', 'id_habitacion', 'fecha_salida', 'hora_salida'];

    /**
     * Relación con el modelo Visitante
     */
    public function visitante()
    {
        return $this->belongsTo(Visitante::class, 'id_visitante');
    }

    /**
     * Relación con el modelo Habitacion
     */
    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class, 'id_habitacion');
    }
}
