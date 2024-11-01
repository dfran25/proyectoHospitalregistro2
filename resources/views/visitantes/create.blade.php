@extends('layouts.app')

@section('title', 'Registrar Nuevo Visitante')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg p-4" style="background-color: #2E2E4E; color: #FFFFFF;">
        <h2 class="text-center mb-4">Registrar Nuevo Visitante</h2>

        <form action="{{ route('visitantes.store') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>

            <div class="form-group mb-3">
                <label for="identificacion">Identificación</label>
                <input type="text" class="form-control" id="identificacion" name="identificacion" required>
            </div>

            <div class="form-group mb-3">
                <label for="habitacion_id">Habitación</label>
                <select class="form-control" id="habitacion_id" name="habitacion_id" required>
                    <option value="">Seleccionar Habitación</option>
                    @foreach($habitaciones as $habitacion)
                        <option value="{{ $habitacion->id }}">{{ $habitacion->numero_habitacion }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Captura de Foto -->
            <div class="form-group mb-3 text-center">
                <label for="foto" class="form-label">Captura de Foto</label>
                <div class="d-flex justify-content-center">
                    <video id="video" width="320" height="240" autoplay class="border rounded"></video>
                </div>
                <button type="button" id="capture" class="btn btn-secondary mt-3">Tomar Foto</button>
                
                <!-- Canvas oculto -->
                <canvas id="canvas" style="display: none;"></canvas>

                <input type="hidden" name="foto" id="foto_base64"> <!-- Foto en base64 para enviar -->
                <div id="photoPreview" class="mt-3" style="display:none;">
                    <p>Foto Capturada:</p>
                    <img id="photo" src="" alt="Foto Capturada" class="img-thumbnail">
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-custom" style="background-color: #4CAF50; color: #FFFFFF;">Registrar Visitante</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
    <!-- Incluye el archivo JavaScript para manejar la captura de foto -->
    <script src="{{ asset('js/camera.js') }}"></script>
@endsection
