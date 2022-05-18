<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;


class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.categorias');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            $categoria = new Categoria();
            $categoria->nombre = $request->nombre;
            $categoria->save();
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
     * Actualizar categorias
     */
    public function update(Request $request)
    {
        try {
            $categoria = Categoria::findORFail($request->id);
            $categoria->nombre = $request->nombre;
            $categoria->save();
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
            $categoria = Categoria::findORFail($request->id);
            $categoria->delete();
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    public function show()
    {
        try {
            $categorias = Categoria::orderBy('id','DESC')->get();
            return $categorias;
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }
}
