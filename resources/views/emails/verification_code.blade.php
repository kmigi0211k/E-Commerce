<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Código de Verificación — ProductosPro</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #1e293b;
        }
        .email-container {
            max-width: 560px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .header {
            background-color: #0f2942;
            padding: 28px 32px;
            text-align: center;
        }
        .brand-title {
            color: #ffffff;
            font-size: 24px;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .brand-title span {
            color: #34d399;
        }
        .body-content {
            padding: 36px 32px;
        }
        h2 {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 0;
            margin-bottom: 12px;
        }
        p {
            font-size: 15px;
            line-height: 1.6;
            color: #475569;
            margin: 0 0 20px;
        }
        .code-box {
            background-color: #f0fdf4;
            border: 2px dashed #059669;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            margin: 28px 0;
        }
        .code-label {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #059669;
            margin-bottom: 8px;
        }
        .code-value {
            font-size: 38px;
            font-weight: 800;
            letter-spacing: 8px;
            color: #0f172a;
            font-family: 'Courier New', Courier, monospace;
        }
        .expiry-note {
            font-size: 13px;
            color: #64748b;
            margin-top: 8px;
        }
        .footer {
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 20px 32px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
        }
    </style>
</head>
<body>

<div class="email-container">
    <div class="header">
        <div class="brand-title">Productos<span>Pro</span></div>
    </div>

    <div class="body-content">
        <h2>Hola, {{ $userName }} 👋</h2>
        <p>Gracias por unirte a <strong>ProductosPro</strong>. Para completar tu registro y activar tu cuenta en la plataforma, por favor utiliza el siguiente código de verificación:</p>

        <div class="code-box">
            <div class="code-label">Tu Código de Seguridad</div>
            <div class="code-value">{{ $code }}</div>
            <div class="expiry-note">Este código expira en 15 minutos</div>
        </div>

        <p>Si no has solicitado este registro, puedes ignorar este correo de forma segura. Nadie puede acceder a tu cuenta sin este código.</p>
    </div>

    <div class="footer">
        © {{ date('Y') }} ProductosPro · Sistema de Comercio y Voluntariado Solidario.<br>
        Este es un mensaje automático de seguridad, por favor no respondas a este correo.
    </div>
</div>

</body>
</html>
