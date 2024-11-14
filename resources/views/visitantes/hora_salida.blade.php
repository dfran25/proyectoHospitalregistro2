@extends('layouts.app')

@section('title', 'Registro de Salida de Visitantes')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg p-4" style="background-color: #2E2E4E; color: #FFFFFF;">
        <h2 class="text-center mb-4">Registro de Salida de Visitantes</h2>

        <!-- Captura de Foto para Búsqueda de Visitante Existente -->
        <div class="card mb-4" style="background-color: #1A1A2E;">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Capturar Foto para Registrar Salida, No usar gafas y mirar al frente.</h5>
            </div>
            <div class="card-body">
                <div class="mb-3 text-center">
                    <label for="foto" class="form-label font-weight-bold">Captura de Foto:</label>
                    <div class="d-flex justify-content-center">
                        <video id="video" width="320" height="240" autoplay class="border rounded"></video>
                    </div>
                    <button type="button" id="capture" class="btn btn-secondary mt-3">Tomar Foto</button>
                    
                    <!-- Elemento canvas oculto para capturar la foto -->
                    <canvas id="canvas" style="display: none;"></canvas>

                    <input type="hidden" name="foto_base64" id="foto_base64">
                    <div id="photoPreview" class="mt-3" style="display:none;">
                        <p>Foto Capturada:</p>
                        <img id="photo" src="" alt="Foto Capturada" class="img-thumbnail">
                    </div>
                </div>

                <div class="text-center">
                    <button type="button" id="search" class="btn btn-custom" style="background-color: #4CAF50; color: #FFFFFF;">Registrar Salida</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const video = document.getElementById('video');
        const captureButton = document.getElementById('capture');
        const searchButton = document.getElementById('search');
        const canvas = document.getElementById('canvas');
        const photoBase64Input = document.getElementById('foto_base64');
        const photoPreview = document.getElementById('photoPreview');
        const photo = document.getElementById('photo');

        // Acceso a la cámara
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
                video.play();
            })
            .catch(error => {
                console.error('Error al acceder a la cámara: ', error);
                alert('No se pudo acceder a la cámara.');
            });

        // Capturar la foto
        captureButton.addEventListener('click', function () {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
            const dataURL = canvas.toDataURL('image/jpeg');
            photo.src = dataURL;
            photoBase64Input.value = dataURL;
            photoPreview.style.display = 'block';
        });

        // Enviar la foto para verificación
        searchButton.addEventListener('click', function (event) {
            event.preventDefault();
            if (!photoBase64Input.value) {
                alert("Primero debes capturar una foto.");
                return;
            }

            fetch('http://localhost:5000/process_image', {
                method: 'POST',
                body: JSON.stringify({ image: photoBase64Input.value }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.mensaje === "Coincidencia encontrada") {
                    // Redirigir a salida_exitosa con datos obtenidos de Flask
                    const url = new URL("{{ route('visitantes.salida_exitosa') }}", window.location.origin);
                    url.searchParams.append("nombre", data.nombre);
                    url.searchParams.append("identificacion", data.identificacion);
                    url.searchParams.append("habitacion_id", data.habitacion_id);
                    url.searchParams.append("hora_actual", new Date().toLocaleTimeString('en-GB'));
                    window.location.href = url;
                } else {
                    alert(data.mensaje);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al enviar la imagen al servidor Flask.');
            });
        });
    });
</script>
@endsection

