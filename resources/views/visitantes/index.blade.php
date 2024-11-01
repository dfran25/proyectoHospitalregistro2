@extends('layouts.app')

@section('title', 'Listado de Visitantes')

@section('content')
<div class="container mt-5">
    <h2>Listado de Visitantes</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabla de visitantes -->
    <table class="table table-bordered table-striped mt-4">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Identificación</th>
                <th>Foto</th>
                <th>Habitación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($visitantes as $visitante)
                <tr>
                    <td>{{ $visitante->nombre }}</td>
                    <td>{{ $visitante->identificacion }}</td>
                    <td>
                        @if($visitante->foto && Storage::disk('public')->exists($visitante->foto))
                            <img src="{{ asset('storage/' . $visitante->foto) }}" alt="Foto del Visitante" width="80">
                        @else
                            <em>Sin foto</em>
                        @endif
                    </td>
                    <td>{{ $visitante->habitacion->numero_habitacion }}</td>
                    <td>
                        <a href="{{ route('visitantes.detalle', $visitante->id) }}" class="btn btn-info btn-sm">Ver Detalles</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
