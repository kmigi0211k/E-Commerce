<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar Sesión — ProductosPro</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Normalización de IP local a localhost para compatibilidad con Google reCAPTCHA y Firebase OAuth -->
    <script>
        if (window.location.hostname === '127.0.0.1') {
            const port = window.location.port ? ':' + window.location.port : '';
            window.location.replace(window.location.protocol + '//localhost' + port + window.location.pathname + window.location.search + window.location.hash);
        }
    </script>

    <!-- Google reCAPTCHA v2 -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
        :root {
            --font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            --color-primary: #2563eb;
            --color-primary-hover: #1d4ed8;
            --color-accent: #0891b2;
            --color-accent-hover: #0e7490;
            --color-dark: #0b1220;
            --color-slate: #334155;
            --color-muted: #64748b;
            --color-light-bg: #f1f5f9;
            --color-border: #dbe5f0;
            --color-card-bg: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: var(--font-family);
        }

        body {
            min-height: 100vh;
            background:
                radial-gradient(ellipse at 12% 8%, rgba(59, 130, 246, 0.13), transparent 34rem),
                radial-gradient(ellipse at 90% 88%, rgba(6, 182, 212, 0.12), transparent 30rem),
                #f1f5f9;
            color: var(--color-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 20px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 460px;
            margin: auto;
        }

        /* Card */
        .login-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid var(--color-border);
            box-shadow: 0 18px 48px rgba(15, 23, 42, 0.10), 0 2px 8px rgba(37, 99, 235, 0.04);
            padding: 38px 34px;
        }

        /* Brand header */
        .login-brand-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            color: var(--color-dark);
            margin-bottom: 20px;
        }

        .login-brand-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #06b6d4, #2563eb);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        .login-brand-name {
            font-size: 1.45rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--color-dark);
            margin: 0;
        }

        .login-brand-name span {
            color: var(--color-primary);
        }

        .form-heading {
            margin-bottom: 22px;
            text-align: center;
        }

        .form-heading h2 {
            font-size: 1.55rem;
            font-weight: 800;
            color: var(--color-dark);
            margin-bottom: 4px;
            letter-spacing: -0.5px;
        }

        .form-heading p {
            font-size: 0.9rem;
            color: var(--color-muted);
            margin: 0;
        }

        /* Acceso SSO con Google */
        .sso-container {
            display: grid;
            gap: 10px;
            margin-bottom: 20px;
        }

        .btn-sso {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 11px 16px;
            background-color: #ffffff;
            border: 1px solid var(--color-border);
            border-radius: 10px;
            font-size: 0.92rem;
            font-weight: 600;
            color: var(--color-slate);
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .btn-sso:hover {
            background-color: #f8fafc;
            border-color: #cbd5e1;
            color: var(--color-dark);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            transform: translateY(-1px);
        }

        .btn-sso svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .divider-box {
            display: flex;
            align-items: center;
            margin: 20px 0;
            gap: 12px;
        }

        .divider-box::before,
        .divider-box::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: var(--color-border);
        }

        .divider-text {
            font-size: 0.78rem;
            color: var(--color-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Campos de texto */
        .field-group {
            margin-bottom: 18px;
        }

        .field-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--color-slate);
            margin-bottom: 6px;
        }

        .input-wrapper-custom {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon-left {
            position: absolute;
            left: 14px;
            color: #94a3b8;
            font-size: 1.05rem;
            pointer-events: none;
        }

        .input-control-custom {
            width: 100%;
            height: 46px;
            padding: 10px 14px 10px 42px;
            font-size: 0.92rem;
            font-weight: 500;
            color: var(--color-dark);
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .input-control-custom:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .input-control-custom.is-invalid {
            border-color: #dc2626;
            background-color: #fef2f2;
        }

        .toggle-password-btn {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            color: #94a3b8;
            font-size: 1.1rem;
            cursor: pointer;
            padding: 4px;
        }

        .toggle-password-btn:hover {
            color: var(--color-dark);
        }

        .field-error-text {
            color: #dc2626;
            font-size: 0.8rem;
            font-weight: 500;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* reCAPTCHA container */
        .recaptcha-wrapper {
            margin: 18px 0;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        /* Botón de acceso principal */
        .btn-submit-primary {
            width: 100%;
            height: 48px;
            background: linear-gradient(135deg, #0891b2, #2563eb);
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
            box-shadow: 0 8px 18px rgba(37, 99, 235, 0.18);
            transition: background 0.2s, transform 0.1s, box-shadow 0.2s;
        }

        .btn-submit-primary:hover {
            background: linear-gradient(135deg, #0e7490, #1d4ed8);
            box-shadow: 0 10px 22px rgba(37, 99, 235, 0.25);
        }

        .btn-submit-primary:active {
            transform: translateY(1px);
        }

        .signup-prompt {
            text-align: center;
            margin-top: 24px;
            font-size: 0.88rem;
            color: var(--color-muted);
        }

        .signup-prompt a {
            color: var(--color-primary);
            font-weight: 700;
            text-decoration: none;
        }

        .signup-prompt a:hover {
            text-decoration: underline;
        }

        /* Back to store link */
        .back-link {
            text-align: center;
            margin-top: 16px;
        }

        .back-link a {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            color: var(--color-muted);
            font-size: 0.85rem;
            font-weight: 600;
            transition: color 0.2s;
        }

        .back-link a:hover {
            color: var(--color-primary);
        }

        @media (max-width: 480px) {
            body {
                padding: 20px 14px;
            }
            .login-card {
                padding: 28px 20px;
            }
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    <!-- Login Card -->
    <div class="login-card">

        <!-- Brand Header / Logo -->
        <a href="{{ route('products.index') }}" class="login-brand-link">
            <div class="login-brand-icon">
                <i class="bi bi-cpu-fill"></i>
            </div>
            <h1 class="login-brand-name">Productos<span>Pro</span></h1>
        </a>

        <div class="form-heading">
            <h2>Iniciar Sesión</h2>
            <p>Ingresa tus credenciales para acceder a tu cuenta.</p>
        </div>

        @if(config('services.firebase.api_key') && config('services.firebase.auth_domain') && config('services.firebase.project_id'))
        <div class="sso-container">
            <button type="button" class="btn-sso" id="btn-sso-google" title="Continuar con Google">
                <!-- SVG oficial de Google -->
                <svg viewBox="0 0 24 24" width="20" height="20">
                    <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17z"/>
                    <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.27v3.15C3.25 21.37 7.31 24 12 24z"/>
                    <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.27C.46 8.2 0 10.04 0 12s.46 3.8 1.27 5.42l4.01-3.15z"/>
                    <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.31 0 3.25 2.63 1.27 6.58l4.01 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
                </svg>
                <span>Continuar con Google</span>
            </button>
        </div>

        <div class="divider-box">
            <span class="divider-text">o con tu correo o usuario</span>
        </div>
        @endif

        <!-- Alertas de estado de sesión -->
        @if(session('status'))
            <div class="alert alert-success d-flex align-items-center mb-3 rounded-2 py-2 px-3 small" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-6"></i>
                <div>{{ session('status') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center mb-3 rounded-2 py-2 px-3 small" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-6"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        <!-- Mensajes de Firebase Auth -->
        <div id="firebase-alert" class="d-none" role="alert"></div>

        <!-- Formulario Principal -->
        <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
            @csrf

            <!-- Campo de Nombre de Usuario -->
            <div class="field-group">
                <label for="username" class="field-label">Correo electrónico o usuario</label>
                <div class="input-wrapper-custom">
                    <i class="bi bi-person input-icon-left"></i>
                    <input
                        id="username"
                        type="text"
                        name="user_name"
                        class="input-control-custom @error('user_name') is-invalid @enderror"
                        value="{{ old('user_name') }}"
                        required
                        autofocus
                        placeholder="correo@ejemplo.com o tu usuario"
                        autocomplete="username">
                </div>
                @error('user_name')
                    <div class="field-error-text">
                        <i class="bi bi-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <!-- Campo de Contraseña -->
            <div class="field-group">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label for="password" class="field-label mb-0">Contraseña</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="small text-decoration-none text-muted">¿Olvidaste tu contraseña?</a>
                    @endif
                </div>
                <div class="input-wrapper-custom">
                    <i class="bi bi-lock input-icon-left"></i>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="input-control-custom @error('password') is-invalid @enderror"
                        required
                        placeholder="Ingresa tu contraseña"
                        autocomplete="current-password">
                    <button type="button" class="toggle-password-btn" id="togglePasswordBtn" title="Ver contraseña">
                        <i class="bi bi-eye" id="toggleIcon"></i>
                    </button>
                </div>
                @error('password')
                    <div class="field-error-text">
                        <i class="bi bi-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <!-- Recordarme -->
            <div class="d-flex align-items-center justify-content-between mb-3">
                <label class="d-flex align-items-center gap-2 small text-muted user-select-none" style="cursor: pointer;">
                    <input type="checkbox" name="remember" id="remember" class="form-check-input mt-0" checked>
                    <span>Recordar sesión</span>
                </label>
            </div>

            <!-- Widget de Google reCAPTCHA v2 -->
            <div class="recaptcha-wrapper">
                @if(config('services.recaptcha.site_key') && config('services.recaptcha.secret_key'))
                    <div class="g-recaptcha"
                         data-sitekey="{{ config('services.recaptcha.site_key') }}"
                         data-error-callback="onRecaptchaError"></div>
                @else
                    <div class="alert alert-warning small mb-0" role="status">
                        El acceso requiere configurar las claves de reCAPTCHA. Contacta al administrador.
                    </div>
                @endif
                <div id="recaptcha-client-error" class="field-error-text mt-2 d-none">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span id="recaptcha-client-error-msg"></span>
                </div>
                @error('g-recaptcha-response')
                    <div class="field-error-text mt-2">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <!-- Botón de Envío -->
            <button type="submit" class="btn-submit-primary" id="btn-login-submit">
                <span>Ingresar a la Plataforma</span>
                <i class="bi bi-arrow-right"></i>
            </button>
        </form>

        <div class="signup-prompt">
            ¿No tienes una cuenta registrada?
            <a href="{{ route('register') }}">Crear una cuenta</a>
        </div>

    </div>

    <!-- Link para volver a la tienda -->
    <div class="back-link">
        <a href="{{ route('products.index') }}">
            <i class="bi bi-arrow-left"></i>
            Volver a la tienda
        </a>
    </div>

</div>

<!-- Firebase Auth (configuración, SDK y lógica) -->
@include('auth._firebase-scripts')

<script>
    // 1. Mostrar/ocultar contraseña
    const toggleBtn = document.getElementById('togglePasswordBtn');
    const pwdInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    if (toggleBtn && pwdInput) {
        toggleBtn.addEventListener('click', function () {
            const isPassword = pwdInput.type === 'password';
            pwdInput.type = isPassword ? 'text' : 'password';
            toggleIcon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    }

    // 2. Callback cuando reCAPTCHA falla al inicializarse o verificar el dominio
    function onRecaptchaError() {
        const errBox = document.getElementById('recaptcha-client-error');
        const errMsg = document.getElementById('recaptcha-client-error-msg');
        if (errBox && errMsg) {
            errBox.classList.remove('d-none');
            const host = window.location.hostname;
            const port = window.location.port ? ':' + window.location.port : '';
            const targetUrl = window.location.protocol + '//localhost' + port + window.location.pathname;
            errMsg.innerHTML = 'El dominio <strong>' + host + '</strong> no está autorizado en Google reCAPTCHA. ' +
                'Agrega <strong>' + host + '</strong> en <a href="https://www.google.com/recaptcha/admin" target="_blank" class="text-danger fw-bold text-decoration-underline">Google reCAPTCHA Admin</a> o <a href="' + targetUrl + '" class="text-danger fw-bold text-decoration-underline">haz clic aquí para abrir con localhost</a>.';
        }
    }
</script>

</body>
</html>