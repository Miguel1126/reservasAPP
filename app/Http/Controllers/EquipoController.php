<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;

class EquipoController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.equipos');
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
            $equipo = new Equipo();
            $equipo->codigo = $request->codigo;
            $equipo->nombre = $request->nombre;
            $equipo->descripcion = $request->descripcion;
            $equipo->estado = $request->estado;
            $equipo->categoria_id = $request->categoria_id;
            $equipo->marca_id = $request->marca_id;
            if($equipo->save()>=1) {
                return response()->json(['status'=>'ok', 'data'=>$equipo],201);
            }
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     * Actualizar Equipos
     */
    public function update(Request $request)
    {
        try {
            $equipo = Equipo::findOrFail($request->id);
            $equipo->codigo = $request->codigo;
            $equipo->nombre = $request->nombre;
            $equipo->descripcion = $request->descripcion;
            //$equipo->estado = $request->estado;
            $equipo->categoria_id = $request->categoria_id;
            $equipo->marca_id = $request->marca_id;
            if ($equipo->save()>=1) {
                return response()->json(['status'=>'ok', 'data'=>$equipo],202);
            }
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\
     * 
     * Eliminar cateogrias
     */
    public function destroy(Request $request)
    {
        try {
            $equipo = Equipo::findORFail($request->id);
            $equipo->delete();
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    public function show()
    {
        try {
            $equipos = Equipo::join('categorias','equipos.categoria_id','=','categorias.id')
            ->join('marcas','equipos.marca_id','=','marcas.id')
            ->select('equipos.id','equipos.codigo','equipos.nombre','equipos.descripcion','equipos.estado','equipos.categoria_id','categorias.nombre as categoria','equipos.marca_id','marcas.nombre as marca')
            ->orderBy('equipos.id','DESC')->get();
            return $equipos;
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }
}
