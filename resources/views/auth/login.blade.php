<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - CUP UAGRM</title>
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
    <div class="hidden md:flex md:w-7/12 bg-gradient-to-br from-[#002855] via-[#001f3f] to-[#0d3b66] p-16 flex-col justify-between relative overflow-hidden">
        
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
                <p class="text-xs text-blue-200 font-semibold tracking-wide uppercase leading-tight">EN CIENCIAS DE LA COMPUTACIÓN Y TELECOMUNICACIONES</p>
                <span class="text-[10px] font-bold text-red-500 uppercase tracking-widest">U A G R M</span>
            </div>
        </div>

        <!-- Banner Content -->
        <div class="max-w-xl my-auto z-10">
            <h1 class="text-4xl lg:text-5xl font-extrabold text-white leading-tight">
                Sistema de registro <br>para el Examen <br>
                <span class="text-[#c1121f] text-stroke">de Admisión CUP</span>
            </h1>
            <p class="mt-6 text-blue-100 text-sm leading-relaxed font-medium">
                Plataforma oficial para el registro de postulantes al Examen de Admisión CUP. Completa tu inscripción y da el primer paso hacia tu futuro profesional.
            </p>
        </div>

        <!-- Wave bottom color crimson red -->
        <div class="absolute bottom-0 left-0 right-0 h-4 bg-[#c1121f] z-0"></div>

        <!-- Circular Icons / Bottom Links (Exactly matching the mockup!) -->
        <div class="grid grid-cols-3 gap-6 mt-8 z-10 border-t border-blue-900/40 pt-8">
            <a href="#" class="flex flex-col items-center text-center group">
                <div class="w-12 h-12 bg-blue-700/30 group-hover:bg-blue-600/50 rounded-full flex items-center justify-center text-white text-lg transition-all border border-blue-600/30">
                    <i class="fa-solid fa-globe"></i>
                </div>
                <span class="text-[10px] text-blue-100 font-semibold mt-2">Sitio Web<br>Institucional</span>
            </a>
            <a href="#" class="flex flex-col items-center text-center group">
                <div class="w-12 h-12 bg-red-600/30 group-hover:bg-[#c1121f]/50 rounded-full flex items-center justify-center text-white text-lg transition-all border border-red-600/30">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <span class="text-[10px] text-blue-100 font-semibold mt-2">Información del<br>Examen CUP</span>
            </a>
            <a href="#" class="flex flex-col items-center text-center group">
                <div class="w-12 h-12 bg-blue-700/30 group-hover:bg-blue-600/50 rounded-full flex items-center justify-center text-white text-lg transition-all border border-blue-600/30">
                    <i class="fa-solid fa-question"></i>
                </div>
                <span class="text-[10px] text-blue-100 font-semibold mt-2">Preguntas<br>Frecuentes</span>
            </a>
        </div>
    </div>

    <!-- RIGHT SIDE (Pristine Card Login Form) -->
    <div class="w-full md:w-5/12 bg-[#001f3f] md:bg-white flex items-center justify-center p-8 md:p-12 overflow-y-auto">
        <div class="w-full max-w-md bg-white rounded-3xl p-8 md:p-0 shadow-2xl md:shadow-none flex flex-col justify-between min-h-[500px]">
            
            <!-- Illustration & Form Header (mockup-identical layout!) -->
            <div class="text-center">
                <!-- Laptop illustration using FontAwesome mockup style -->
                <div class="mx-auto w-32 h-24 flex items-center justify-center relative mb-4">
                    <i class="fa-solid fa-laptop text-6xl text-blue-600"></i>
                    <i class="fa-solid fa-graduation-cap text-2xl text-red-500 absolute top-2 right-4"></i>
                    <i class="fa-solid fa-wifi text-xl text-blue-400 absolute top-0 left-4"></i>
                </div>
                <h2 class="text-2xl font-extrabold text-slate-800">Iniciar sesión</h2>
                <p class="text-xs text-slate-400 font-semibold mt-1">Accede a tu cuenta para continuar</p>
            </div>

            <!-- Form -->
            <form action="{{ route('login') }}" method="POST" class="mt-8 space-y-6">
                @csrf

                <!-- Custom inline error alert -->
                @if($errors->any())
                    <div class="p-3 bg-red-50 border border-red-100 text-red-600 text-xs rounded-xl font-medium">
                        <i class="fa-solid fa-circle-exclamation mr-1"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                @if(session('status'))
                    <div class="p-3 bg-emerald-50 border border-emerald-100 text-emerald-600 text-xs rounded-xl font-medium">
                        <i class="fa-solid fa-circle-check mr-1"></i>
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Email field -->
                <div class="space-y-1.5">
                    <label for="correo" class="text-xs font-bold text-slate-700">Correo institucional</label>
                    <div class="relative rounded-2xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <input type="email" name="correo" id="correo" required value="{{ old('correo') }}"
                            class="block w-full pl-11 pr-4 py-3.5 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-sm placeholder-slate-400 text-slate-800 font-semibold"
                            placeholder="Ingresa tu correo institucional">
                    </div>
                </div>

                <!-- Password field -->
                <div class="space-y-1.5">
                    <div class="flex justify-between items-center">
                        <label for="contraseña" class="text-xs font-bold text-slate-700">Contraseña</label>
                    </div>
                    <div class="relative rounded-2xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" name="contraseña" id="contraseña" required
                            class="block w-full pl-11 pr-12 py-3.5 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-sm placeholder-slate-400 text-slate-800 font-semibold"
                            placeholder="Ingresa tu contraseña">
                        <!-- Eye Icon to toggle visibility -->
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600">
                            <i class="fa-regular fa-eye-slash" id="password-eye"></i>
                        </button>
                    </div>
                    <div class="text-right mt-1">
                        <a href="{{ route('password.request') }}" class="text-xs font-bold text-blue-600 hover:underline">¿Olvidaste tu contraseña?</a>
                    </div>
                </div>

                <!-- Log In Button -->
                <button type="submit" class="w-full bg-[#002855] hover:bg-[#001f3f] text-white py-3.5 px-4 rounded-2xl font-bold text-sm shadow-lg hover:shadow-xl transition-all flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-right-to-bracket mr-1"></i>
                    <span>Ingresar</span>
                </button>
            </form>

            <!-- SignUp redirection link -->
            <div class="text-center mt-4">
                <span class="text-xs text-slate-400 font-semibold">¿Eres un nuevo postulante?</span>
                <a href="{{ route('register') }}" class="text-xs font-bold text-red-600 hover:underline ml-1">Regístrate aquí</a>
            </div>

            <!-- Footer Card Note -->
            <div class="mt-8 border-t border-slate-100 pt-6 flex items-center space-x-3 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-sm flex-shrink-0">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <p class="text-[10px] text-slate-500 font-semibold leading-normal">
                    Por tu seguridad, cierra sesión al finalizar y no compartas tus credenciales.
                </p>
            </div>

        </div>
    </div>

    <!-- Password visibility toggle script -->
    <script>
        function togglePassword() {
            var passInput = document.getElementById("contraseña");
            var eyeIcon = document.getElementById("password-eye");
            if (passInput.type === "password") {
                passInput.type = "text";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            } else {
                passInput.type = "password";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            }
        }
    </script>

</body>
</html>
