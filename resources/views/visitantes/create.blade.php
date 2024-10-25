<!DOCTYPE html>
<html lang="es">
    @extends('layouts.app')

    @section('title', 'Registro de Visitantes')

    @section('content')
    <div class="container mt-4">
        <h1 class="mb-4">Registrar Visitante</h1>

        <!-- Mostrar mensajes de éxito o errores -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulario de registro -->
        <form action="{{ route('visitantes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" name="nombre" required>
            </div>

            <div class="mb-3">
                <label for="identificacion" class="form-label">Identificación:</label>
                <input type="text" class="form-control" name="identificacion" required>
            </div>

            <!-- Captura de foto con cámara web -->
            <div class="mb-3">
                <label for="foto" class="form-label">Foto:</label>
                <div>
                    <video id="video" width="320" height="240" autoplay></video>
                    <canvas id="canvas" style="display:none;"></canvas>
                </div>
                <button type="button" id="capture" class="btn btn-secondary">Tomar Foto</button>
                <input type="hidden" name="foto" id="foto" required>
                <div id="photoPreview" style="display:none; margin-top: 10px;">
                    <p>Foto Capturada:</p>
                    <img id="photo" src="" alt="Foto Capturada">
                </div>
            </div>

            <div class="form-group">
                <label for="habitacion">Habitación:</label>
                <select name="habitacion_id" class="form-control" required>
                    <option value="">Seleccionar Habitación</option>
                    @foreach($habitaciones as $habitacion)
                        <option value="{{ $habitacion->id }}">{{ $habitacion->numero_habitacion }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Registrar Visitante</button>
        </form>
    </div>

    <script>
        // Acceder a la cámara web
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const captureButton = document.getElementById('capture');
        const photoPreview = document.getElementById('photoPreview');
        const photo = document.getElementById('photo');
        const fotoInput = document.getElementById('foto');

        // Solicitar acceso a la cámara
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(stream) {
                video.srcObject = stream;
            })
            .catch(function(err) {
                console.log("Error al acceder a la cámara: " + err);
            });

        // Capturar la imagen al hacer clic en el botón
        captureButton.addEventListener('click', function() {
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Convertir la imagen a base64
            const dataURL = canvas.toDataURL('image/png');
            fotoInput.value = dataURL; // Almacenar la imagen base64 en el input oculto

            // Mostrar la imagen capturada
            photo.src = dataURL;
            photoPreview.style.display = 'block';
        });
    </script>
    @endsection
</html>
