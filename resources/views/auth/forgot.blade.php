<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - CUP UAGRM</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="h-full flex items-center justify-center p-6 bg-gradient-to-br from-[#002855] via-[#001f3f] to-[#0d3b66]">

    <div class="w-full max-w-md bg-white rounded-3xl p-8 shadow-2xl">
        <div class="text-center mb-6">
            <div class="mx-auto w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center text-[#002855] text-2xl mb-4 shadow-sm">
                <i class="fa-solid fa-key"></i>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-800">¿Olvidaste tu contraseña?</h2>
            <p class="text-xs text-slate-400 font-semibold mt-1.5 leading-normal px-4">
                Ingresa tu correo institucional y te enviaremos un código único de 6 dígitos para reestablecer tu contraseña.
            </p>
        </div>

        <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
            @csrf

            @if($errors->any())
                <div class="p-3 bg-red-50 border border-red-100 text-red-600 text-xs rounded-xl font-medium">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="space-y-1.5">
                <label for="correo" class="text-xs font-bold text-slate-700">Correo institucional registrado</label>
                <div class="relative rounded-2xl shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-regular fa-envelope"></i>
                    </div>
                    <input type="email" name="correo" id="correo" required value="{{ old('correo') }}"
                        class="block w-full pl-11 pr-4 py-3.5 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-sm placeholder-slate-400 text-slate-800 font-semibold"
                        placeholder="Ej. maria@ejemplo.com">
                </div>
            </div>

            <button type="submit" class="w-full bg-[#002855] hover:bg-[#001f3f] text-white py-3.5 px-4 rounded-2xl font-bold text-sm shadow-lg hover:shadow-xl transition-all flex items-center justify-center space-x-2">
                <span>Enviar Código de Recuperación</span>
                <i class="fa-solid fa-arrow-right ml-1"></i>
            </button>
        </form>

        <div class="text-center mt-6 pt-4 border-t border-slate-100">
            <a href="{{ route('login') }}" class="text-xs font-bold text-slate-500 hover:text-slate-700 flex items-center justify-center space-x-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Volver al Inicio de Sesión</span>
            </a>
        </div>
    </div>

</body>
</html>
