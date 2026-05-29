<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Postulante - CUP UAGRM</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="h-full flex flex-col md:flex-row overflow-hidden">

    <!-- LEFT SIDE BANNER (Deep Blue Institutional Wave) -->
    <div class="hidden md:flex md:w-5/12 bg-gradient-to-br from-[#002855] via-[#001f3f] to-[#0d3b66] p-16 flex-col justify-between relative overflow-hidden">
        
        <!-- Subtle Wave Background Pattern -->
        <div class="absolute inset-0 pointer-events-none opacity-10">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000">
                <path d="M0,1000 C300,900 350,700 500,500 C650,300 700,100 1000,0 L1000,1000 Z" fill="white"/>
            </svg>
        </div>

        <!-- UAGRM Logo and Header -->
        <div class="flex items-center space-x-4 z-10">
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-2xl border border-red-500">
                <i class="fa-solid fa-graduation-cap text-[#002855] text-2xl"></i>
            </div>
            <div>
                <h2 class="text-white font-extrabold text-lg tracking-wider">FACULTAD DE INGENIERÍA</h2>
                <p class="text-xs text-blue-200 font-semibold tracking-wide uppercase leading-tight">U A G R M</p>
            </div>
        </div>

        <!-- Banner Content -->
        <div class="max-w-xl my-auto z-10">
            <h1 class="text-3xl font-extrabold text-white leading-tight">
                Formulario de <br>
                <span class="text-[#c1121f] text-stroke">Registro Postulante</span>
            </h1>
            <p class="mt-4 text-blue-100 text-xs leading-relaxed font-medium">
                Completa tus datos personales y académicos para crear tu expediente y registrar tu postulación al Curso Preuniversitario (CUP).
            </p>
        </div>

        <!-- Footer Card Note -->
        <div class="z-10 border-t border-blue-900/40 pt-6">
            <div class="flex items-center space-x-3 bg-blue-950/40 p-4 rounded-2xl border border-blue-900/20">
                <div class="w-8 h-8 rounded-full bg-red-600/30 flex items-center justify-center text-white text-sm flex-shrink-0">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <p class="text-[10px] text-blue-200 font-semibold leading-normal">
                    Asegúrate de ingresar tu Cédula de Identidad (CI) y Correo de forma exacta. Serán tus credenciales de acceso.
                </p>
            </div>
        </div>
    </div>

    <!-- RIGHT SIDE (Register Form) -->
    <div class="w-full md:w-7/12 bg-white flex items-center justify-center p-8 md:p-12 overflow-y-auto h-full">
        <div class="w-full max-w-xl bg-white rounded-3xl p-4 flex flex-col justify-between">
            
            <div class="text-center md:text-left mb-6">
                <h2 class="text-2xl font-extrabold text-slate-800">Crea tu cuenta de Postulante</h2>
                <p class="text-xs text-slate-400 font-semibold mt-1">Completa todos los campos obligatorios para registrarte</p>
            </div>

            <!-- Form -->
            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Custom inline error alert -->
                @if($errors->any())
                    <div class="p-3 bg-red-50 border border-red-100 text-red-600 text-xs rounded-xl font-medium">
                        <i class="fa-solid fa-circle-exclamation mr-1"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- CI field -->
                    <div class="space-y-1">
                        <label for="ci" class="text-xs font-bold text-slate-700">Cédula de Identidad (CI) *</label>
                        <input type="text" name="ci" id="ci" required value="{{ old('ci') }}"
                            class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-xs font-semibold text-slate-800"
                            placeholder="Ej. 1234567">
                    </div>

                    <!-- Carrera field -->
                    <div class="space-y-1">
                        <label for="carrera_id" class="text-xs font-bold text-slate-700">Carrera a Postular *</label>
                        <select name="carrera_id" id="carrera_id" required
                            class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-xs font-semibold text-slate-800 bg-white">
                            <option value="">Seleccione una carrera</option>
                            @foreach($carreras as $c)
                                <option value="{{ $c->id_carrera }}" {{ old('carrera_id') == $c->id_carrera ? 'selected' : '' }}>
                                    {{ $c->nombre_carrera }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nombre field -->
                    <div class="space-y-1">
                        <label for="nombre" class="text-xs font-bold text-slate-700">Nombre(s) *</label>
                        <input type="text" name="nombre" id="nombre" required value="{{ old('nombre') }}"
                            class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-xs font-semibold text-slate-800"
                            placeholder="Ej. María">
                    </div>

                    <!-- Apellido field -->
                    <div class="space-y-1">
                        <label for="apellido" class="text-xs font-bold text-slate-700">Apellido(s) *</label>
                        <input type="text" name="apellido" id="apellido" required value="{{ old('apellido') }}"
                            class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-xs font-semibold text-slate-800"
                            placeholder="Ej. Gonzalez">
                    </div>

                    <!-- Fecha Nacimiento field -->
                    <div class="space-y-1">
                        <label for="fecha_nacimiento" class="text-xs font-bold text-slate-700">Fecha de Nacimiento *</label>
                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required value="{{ old('fecha_nacimiento') }}"
                            class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-xs font-semibold text-slate-800">
                    </div>

                    <!-- Telefono field -->
                    <div class="space-y-1">
                        <label for="telefono" class="text-xs font-bold text-slate-700">Teléfono / Celular</label>
                        <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}"
                            class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-xs font-semibold text-slate-800"
                            placeholder="Ej. 76543210">
                    </div>

                    <!-- Correo field -->
                    <div class="space-y-1">
                        <label for="correo" class="text-xs font-bold text-slate-700">Correo Electrónico *</label>
                        <input type="email" name="correo" id="correo" required value="{{ old('correo') }}"
                            class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-xs font-semibold text-slate-800"
                            placeholder="Ej. maria@ejemplo.com">
                    </div>

                    <!-- Direccion field -->
                    <div class="space-y-1">
                        <label for="direccion" class="text-xs font-bold text-slate-700">Dirección Domiciliaria</label>
                        <input type="text" name="direccion" id="direccion" value="{{ old('direccion') }}"
                            class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-xs font-semibold text-slate-800"
                            placeholder="Ej. UV-101, Av. Bush">
                    </div>

                    <!-- Nombre Usuario field -->
                    <div class="space-y-1">
                        <label for="nombre_usuario" class="text-xs font-bold text-slate-700">Nombre de Usuario *</label>
                        <input type="text" name="nombre_usuario" id="nombre_usuario" required value="{{ old('nombre_usuario') }}"
                            class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-xs font-semibold text-slate-800"
                            placeholder="Ej. mariag26">
                    </div>
                </div>

                <hr class="border-slate-100 my-4">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Password field -->
                    <div class="space-y-1">
                        <label for="contraseña" class="text-xs font-bold text-slate-700">Contraseña *</label>
                        <input type="password" name="contraseña" id="contraseña" required
                            class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-xs font-semibold text-slate-800"
                            placeholder="Mínimo 8 caracteres">
                        <p class="text-[9px] text-slate-400 font-semibold leading-tight mt-1">Debe incluir mayúsculas, minúsculas, números y símbolos.</p>
                    </div>

                    <!-- Password Confirmation field -->
                    <div class="space-y-1">
                        <label for="contraseña_confirmation" class="text-xs font-bold text-slate-700">Confirmar Contraseña *</label>
                        <input type="password" name="contraseña_confirmation" id="contraseña_confirmation" required
                            class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-xs font-semibold text-slate-800"
                            placeholder="Repita su contraseña">
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-[#c1121f] hover:bg-[#a80f1a] text-white py-3 px-4 rounded-xl font-bold text-xs shadow-lg hover:shadow-xl transition-all flex items-center justify-center space-x-2 mt-6">
                    <i class="fa-solid fa-user-plus mr-1"></i>
                    <span>Completar Registro e Ingresar</span>
                </button>
            </form>

            <div class="text-center mt-6">
                <span class="text-xs text-slate-400 font-semibold">¿Ya tienes una cuenta?</span>
                <a href="{{ route('login') }}" class="text-xs font-bold text-[#002855] hover:underline ml-1">Inicia sesión</a>
            </div>

        </div>
    </div>

</body>
</html>
