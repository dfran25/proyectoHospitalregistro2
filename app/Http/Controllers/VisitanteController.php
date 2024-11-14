<?php

namespace App\Http\Controllers;

use App\Models\Visitante;
use App\Models\Habitacion;
use App\Models\HoraEntrada;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\HoraSalida;
use GuzzleHttp\Client;

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
        $request->validate([
            'foto_base64' => 'required|string',
        ]);

        $fotoBase64 = $request->input('foto_base64');
        $urlFlask = 'http://127.0.0.1:5000/process_image';

        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->post($urlFlask, [
                'json' => [
                    'image' => $fotoBase64,
                ]
            ]);

            $resultado = json_decode($response->getBody(), true);

            if ($resultado && isset($resultado['mensaje']) && $resultado['mensaje'] === 'Coincidencia encontrada') {
                // Buscar al visitante en la base de datos usando la identificación de la respuesta de Flask
                $visitante = Visitante::where('identificacion', $resultado['identificacion'])->first();

                if ($visitante) {
                    // Registrar la hora de entrada en la tabla hora_entrada
                    HoraEntrada::create([
                        'id_visitante' => $visitante->id,
                        'id_habitacion' => $resultado['habitacion_id'], // Usamos el ID de la habitación devuelto por Flask
                        'fecha_entrada' => Carbon::now()->toDateString(),
                        'hora_entrada' => Carbon::now()->toTimeString(),
                    ]);

                    // Redirigir a una nueva vista de "Ingreso Exitoso"
                    return redirect()->route('visitantes.ingresoExitoso', ['id' => $visitante->id])
                        ->with('success', 'Ingreso registrado con éxito.');
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

        return response()->json(['success' => true]);
    }



    public function ingresoExitoso(Request $request)
    {
        // Extrae los datos desde el request
        $nombre = $request->input('nombre');
        $identificacion = $request->input('identificacion');
        $habitacion_id = $request->input('habitacion_id');
        $hora_actual = Carbon::now()->format('H:i:s'); // Genera la hora actual
        $fecha_actual = Carbon::now()->format('Y-m-d'); // Genera la fecha actual
    
        // Encuentra el visitante en la base de datos usando la identificación
        $visitante = Visitante::where('identificacion', $identificacion)->first();
    
        if ($visitante) {
            // Inserta un nuevo registro en la tabla 'hora_entrada' con los datos actuales
            HoraEntrada::create([
                'id_visitante' => $visitante->id,
                'id_habitacion' => $habitacion_id,
                'fecha_entrada' => $fecha_actual,
                'hora_entrada' => $hora_actual,
            ]);
    
            // Retorna la vista de ingreso exitoso con los datos necesarios
            return view('visitantes.ingreso_exitoso', [
                'nombre' => $nombre,
                'identificacion' => $identificacion,
                'habitacion_id' => $habitacion_id,
                'hora_actual' => $hora_actual,
                'fecha_actual' => $fecha_actual,
            ]);
        } else {
            // Si no se encuentra el visitante, redirige de vuelta a la página de ingreso con un mensaje de error
            return redirect()->route('visitantes.ingreso')->withErrors(['Visitante no encontrado.']);
        }
    }
    
    public function registrarSalidaSimple(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'image' => 'required|string',
                'id_visitante' => 'required|exists:visitantes,id',
                'id_habitacion' => 'required|exists:habitaciones,id',
            ]);
    
            HoraSalida::create([
                'id_visitante' => $validatedData['id_visitante'],
                'id_habitacion' => $validatedData['id_habitacion'],
                'fecha_salida' => Carbon::now()->toDateString(),
                'hora_salida' => Carbon::now()->toTimeString(),
            ]);
    
            return response()->json([
                'mensaje' => 'Salida registrada exitosamente',
                'hora_actual' => Carbon::now()->format('H:i:s'),
                'fecha_actual' => Carbon::now()->format('Y-m-d'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al registrar la salida: ' . $e->getMessage()], 500);
        }
    }
    
    
    public function mostrarHoraSalida(Request $request)
{
    $id_visitante = $request->input('id_visitante'); // Reemplaza con el método correcto para obtener el ID si es necesario
    $id_habitacion = $request->input('id_habitacion'); // Reemplaza con el método correcto para obtener el ID si es necesario

    return view('visitantes.hora_salida', [
        'id_visitante' => $id_visitante,
        'id_habitacion' => $id_habitacion
    ]);
}

    // Realiza la salida verificando la foto con Flask
    public function registrarSalida(Request $request)
{
    // Validar los datos que se enviaron
    $validatedData = $request->validate([
        'nombre' => 'required|string',
        'identificacion' => 'required|string',
        'habitacion_id' => 'required|integer',
    ]);

    try {
        // Buscar al visitante en la base de datos
        $visitante = Visitante::where('identificacion', $validatedData['identificacion'])->first();

        if (!$visitante) {
            return response()->json(['success' => false, 'message' => 'Visitante no encontrado.'], 404);
        }

        // Registrar la salida en la tabla 'hora_salida'
        HoraSalida::create([
            'id_visitante' => $visitante->id,
            'id_habitacion' => $validatedData['habitacion_id'],
            'fecha_salida' => Carbon::now()->toDateString(),
            'hora_salida' => Carbon::now()->toTimeString(),
        ]);

        // Devolver una respuesta de éxito
        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        // Devolver una respuesta de error
        return response()->json(['success' => false, 'message' => 'Error al registrar la salida: ' . $e->getMessage()], 500);
    }
}


    // Muestra la vista de salida exitosa
    public function salidaExitosa(Request $request)
{
    // Extrae los datos desde el request
    $nombre = $request->input('nombre');
    $identificacion = $request->input('identificacion');
    $habitacion_id = $request->input('habitacion_id');
    $hora_actual = Carbon::now()->format('H:i:s'); // Genera la hora actual
    $fecha_actual = Carbon::now()->format('Y-m-d'); // Genera la fecha actual

    // Encuentra el visitante en la base de datos usando la identificación
    $visitante = Visitante::where('identificacion', $identificacion)->first();

    if ($visitante) {
        // Inserta un nuevo registro en la tabla 'hora_salida' con los datos actuales
        HoraSalida::create([
            'id_visitante' => $visitante->id,
            'id_habitacion' => $habitacion_id,
            'fecha_salida' => $fecha_actual,
            'hora_salida' => $hora_actual,
        ]);

        // Retorna la vista de salida exitosa con los datos necesarios
        return view('visitantes.salida_exitosa', [
            'nombre' => $nombre,
            'identificacion' => $identificacion,
            'habitacion_id' => $habitacion_id,
            'hora_actual' => $hora_actual,
            'fecha_actual' => $fecha_actual,
        ]);
    } else {
        // Si no se encuentra el visitante, redirige de vuelta a la página de ingreso con un mensaje de error
        return redirect()->route('visitantes.ingreso')->withErrors(['Visitante no encontrado.']);
    }
}

 
}

