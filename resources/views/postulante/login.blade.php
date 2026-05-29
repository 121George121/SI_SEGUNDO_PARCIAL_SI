<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - CUP UAGRM</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 480px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05), 0 4px 6px -4px rgba(0,0,0,0.05);
            border: 1px solid #f1f5f9;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        form input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            font-size: 14px;
        }
        form button {
            width: 100%;
            padding: 12px;
            background-color: #1e40af;
            color: #fff;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }
        form button:hover {
            background-color: #1e3a8a;
        }
        .error {
            color: red;
            font-size: 13px;
            margin-bottom: 10px;
        }
        .forgot {
            text-align: right;
            display: block;
            margin-bottom: 15px;
            font-size: 13px;
            text-decoration: none;
            color: #1e40af;
        }
        .forgot:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Iniciar sesión</h1>

        @if(session('success'))
            <div style="color:green; font-size:14px; margin-bottom:10px;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <input type="email" name="correo" placeholder="Correo institucional" value="{{ old('correo') }}" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <a class="forgot" href="{{ route('recuperar.contrasena') }}">¿Olvidaste tu contraseña?</a>
            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>