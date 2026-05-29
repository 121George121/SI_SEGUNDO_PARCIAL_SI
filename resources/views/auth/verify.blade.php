<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Código - CUP UAGRM</title>
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
            <div class="mx-auto w-16 h-16 bg-red-50 rounded-full flex items-center justify-center text-red-600 text-2xl mb-4 shadow-sm">
                <i class="fa-solid fa-envelope-open-text"></i>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-800">Restablecer Contraseña</h2>
            <p class="text-xs text-slate-400 font-semibold mt-1.5 leading-normal">
                Ingresa el código de 6 dígitos enviado y escribe tu nueva contraseña segura.
            </p>
        </div>

        @if(session('status'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl mb-6 text-xs font-semibold">
                <div class="flex items-center space-x-2 text-emerald-600 mb-1">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Estado de Envío</span>
                </div>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        

        <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
            @csrf

            @if($errors->any())
                <div class="p-3 bg-red-50 border border-red-100 text-red-600 text-xs rounded-xl font-medium">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Hidden or readable email field -->
            <div class="space-y-1.5">
                <label for="correo" class="text-xs font-bold text-slate-700">Confirmar Correo Electrónico</label>
                <input type="email" name="correo" id="correo" required value="{{ $email ?? old('correo') }}"
                    class="block w-full px-4 py-3 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-sm font-semibold text-slate-800">
            </div>

            <!-- Verification Code field -->
            <div class="space-y-1.5">
                <label for="code" class="text-xs font-bold text-slate-700">Código de Verificación (6 dígitos)</label>
                <div class="relative rounded-2xl shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-hashtag"></i>
                    </div>
                    <input type="text" name="code" id="code" required maxLength="6"
                        class="block w-full pl-11 pr-4 py-3 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-sm placeholder-slate-400 text-slate-800 font-semibold tracking-widest text-center"
                        placeholder="123456">
                </div>
            </div>

            <!-- New Password field -->
            <div class="space-y-1.5">
                <label for="contraseña" class="text-xs font-bold text-slate-700">Nueva Contraseña</label>
                <input type="password" name="contraseña" id="contraseña" required
                    class="block w-full px-4 py-3 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-sm placeholder-slate-400 text-slate-800 font-semibold"
                    placeholder="Mínimo 8 caracteres">
                <p class="text-[9px] text-slate-400 font-semibold leading-tight">Mínimo 8 letras (mayúsculas y minúsculas), números y símbolos.</p>
            </div>

            <!-- Confirm Password field -->
            <div class="space-y-1.5">
                <label for="contraseña_confirmation" class="text-xs font-bold text-slate-700">Confirmar Nueva Contraseña</label>
                <input type="password" name="contraseña_confirmation" id="contraseña_confirmation" required
                    class="block w-full px-4 py-3 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-sm placeholder-slate-400 text-slate-800 font-semibold"
                    placeholder="Repita la contraseña">
            </div>

            <button type="submit" class="w-full bg-[#c1121f] hover:bg-[#a80f1a] text-white py-3.5 px-4 rounded-2xl font-bold text-sm shadow-lg hover:shadow-xl transition-all flex items-center justify-center space-x-2 mt-6">
                <i class="fa-solid fa-lock-open mr-1"></i>
                <span>Verificar y Reestablecer</span>
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
