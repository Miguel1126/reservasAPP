<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.marcas');
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
            $marca = new Marca();
            $marca->nombre = $request->nombre;
            $marca->save();
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
     * Actualizar Marcas
     */
    public function update(Request $request)
    {
        try {
            $marca = Marca::findORFail($request->id);
            $marca->nombre = $request->nombre;
            $marca->save();
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
            $marca = Marca::findORFail($request->id);
            $marca->delete();
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    public function show()
    {
        try {
            $marcas = Marca::orderBy('id','ASC')->get();
            return $marcas;
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }
}
