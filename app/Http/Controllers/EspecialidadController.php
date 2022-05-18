<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class EspecialidadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.especialidades');
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
            $espcd = new Especialidad();
            $espcd->nombre = $request->nombre;
            if ($espcd->save()>=1) {
                return response()->json(['status'=>'ok', 'data'=>$espcd],201);
            }
        } 
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        try {
            $espcd = Especialidad::orderBy('id','desc')->get();
            return $espcd;    
        } 
        catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        try {
            $espcd = Especialidad::findOrFail($request->id);
            $espcd->nombre = $request->nombre;
            if ($espcd->save()>=1) {
                return response()->json(['status'=>'ok', 'data'=>$espcd],202);
            }
        } 
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            $espcd = Especialidad::findOrFail($request->id);
            $espcd->delete();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
