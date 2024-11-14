<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VisitanteController;

// Ruta para la página de inicio (welcome)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Rutas para la gestión de visitantes
Route::get('/visitantes/ingreso', [VisitanteController::class, 'ingreso'])->name('visitantes.ingreso');
Route::get('/visitantes/create', [VisitanteController::class, 'create'])->name('visitantes.create');
Route::post('/visitantes', [VisitanteController::class, 'store'])->name('visitantes.store');
Route::get('/visitantes', [VisitanteController::class, 'index'])->name('visitantes.index');
Route::post('/visitantes/registrar_salida', [VisitanteController::class, 'registrarSalida'])->name('visitantes.registrarSalida');
Route::get('/visitantes/salida_exitosa', [VisitanteController::class, 'salidaExitosa'])->name('visitantes.salida_exitosa');


Route::get('/visitantes/ingreso_exitoso', [VisitanteController::class, 'ingresoExitoso'])->name('visitantes.ingreso_exitoso');
Route::get('/visitantes/hora_salida', [VisitanteController::class, 'mostrarHoraSalida'])->name('visitantes.hora_salida');

Route::post('/visitantes/buscar', [VisitanteController::class, 'buscar'])->name('visitantes.buscar');
Route::post('/visitantes/buscarPorFoto', [VisitanteController::class, 'buscarPorFoto'])->name('visitantes.buscarPorFoto');
Route::post('/visitantes/ingreso', [VisitanteController::class, 'registrarIngreso'])->name('visitantes.registrarIngreso');

Route::post('/visitantes/enviar-foto', [VisitanteController::class, 'enviarFotoAFlask'])->name('visitantes.enviarFoto');
Route::post('/visitantes/guardar-ingreso', [VisitanteController::class, 'guardarIngreso'])->name('visitantes.guardarIngreso');

Route::get('/visitantes/{id}', [VisitanteController::class, 'detalle'])->name('visitantes.detalle');


