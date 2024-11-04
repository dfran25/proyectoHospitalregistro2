<?php

namespace App\Http\Controllers;

use App\Models\Visitante;
use App\Models\Habitacion;
use App\Models\HoraEntrada;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VisitanteController extends Controller
{
    // Muestra el formulario de ingreso de visitantes
    public function ingreso()
    {
        return view('visitantes.ingreso');
    }

    // Muestra el formulario de creación de visitantes
    public function create()
    {
        $habitaciones = Habitacion::all();
        return view('visitantes.create', compact('habitaciones'));
    }

    // Método para buscar un visitante existente
    public function buscar(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Guardar la foto temporalmente
        $path = $request->file('foto')->store('fotos', 'public');

        // Buscar al visitante por la foto
        $visitante = Visitante::where('foto', $path)->first();

        if (!$visitante) {
            return redirect()->route('visitantes.ingreso')->withErrors(['No se encontró el visitante. Por favor, ingrésalo como nuevo.']);
        }

        return view('visitantes.detalle', compact('visitante'));
    }

    // Almacena un nuevo visitante
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'identificacion' => 'required|string|max:255',
            'foto' => 'required',
            'habitacion_id' => 'required|exists:habitaciones,id',
        ]);

        $fotoBase64 = $request->input('foto');
        $foto = str_replace('data:image/png;base64,', '', $fotoBase64);
        $foto = str_replace(' ', '+', $foto);
        $fotoNombre = 'foto_' . time() . '.png';
        
        Storage::disk('public')->put($fotoNombre, base64_decode($foto));

        Visitante::create([
            'nombre' => $validatedData['nombre'],
            'identificacion' => $validatedData['identificacion'],
            'foto' => $fotoNombre,
            'habitacion_id' => $validatedData['habitacion_id'],
        ]);

        return redirect()->route('visitantes.index')->with('success', 'Visitante registrado con éxito.');
    }

    // Muestra todos los visitantes
    public function index()
    {
        $visitantes = Visitante::all();
        return view('visitantes.index', compact('visitantes'));
    }

    // Muestra el detalle de un visitante específico
    public function detalle($id)
    {
        $visitante = Visitante::with('habitacion')->findOrFail($id);
        return view('visitantes.detalles', compact('visitante'));
    }

    // Busca un visitante por foto usando base64
    /* public function buscarPorFoto(Request $request)
    {
        $fotoBase64 = $request->input('foto_base64');
        $visitante = Visitante::where('foto', $fotoBase64)->first();

        if ($visitante) {
            return view('visitantes.detalles', compact('visitante'));
        } else {
            return redirect()->route('visitantes.ingreso')->withErrors(['No se encontró el visitante. Puedes registrarlo como nuevo.']);
        }
    } */

    public function buscarPorFoto(Request $request)
{
    // Valida que la foto base64 esté presente en la solicitud
    $request->validate([
        'foto_base64' => 'required|string',
    ]);

    $fotoBase64 = $request->input('foto_base64');

    // URL del servidor Flask
    $urlFlask = 'http://127.0.0.1:5000/detectar_rostro';

    // Inicializa el cliente HTTP
    $client = new \GuzzleHttp\Client();

    try {
        // Envía la foto al servidor Flask
        $response = $client->post($urlFlask, [
            'form_params' => [
                'imagen' => $fotoBase64,
            ]
        ]);

        // Decodifica la respuesta JSON de Flask
        $resultado = json_decode($response->getBody(), true);

        if ($resultado && isset($resultado['reconocido']) && $resultado['reconocido']) {
            // Si el rostro es reconocido, busca al visitante por su identificación o nombre
            $visitante = Visitante::where('identificacion', $resultado['identificacion'])->first();

            if ($visitante) {
                return view('visitantes.detalles', compact('visitante'));
            } else {
                return redirect()->route('visitantes.ingreso')->withErrors(['Visitante no encontrado en la base de datos.']);
            }
        } else {
            return redirect()->route('visitantes.ingreso')->withErrors(['No se reconoció ningún rostro.']);
        }

    } catch (\Exception $e) {
        return redirect()->route('visitantes.ingreso')->withErrors(['Error al comunicarse con el servidor Flask: ' . $e->getMessage()]);
    }
}

public function enviarFotoAFlask($pathImagen)
{
    $rutaCompleta = storage_path('app/public/' . $pathImagen);

    if (file_exists($rutaCompleta)) {
        $imagenBase64 = base64_encode(file_get_contents($rutaCompleta));

        // Configuración de la solicitud HTTP
        $cliente = new \GuzzleHttp\Client();
        $urlFlask = 'http://localhost:5000/process_image';

        try {
            $respuesta = $cliente->post($urlFlask, [
                'json' => [
                    'image' => $imagenBase64
                ]
            ]);

            $resultado = json_decode($respuesta->getBody(), true);
            return response()->json($resultado);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al enviar la imagen a Flask: ' . $e->getMessage()]);
        }
    } else {
        return response()->json(['error' => 'La imagen no se encontró en el almacenamiento.']);
    }
}



    // Registra la hora de entrada de un visitante
    public function registrarIngreso(Request $request)
    {
        $validatedData = $request->validate([
            'id_visitante' => 'required|exists:visitantes,id',
            'id_habitacion' => 'required|exists:habitaciones,id',
        ]);

        HoraEntrada::create([
            'id_visitante' => $validatedData['id_visitante'],
            'id_habitacion' => $validatedData['id_habitacion'],
            'fecha_entrada' => Carbon::now()->toDateString(),
            'hora_entrada' => Carbon::now()->toTimeString(),
        ]);

        return redirect()->route('visitantes.index')->with('success', 'Ingreso registrado con éxito.');
    }
}

