@extends('layouts.app')

@section('title', 'Ingreso Exitoso')

@section('content')
<div class="container mt-5">
    <h2>Ingreso Exitoso</h2>
    <p><strong>Nombre:</strong> {{ $nombre }}</p>
    <p><strong>Identificación:</strong> {{ $identificacion }}</p>
    <p><strong>Habitación:</strong> {{ $habitacion_id }}</p>
    <p><strong>Hora de Ingreso:</strong> {{ $hora }}</p>
    <a href="{{ route('visitantes.ingreso') }}" class="btn btn-primary">Volver al Inicio</a>
</div>
@endsection
