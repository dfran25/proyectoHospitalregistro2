<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habitacion extends Model
{
    use HasFactory;

    protected $fillable = ['numero_habitacion'];

    public function visitantes()
    {
        return $this->hasMany(Visitante::class);
    }
}
