<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitante extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'identificacion', 'foto', 'habitacion_id'];

    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class);
    }
}

