<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de Contraseña - CUP UAGRM</title>
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #f8fafc; }
        .container { max-width: 480px; margin: 50px auto; background: #fff; padding: 40px; border-radius: 24px; border:1px solid #f1f5f9; }
        input, button { width: 100%; padding: 12px; margin-bottom: 15px; border-radius: 8px; font-size: 14px; }
        button { background-color: #1e40af; color: #fff; border: none; cursor: pointer; }
        button:hover { background-color: #1e3a8a; }
        .error { color:red; font-size:13px; margin-bottom:10px; }
        .success { color:green; font-size:14px; margin-bottom:10px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Recuperación de Contraseña</h1>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif

    {{-- Paso 1: Ingresar correo --}}
    @if(!isset($step) || $step === 1)
        <form method="POST" action="{{ route('recuperar.enviarCodigo') }}">
            @csrf
            <input type="email" name="correo" placeholder="Ingresa tu correo electrónico" required>
            <button type="submit">Enviar Código</button>
        </form>
    @endif

    {{-- Paso 2: Ingresar código --}}
    @if(isset($step) && $step === 2)
        <form method="POST" action="{{ route('recuperar.validarCodigo') }}">
            @csrf
            <input type="hidden" name="usuario_id" value="{{ $usuario_id }}">
            <input type="text" name="codigo" placeholder="Ingresa el código de 6 dígitos" maxlength="6" required>
            <button type="submit">Verificar Código</button>
        </form>
    @endif

    {{-- Paso 3: Nueva contraseña --}}
    @if(isset($step) && $step === 3)
        <form method="POST" action="{{ route('recuperar.cambiar') }}">
            @csrf
            <input type="hidden" name="usuario_id" value="{{ $usuario_id }}">
            <input type="password" name="password" placeholder="Nueva contraseña" required>
            <input type="password" name="password_confirmation" placeholder="Confirmar contraseña" required>
            <button type="submit">Actualizar Contraseña</button>
        </form>
    @endif

</div>
</body>
</html>