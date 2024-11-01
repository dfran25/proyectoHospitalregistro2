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
                        <button type="submit" class="btn btn-custom" style="background-color: #4CAF50; color: #FFFFFF;">Buscar Visitante</button>
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

@section('scripts')
    <script src="{{ asset('js/camera.js') }}"></script>
@endsection
