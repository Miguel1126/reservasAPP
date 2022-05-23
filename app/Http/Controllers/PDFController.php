<?php

namespace App\Http\Controllers;
use App\Models\Equipo;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function equiposPDF(){
        $equipos = Equipo::join('marcas', 'equipos.marca_id', 'marcas.id')
        ->join('categorias', 'equipos.categoria_id', 'categorias.id')
        ->select(
            'equipos.id',
            'equipos.codigo',
            'equipos.nombre',
            'equipos.descripcion',
            'categorias.nombre as categoria',
            'marcas.nombre as marca',
            'equipos.estado'
        )
        ->orderBy('equipos.id', 'DESC')->get();

        $pdf = \PDF::loadView("admin.EquiposListPDF", compact('equipos'));
        return $pdf->stream('equipos.pdf');
    }
}
