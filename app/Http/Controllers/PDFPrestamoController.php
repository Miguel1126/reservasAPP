<?php

namespace App\Http\Controllers;
use App\Models\Prestamo;
use Illuminate\Http\Request;

class PDFPrestamoController extends Controller
{
    public function prestamosPDF(){
        $prestamos = Prestamo::join('users','prestamos.user_id','=','users.id')
        ->select('prestamos.id','prestamos.correlativo','prestamos.fecha_reserva','prestamos.fecha_prestamo','prestamos.hora_inicio','prestamos.hora_fin','prestamos.estado','users.name')
        ->orderBy('prestamos.id','desc')->get();
        $pdf = \PDF::loadView("admin.PrestamosListPDF", compact('prestamos'));
        return $pdf->stream('prestamos.pdf');

    }
        
  
}
