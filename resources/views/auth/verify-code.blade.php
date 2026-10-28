<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verificar Código — ProductosPro</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            --color-primary: #1e3a8a;
            --color-accent: #059669;
            --color-accent-hover: #047857;
            --color-dark: #0f172a;
            --color-slate: #334155;
            --color-muted: #64748b;
            --color-border: #e2e8f0;
            --color-bg: #f8fafc;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: var(--font-family);
        }

        body {
            min-height: 100vh;
            background-color: var(--color-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 20px;
        }

        .verify-card {
            background-color: #ffffff;
            border: 1px solid var(--color-border);
            border-radius: 16px;
            padding: 44px 40px;
            max-width: 480px;
            width: 100%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
            text-decoration: none;
            color: var(--color-dark);
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background-color: var(--color-accent);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .brand-name {
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .brand-name span {
            color: #059669;
        }

        .mail-icon-circle {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background-color: #ecfdf5;
            color: #059669;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 20px;
            border: 2px solid #a7f3d0;
        }

        .verify-title {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--color-dark);
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .verify-desc {
            font-size: 0.92rem;
            color: var(--color-muted);
            line-height: 1.5;
            margin-bottom: 24px;
        }

        .target-email {
            font-weight: 700;
            color: var(--color-dark);
            word-break: break-all;
        }

        /* Demo Helper Card para facilitar sustentación */
        .demo-box {
            background-color: #f1f5f9;
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.85rem;
            color: #334155;
            text-align: left;
        }

        .demo-box strong {
            font-family: 'Courier New', Courier, monospace;
            font-size: 1.1rem;
            letter-spacing: 2px;
            color: #059669;
        }

        .btn-fill-code {
            background-color: #e2e8f0;
            border: none;
            color: #0f172a;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-fill-code:hover {
            background-color: #cbd5e1;
        }

        /* Input de código 6 dígitos */
        .otp-input-wrapper {
            margin: 24px 0 16px;
        }

        .otp-input {
            width: 100%;
            height: 56px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: 12px;
            text-align: center;
            color: var(--color-dark);
            background-color: #ffffff;
            border: 2px solid #cbd5e1;
            border-radius: 12px;
            transition: all 0.2s;
        }

        .otp-input:focus {
            outline: none;
            border-color: var(--color-accent);
            box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.12);
        }

        .otp-input.is-invalid {
            border-color: #dc2626;
            background-color: #fef2f2;
        }

        .btn-verify-submit {
            width: 100%;
            height: 48px;
            background-color: var(--color-accent);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 12px;
        }

        .btn-verify-submit:hover {
            background-color: var(--color-accent-hover);
        }

        .resend-box {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid var(--color-border);
            font-size: 0.88rem;
            color: var(--color-muted);
        }

        .btn-resend-link {
            background: none;
            border: none;
            color: var(--color-accent);
            font-weight: 700;
            padding: 0;
            cursor: pointer;
            text-decoration: underline;
        }

        .btn-resend-link:hover {
            color: var(--color-accent-hover);
        }
    </style>
</head>
<body>

<div class="verify-card">

    <a href="{{ url('/') }}" class="brand-badge">
        <div class="brand-icon">
            <i class="bi bi-box-seam-fill"></i>
        </div>
        <div class="brand-name">Productos<span>Pro</span></div>
    </a>

    <div class="mail-icon-circle">
        <i class="bi bi-envelope-check"></i>
    </div>

    <h1 class="verify-title">Verifica tu Correo</h1>
    <p class="verify-desc">
        Hemos enviado un código de verificación de 6 dígitos a:<br>
        <span class="target-email">{{ $email }}</span>
    </p>

    <!-- Alertas -->
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center mb-3 text-start small py-2 px-3 rounded-2">
            <i class="bi bi-check-circle-fill me-2 fs-6"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center mb-3 text-start small py-2 px-3 rounded-2">
            <i class="bi bi-exclamation-octagon-fill me-2 fs-6"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <!-- Formulario de ingreso de código -->
    <form method="POST" action="{{ route('verification.code.verify') }}" id="verifyForm">
        @csrf

        <div class="otp-input-wrapper">
            <input
                type="text"
                name="code"
                id="otpCode"
                class="otp-input @error('code') is-invalid @enderror"
                maxlength="6"
                placeholder="000000"
                autocomplete="one-time-code"
                inputmode="numeric"
                pattern="[0-9]{6}"
                required
                autofocus>
            
            @error('code')
                <div class="text-danger small mt-2 fw-semibold">
                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="btn-verify-submit" id="btn-verify-submit">
            <i class="bi bi-check2-circle fs-5"></i>
            <span>Verificar y Activar Cuenta</span>
        </button>
    </form>

    <!-- Reenviar código -->
    <div class="resend-box">
        ¿No recibiste el correo?
        <form method="POST" action="{{ route('verification.code.resend') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn-resend-link">Reenviar código</button>
        </form>
    </div>

</div>

<script>
    // 1. Filtrar solo dígitos numéricos en el input
    const otpInput = document.getElementById('otpCode');
    if (otpInput) {
        otpInput.addEventListener('input', function (e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
        });
    }
</script>

</body>
</html>
