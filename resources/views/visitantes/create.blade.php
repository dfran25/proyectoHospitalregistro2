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
    
            <div class="mb-3">
                <label for="foto" class="form-label">Foto:</label>
                <input type="file" class="form-control" name="foto" accept="image/*" required>
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
    @endsection
    