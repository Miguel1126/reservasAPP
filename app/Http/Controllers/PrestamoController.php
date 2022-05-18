<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Prestamo;
use App\Models\DetallePrestamo;
use Error;

class PrestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.prestamos');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $errors = 0;
            DB::beginTransaction();
            //Creando y llenando el objeto prestamo
            $prestamo = new Prestamo();            
            $prestamo->correlativo = $this->getCorrelativo();
            $prestamo->fecha_reserva = $request->fechaReserva;
            $prestamo->fecha_prestamo = $request->fechaPrestamo;
            $prestamo->hora_inicio = $request->horaInicio;
            $prestamo->hora_fin = $request->horaFin;
            $prestamo->estado = "R";
            $prestamo->user_id = $request->user['id'];
            if ($prestamo->save()<=0) {
                $errors++;
            }
            //Obtenemos el detalle del prestamo que viene del frontend
            $detalle = $request->equipos;
            //Recorriendo los elementos para guardar en la tabla detalle_prestamos
            foreach ($detalle as $key => $det) {
                //Creamos el objeto de tipo DetallePrestamo
                $detPrestamo = new DetallePrestamo();
                $detPrestamo->equipo_id = $det['equipo']['id'];
                $detPrestamo->prestamo_id = $prestamo->id;
                //guardamos en la tabla detalle_prestamos
                if ($detPrestamo->save()<=0) {
                    $errors++;
                }
            }
            if ($errors == 0) {
                DB::commit();
                return response()->json(['status'=>'ok','data'=>$prestamo],201);
            }
            else {
                DB::rollBack();
                return response()->json(['status'=>'fail','data'=>$prestamo],209);
            }
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    //Metodo para cambiar el estado del prestamo
    public function changeState(Request $request) {
        try {
            $prestamo = Prestamo::findOrFail($request->id);
            $prestamo->estado = $request->estado;
            $prestamo->save();
        }
        catch (\Exception $e) {
            $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $state = $request->state;   
        $prestamos = Prestamo::join('users','prestamos.user_id','=','users.id')
            ->select('prestamos.id','prestamos.correlativo','prestamos.fecha_reserva','prestamos.fecha_prestamo','prestamos.hora_inicio','prestamos.hora_fin','prestamos.estado','users.name')
            ->where('prestamos.estado','=',$state)
            ->orderBy('prestamos.id','desc')->get();
        return $prestamos;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
    }

    //Metodo para generear el correlativo de prestamo
    public function getCorrelativo() {
        $result = DB::select("SELECT CONCAT(TRIM(YEAR(CURDATE())),LPAD(TRIM(MONTH(CURDATE())),2,0),LPAD(IFNULL(MAX(TRIM(SUBSTRING(correlativo,7,4))),0)+1,4,0)) as correlativo FROM prestamos WHERE SUBSTRING(correlativo,1,6) = CONCAT(TRIM(YEAR(CURDATE())),LPAD(TRIM(MONTH(CURDATE())),2,0))");
        return $result[0]->correlativo;
    }
}
