<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Visitas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: #1A1A2E;
            color: #FFFFFF;
            font-family: Arial, sans-serif;
        }

        .welcome-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        .left-content, .right-content {
            background-color: #2E2E4E;
            padding: 30px;
            border-radius: 10px;
        }

        .left-content {
            flex: 1;
            margin-right: 20px;
        }

        .right-content {
            width: 300px;
            text-align: center;
        }

        .right-content img {
            width: 80px;
            margin-bottom: 20px;
        }

        .btn-custom {
            background-color: #4CAF50;
            color: #FFFFFF;
            width: 100%;
            margin-top: 10px;
            padding: 12px 0;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
        }

        .testimonial-text {
            font-size: 14px;
            color: #CCCCCC;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666666;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <!-- Left Content -->
        <div class="left-content">
            <h1>Registro Visitas Hospital UNAB</h1>
            <p class="testimonial-text">Everything you need to accept to payment and grow your money of manage anywhere on planet</p>
            <blockquote class="blockquote">
                <p class="testimonial-text">Texto introduccion pendiente😌</p>
                <footer class="blockquote-footer text-white">Diego Lozano</footer>
            </blockquote>
            <div class="d-flex justify-content-center mt-3">
                <img src="https://via.placeholder.com/40" class="rounded-circle mx-1" alt="User">
                <img src="https://via.placeholder.com/40" class="rounded-circle mx-1" alt="User">
                <img src="https://via.placeholder.com/40" class="rounded-circle mx-1" alt="User">
                <img src="https://unab.edu.co/tenga-en-cuenta-el-uso-del-logo-unab-en-su-gestion-diaria/" class="rounded-circle mx-1" alt="User">
            </div>
        </div>

        <!-- Right Content -->
        <div class="right-content">
            <img src="{{ asset('images/logo-hospital.png') }}" alt="Logo del Hospital"> <!-- Cambia esto al logo que tienes -->
            <h2 class="mb-4">Bienvenido</h2>
            <a href="{{ route('visitantes.create') }}" class="btn btn-custom">Visitante nuevo</a>
            <a href="{{ route('visitantes.ingreso') }}" class="btn btn-custom">Visitante registrado</a>
            <p class="testimonial-text">By Diego Lozano😌</p>
        </div>
    </div>

    <footer class="footer">
        © 2024 UNAB Inc. Copyright and rights reserved
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
