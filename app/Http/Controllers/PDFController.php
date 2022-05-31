<?php

namespace App\Http\Controllers;
use App\Models\Equipo;
use App\Models\Prestamo;
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

    public function prestamosPDF( Request $request ){
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $prestamos = Prestamo::join('users','prestamos.user_id','=','users.id')
        ->select('prestamos.id','prestamos.correlativo',
        'prestamos.fecha_reserva',
        'prestamos.fecha_prestamo',
        'prestamos.hora_inicio',
        'prestamos.hora_fin',
        'prestamos.estado',
        'users.name')
        ->where('prestamos.estado','=','D')
        ->whereBetween('fecha_prestamo',[$fecha1,$fecha2])
        ->orderBy('prestamos.id','desc')->get();
        $pdf = \PDF::loadView("admin.PrestamosListPDF", compact('prestamos'));
        return $pdf->stream('prestamos.pdf');

    }
}
