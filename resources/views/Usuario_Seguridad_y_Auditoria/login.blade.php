<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Iniciar sesión - CUP FICCT UAGRM</title>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600&display=swap" rel="stylesheet">
<style>
body, html {
    margin: 0;
    padding: 0;
    height: 100%;
    font-family: 'Outfit', sans-serif;
    background: url('imagenes/Fondo.png') no-repeat center center fixed;
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: center;
}
.login-card {
    background-color: rgba(255,255,255,0.95);
    border-radius: 20px;
    padding: 40px;
    max-width: 450px;
    width: 100%;
    box-shadow: 0 15px 30px rgba(0,0,0,0.2);
    text-align: center;
}
.login-card img.logo {
    width: 120px;
    margin-bottom: 20px;
}
.login-card h1 {
    font-size: 28px;
    margin-bottom: 5px;
}
.login-card h1 span.cup { color: #0b1d4a; font-weight: bold; }
.login-card h1 span.ficct { color: #e31c3d; font-weight: bold; }
.login-card h1 span.uagrm { color: #0b1d4a; font-weight: bold; }
.login-card p {
    color: #000;
    margin-bottom: 25px;
    font-size: 14px;
}
.login-card form {
    display: flex;
    flex-direction: column;
}
.login-card input {
    padding: 14px;
    margin-bottom: 15px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
    width: 100%;
}
.login-card button {
    padding: 14px;
    background-color: #0b1d4a;
    color: #fff;
    font-weight: 600;
    font-size: 16px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s;
}
.login-card button:hover {
    background-color: #061339;
}
.login-card a.forgot {
    text-align: right;
    font-size: 13px;
    margin-bottom: 15px;
    color: #0b1d4a;
    text-decoration: none;
}
.login-card a.forgot:hover {
    text-decoration: underline;
}
.alert-success {
    background: #e6ffed;
    color: #046a38;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 10px;
}
.alert-error {
    background: #ffe6e6;
    color: #a61111;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 10px;
}
@media(max-width: 500px){
    .login-card { padding: 30px; }
    .login-card img.logo { width: 100px; }
}
</style>
</head>
<body>

<div class="login-card">
    <img class="logo" src="{{ asset('imagenes/logo_FICCT.png') }}" alt="Logo FICCT">
    <h1><span class="cup">CUP</span> <span class="ficct">FICCT</span> <span class="uagrm">UAGRM</span></h1>
    <p>Facultad de Ingeniería en Ciencias de la Computación y Telecomunicaciones</p>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert-error">{{ $errors->first() }}</div>
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