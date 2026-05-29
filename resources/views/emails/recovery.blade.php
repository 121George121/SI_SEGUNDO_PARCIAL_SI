<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Recuperación - CUP UAGRM</title>
    <style>
        body {
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 580px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -4px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f5f9;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            background-color: #f0f7ff;
            border-radius: 50%;
            margin-bottom: 16px;
        }
        .logo-icon img {
            width: 32px;
            height: 32px;
        }
        .title {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 8px 0;
            letter-spacing: -0.5px;
        }
        .subtitle {
            font-size: 14px;
            color: #64748b;
            margin: 0;
            font-weight: 500;
        }
        .content {
            font-size: 15px;
            line-height: 1.6;
            color: #334155;
            margin-bottom: 30px;
        }
        .code-box {
            background: linear-gradient(135deg, #002855 0%, #001f3f 100%);
            border-radius: 20px;
            padding: 24px;
            text-align: center;
            margin: 24px 0;
            box-shadow: 0 8px 16px rgba(0, 40, 85, 0.15);
        }
        .code-title {
            color: #93c5fd;
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 1.5px;
            margin-bottom: 8px;
        }
        .code-number {
            color: #ffffff;
            font-size: 36px;
            font-weight: 800;
            letter-spacing: 8px;
            margin: 0;
            font-family: monospace, Courier, monospace;
        }
        .meta-info {
            background-color: #f8fafc;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 30px;
            border: 1px solid #f1f5f9;
        }
        .meta-item {
            font-size: 13px;
            color: #475569;
            margin: 4px 0;
            display: flex;
            justify-content: space-between;
        }
        .meta-item strong {
            color: #0f172a;
        }
        .footer {
            text-align: center;
            border-top: 1px solid #f1f5f9;
            padding-top: 24px;
            font-size: 12px;
            color: #94a3b8;
            line-height: 1.5;
        }
        .footer a {
            color: #002855;
            text-decoration: none;
            font-weight: 600;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .warning-text {
            font-size: 12px;
            color: #ef4444;
            font-weight: 600;
            text-align: center;
            margin-top: 16px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div class="logo-icon">
            <!-- Simple SVG key icon matching original aesthetic -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#002855" width="32" height="32">
                <path d="M12.65 10C11.83 7.67 9.61 6 7 6c-3.31 0-6 2.69-6 6s2.69 6 6 6c2.61 0 4.83-1.67 5.65-4H17v4h4v-4h2v-4H12.65zM7 14c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/>
            </svg>
        </div>
        <h1 class="title">Recuperación de Contraseña</h1>
        <p class="subtitle">CUP - Universidad Autónoma Gabriel René Moreno</p>
    </div>

    <div class="content">
        <p>Hola,</p>
        <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en el sistema <strong>CUP UAGRM</strong>. Utiliza el siguiente código de seguridad de un solo uso para verificar tu identidad:</p>
        
        <div class="code-box">
            <div class="code-title">Código de Verificación</div>
            <div class="code-number">{{ $code }}</div>
        </div>

        <p class="warning-text">
            Este código expirará en 10 minutos por razones de seguridad.
        </p>

        <div class="meta-info">
            <div class="meta-item">
                <span>Solicitado para el correo:</span>
                <strong>{{ $email }}</strong>
            </div>
            <div class="meta-item">
                <span>Fecha y hora:</span>
                <strong>{{ date('d/m/Y H:i:s') }}</strong>
            </div>
        </div>

        <p>Si tú no has solicitado este cambio, por favor ignora este correo electrónico de forma segura o ponte en contacto con soporte si crees que es una actividad sospechosa.</p>
    </div>

    <div class="footer">
        <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
        <p>&copy; {{ date('Y') }} CUP UAGRM. Todos los derechos reservados.</p>
        <p><a href="{{ url('/') }}">Ir al portal del sistema</a></p>
    </div>
</div>

</body>
</html>
