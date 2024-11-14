@extends('layouts.app')

@section('title', 'Ingreso Exitoso')
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Ingreso Exitoso</h2>
    <p><strong>Nombre:</strong> {{ $nombre }}</p>
    <p><strong>Identificación:</strong> {{ $identificacion }}</p>
    <p><strong>Habitación:</strong> {{ $habitacion_id }}</p>
    <p><strong>Hora de Ingreso:</strong> {{ $hora_actual }}</p>
    <p><strong>Fecha de Ingreso:</strong> {{ $fecha_actual }}</p>
    <p><strong>Reconocimiento exitoso</strong></p>


    <a href="{{ route('welcome') }}" style="background-color: #4CAF50; color: #FFFFFF";>Volver a Inicio</a>
</div>
@endsection
