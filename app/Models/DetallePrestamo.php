<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePrestamo extends Model
{
    protected $table = "detalle_prestamos";
    use HasFactory;

    public function prestamo() {
        return $this->belongsTo(Prestamo::class,'prestamo_id');
    }

    public function equipo() {
        return $this->belongsTo(Equipo::class,'equipo_id');
    }
}
