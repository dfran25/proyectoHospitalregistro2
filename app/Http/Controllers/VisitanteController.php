<?php

namespace App\Http\Controllers;

use App\Models\Visitante;
use App\Models\Habitacion;
use Illuminate\Http\Request;

class VisitanteController extends Controller
{
    // Método para mostrar el formulario de ingreso de visitantes
    public function ingreso()
    {
        return view('visitantes.ingreso'); // Asegúrate de que esta vista exista
    }

    // Método para mostrar el formulario de creación de visitantes
    public function create()
    {
        // Obtener todas las habitaciones
        $habitaciones = Habitacion::all();

        return view('visitantes.create', compact('habitaciones'));
    }

    // Método para almacenar el nuevo visitante
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'identificacion' => 'required|string|max:255',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'habitacion_id' => 'required|exists:habitacions,id',
        ]);

        // Guardar la foto en el almacenamiento
        $fotoPath = $request->file('foto')->store('fotos', 'public');

        // Crear el nuevo visitante
        Visitante::create([
            'nombre' => $request->nombre,
            'identificacion' => $request->identificacion,
            'foto' => $fotoPath,
            'habitacion_id' => $request->habitacion_id,
        ]);

        return redirect()->route('visitantes.create')->with('success', 'Visitante registrado exitosamente.');
    }

    // Método para buscar un visitante
    public function buscar(Request $request)
    {
        // Validar la entrada (asegúrate de que se haya subido una foto)
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Guardar la foto en el almacenamiento
        $path = $request->file('foto')->store('fotos', 'public');

        // Buscar al visitante por la foto
        $visitante = Visitante::where('foto', $path)->first();

        // Si el visitante no se encuentra
        if (!$visitante) {
            return redirect()->route('visitantes.ingreso')->withErrors(['No se encontró el visitante. Por favor, ingrésalo como nuevo.']);
        }

        // Si el visitante se encuentra, puedes redirigir o mostrar los datos
        return view('visitantes.detalle', compact('visitante'));
    }

    // Otros métodos...
}

