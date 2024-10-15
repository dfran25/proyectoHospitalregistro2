@extends('layouts.app')

@section('title', 'Detalles del Visitante')

@section('content')
<div class="container mt-5">
    <h2>Detalles del Visitante</h2>
    <p><strong>Nombre:</strong> {{ $visitante->nombre }}</p>
    <p><strong>Identificación:</strong> {{ $visitante->identificacion }}</p>
    <img src="{{ Storage::url($visitante->foto) }}" alt="Foto del Visitante" class="img-fluid" />
    <p><strong>Habitación:</strong> {{ $visitante->habitacion->numero_habitacion }}</p>
    
    <a href="{{ route('visitantes.ingreso') }}" class="btn btn-secondary">Volver</a>
</div>
@endsection
