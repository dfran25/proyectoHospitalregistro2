@extends('layouts.app')

@section('title', 'Ingreso de Visitantes')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg p-4" style="background-color: #2E2E4E; color: #FFFFFF;">
        <h2 class="text-center mb-4">Ingreso de Visitantes</h2>

        <!-- Opción 1: Capturar Foto para Búsqueda de Visitante Existente -->
        <div class="card mb-4" style="background-color: #1A1A2E;">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Opción 1: Capturar Foto para Búsqueda de Visitante Existente</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('visitantes.buscarPorFoto') }}" method="POST">
                    @csrf
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
                        <button type="submit" id="search" class="btn btn-custom" style="background-color: #4CAF50; color: #FFFFFF;">Buscar Visitante</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Opción 2: Registrar Nuevo Visitante -->
        {{-- <div class="text-center">
            <h4 class="my-4">Opción 2: Registrar Nuevo Visitante</h4>
            <a href="{{ route('visitantes.create') }}" class="btn btn-custom" style="background-color: #4CAF50; color: #FFFFFF;">Registrar Visitante Nuevo</a>
        </div> --}}
    </div>
</div>
@endsection

{{-- @section('scripts')
    <script src="{{ asset('js/camera.js') }}"></script>
@endsection
 --}}
 @section('scripts')
 <script>
    document.addEventListener('DOMContentLoaded', function () {
    const video = document.getElementById('video');

    // Solicitar acceso a la cámara
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            video.srcObject = stream;
            video.play();
        })
        .catch(error => {
            console.error('Error al acceder a la cámara: ', error);
            alert('No se pudo acceder a la cámara.');
        });
});

document.getElementById('capture').addEventListener('click', function () {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const photo = document.getElementById('photo');
    const photoBase64Input = document.getElementById('foto_base64');

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

    const dataURL = canvas.toDataURL('image/jpeg');
    photo.src = dataURL;
    photoBase64Input.value = dataURL;

    document.getElementById('photoPreview').style.display = 'block';
});

document.getElementById('search').addEventListener('click', function (event) {
    event.preventDefault(); // Evita el envío del formulario HTML tradicional

    const photoBase64Input = document.getElementById('foto_base64').value;
    if (!photoBase64Input) {
        alert("Primero debes capturar una foto.");
        return;
    }

    // Enviar la imagen al servidor Flask
    fetch('http://localhost:5000/process_image', {
        method: 'POST',
        body: JSON.stringify({ image: photoBase64Input }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log("Respuesta del servidor:", data);
        if (data.mensaje) {
            alert(data.mensaje);
        } else {
            alert("Respuesta inesperada del servidor.");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al enviar la imagen al servidor Flask.');
    });
});
 </script>
@endsection


