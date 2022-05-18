<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    //Relacion de 1:N con equipos
    public function equipos() {
        //return $this->hasMany('App\Models\Equipo');
        return $this->hasMany(Equipo::class,'id');
    }

}
