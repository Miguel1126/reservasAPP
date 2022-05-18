<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\PrestamoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Retorno de una vista
Route::get('/', function () {
    return view('welcome');
});

/**
 * Rutas para categorias
 */

Route::get('/categorias',[CategoriaController::class,'index'])->name("categorias");

Route::get('/categorias/all',[CategoriaController::class,'show']);

Route::post('/categorias/save',[CategoriaController::class,'store']);

Route::put('/categorias/update',[CategoriaController::class,'update']);

Route::post('/categorias/delete',[CategoriaController::class,'destroy']);



/**
 * Rutas para prestamos
 */

Route::get('/prestamos',[PrestamoController::class,'index'])->name("reservas");

Route::get('/prestamos/state',[PrestamoController::class,'show']);

Route::post('/prestamos/save',[PrestamoController::class,'store']);

Route::put('/prestamos/change',[PrestamoController::class,'changeState']);

/**
 * Rutas para marcas
 */

Route::get('/marcas',[MarcaController::class,'index'])->name("marcas");

Route::get('/marcas/all',[MarcaController::class,'show']);

Route::post('/marcas/save',[MarcaController::class,'store']);

Route::put('/marcas/update',[MarcaController::class,'update']);

Route::post('/marcas/delete',[MarcaController::class,'destroy']);

/**
 * Rutas para equipos
 */

Route::get('/equipos',[EquipoController::class,'index'])->name("equipos");

Route::get('/equipos/all',[EquipoController::class,'show']);

Route::post('/equipos/save',[EquipoController::class,'store']);

Route::put('/equipos/update',[EquipoController::class,'update']);

Route::post('/equipos/delete',[EquipoController::class,'destroy']);

/**
 * Rutas para especialidades
 */

Route::get('/especialidades',[EspecialidadController::class,'index'])->name("especialidades");

Route::get('/especialidades/all',[EspecialidadController::class,'show']);

Route::post('/especialidades/save',[EspecialidadController::class,'store']);

Route::put('/especialidades/update',[EspecialidadController::class,'update']);

Route::post('/especialidades/delete',[EspecialidadController::class,'destroy']);






Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
