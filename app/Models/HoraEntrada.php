<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoraEntrada extends Model
{
    use HasFactory;

    protected $fillable = ['id_visitante', 'id_habitacion', 'fecha_entrada', 'hora_entrada'];

    public function visitante()
    {
        return $this->belongsTo(Visitante::class, 'id_visitante');
    }

    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class, 'id_habitacion');
    }
}
