<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Proyecto Hospital')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #2e3358;">
        <a class="navbar-brand" href="{{ route('welcome') }}">
            <img src="{{ asset('images/logo-hospital.png') }}" alt="Hospital Logo" width="40" height="40">
            Proyecto Hospital
        </a>
    </nav>
    

    <div class="container mt-5">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts específicos de cada vista -->
    @yield('scripts')
</body>
</html>
