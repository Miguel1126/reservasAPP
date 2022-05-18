<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;

    //Por la relacion inversa con categoria
    public function categoria() {
        return $this->belongsTo(Categoria::class,'categoria_id');
    }

    //Por la relacion inversa con marca
    public function marca() {
        return $this->belongsTo(Marca::class,'marca_id');
    }

    //relacion de 1:N con imagenes
    public function imagenes() {
        return $this->hasMany(Imagen::class,'id');
    }

    //relacion de 1:N con detalleprestamos
    public function detalle_prestamos() {
        return $this->hasMany(DetallePrestamo::class,'id');
    }
}
