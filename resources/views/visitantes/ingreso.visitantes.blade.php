<!DOCTYPE html>
<html lang="es">
    @extends('layouts.app')

    @section('title', 'Ingreso de Visitantes')
    
    @section('content')
    <div class="container mt-5">
        <h2>Ingreso de Visitantes</h2>
    
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    
        <div class="mb-4">
            <h4>Opción 1: Ingresar Foto de Visitante Existente</h4>
            <form action="{{ route('visitantes.buscar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="foto">Foto del Visitante:</label>
                    <input type="file" name="foto" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Buscar Visitante</button>
            </form>
        </div>
    
        <div>
            <h4>Opción 2: Registrar Nuevo Visitante</h4>
            <a href="{{ route('visitantes.create') }}" class="btn btn-secondary">Registrar Visitante Nuevo</a>
        </div>
    </div>
    @endsection
    