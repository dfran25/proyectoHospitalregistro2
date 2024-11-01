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
Route::get('/visitantes/{id}', [VisitanteController::class, 'detalle'])->name('visitantes.detalle');
Route::post('/visitantes/buscar', [VisitanteController::class, 'buscar'])->name('visitantes.buscar');
Route::post('/visitantes/buscarPorFoto', [VisitanteController::class, 'buscarPorFoto'])->name('visitantes.buscarPorFoto');
Route::post('/visitantes/ingreso', [VisitanteController::class, 'registrarIngreso'])->name('visitantes.registrarIngreso');
