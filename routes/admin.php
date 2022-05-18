<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;

//ruta para cargar la index de admin
Route::get('',[HomeController::class,'index']);