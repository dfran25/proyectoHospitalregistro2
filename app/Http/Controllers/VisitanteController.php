<?php

namespace App\Http\Controllers;

use App\Models\Visitante;
use App\Models\Habitacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;




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

    public function store(Request $request)
{
    $validatedData = $request->validate([
        'nombre' => 'required|string|max:255',
        'identificacion' => 'required|string|max:255',
        'foto' => 'required',
        'habitacion_id' => 'required|exists:habitaciones,id',
    ]);

    // Decodificar la imagen base64
    $fotoBase64 = $request->input('foto');
    $foto = str_replace('data:image/png;base64,', '', $fotoBase64);
    $foto = str_replace(' ', '+', $foto);
    $fotoNombre = 'foto_' . time() . '.png';
    
    // Guardar la imagen en el servidor (opcional)
    Storage::disk('public')->put($fotoNombre, base64_decode($foto));

    // Crear el visitante
    Visitante::create([
        'nombre' => $validatedData['nombre'],
        'identificacion' => $validatedData['identificacion'],
        'foto' => $fotoNombre, // Guardamos el nombre del archivo en la base de datos
        'habitacion_id' => $validatedData['habitacion_id'],
    ]);

    return redirect()->route('visitantes.index')->with('success', 'Visitante registrado con éxito.');
}
    // Otros métodos...
}

