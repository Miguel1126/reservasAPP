<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    public function especialidad() {
        return $this->belongsTo(Especialidad::class,'especialidad_id');
    }

    public function prestamos() {
        return $this->hasMany(Prestamo::class,'id');
    }
}
