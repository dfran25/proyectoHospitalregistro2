<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VisitanteController;

// Ruta para la página de inicio
Route::get('/', [VisitanteController::class, 'ingreso'])->name('home'); // Esta ruta llama al método ingreso

// Ruta para mostrar el formulario de ingreso de visitantes
Route::get('/visitantes/ingreso', [VisitanteController::class, 'ingreso'])->name('visitantes.ingreso'); // Esta ruta también llama al método ingreso

// Ruta para mostrar el formulario de creación de visitantes
Route::get('/visitantes/create', [VisitanteController::class, 'create'])->name('visitantes.create'); // Esta ruta llama al método create

// Ruta para almacenar el nuevo visitante
Route::post('/visitantes', [VisitanteController::class, 'store'])->name('visitantes.store');

// Ruta para buscar un visitante existente
Route::post('/visitantes/buscar', [VisitanteController::class, 'buscar'])->name('visitantes.buscar');  // Asegúrate de que el método buscar también exista en el controlador

//fabian suarez