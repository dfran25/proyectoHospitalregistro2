@extends('layouts.app')

@section('title', 'Salida Exitosa')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Salida Exitosa</h2>
    <p><strong>Nombre:</strong> {{ $nombre }}</p>
    <p><strong>Identificación:</strong> {{ $identificacion }}</p>
    <p><strong>Habitación:</strong> {{ $habitacion_id }}</p>
    <p><strong>Hora de Salida:</strong> {{ $hora_actual }}</p>
    <p><strong>Fecha de Salida:</strong> {{ $fecha_actual }}</p>
    <p><strong>Registro de salida completado exitosamente</strong></p>

    <a href="{{ route('welcome') }}" style="background-color: #4CAF50; color: #FFFFFF";>Volver a Inicio</a>
</div>
@endsection
