
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Crear Cuenta — ProductosPro</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            --color-primary: #2563eb;
            --color-accent: #0891b2;
            --color-accent-hover: #0e7490;
            --color-dark: #0b1220;
            --color-slate: #334155;
            --color-muted: #64748b;
            --color-border: #dbe5f0;
            --color-bg: #f1f5f9;
        }

        /* Restablecimiento general */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            min-height: 100%;
            overflow-x: hidden;
        }

        body {
            min-height: 100vh;
            width: 100%;
            background:
                radial-gradient(ellipse at 12% 8%, rgba(59, 130, 246, 0.13), transparent 34rem),
                radial-gradient(ellipse at 90% 88%, rgba(6, 182, 212, 0.12), transparent 30rem),
                var(--color-bg);
            color: var(--color-dark);
            font-family: var(--font-family);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
            overflow-x: hidden;
        }

        button,
        input {
            font: inherit;
        }

        /* Contenedor principal */
        .register-container {
            width: 100%;
            max-width: 520px;
            min-width: 0;
            margin: auto;
        }

        .register-card {
            width: 100%;
            min-width: 0;
            background-color: #ffffff;
            border: 1px solid var(--color-border);
            border-radius: 18px;
            padding: 36px;
            box-shadow: 0 18px 48px rgba(15, 23, 42, 0.10), 0 2px 8px rgba(37, 99, 235, 0.04);
            overflow-wrap: anywhere;
        }

        /* Marca */
        .brand-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            color: var(--color-dark);
            margin-bottom: 24px;
        }

        .brand-icon-box {
            flex: 0 0 42px;
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

        .brand-title {
            font-size: 1.4rem;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .brand-title span {
            color: var(--color-primary);
        }

        /* Encabezado */
        .register-heading {
            text-align: center;
            margin-bottom: 26px;
        }

        .register-heading h2 {
            font-size: 1.65rem;
            font-weight: 800;
            color: var(--color-dark);
            margin-bottom: 8px;
            letter-spacing: -0.6px;
        }

        .register-heading p {
            font-size: 0.9rem;
            line-height: 1.6;
            color: var(--color-muted);
            margin: 0;
        }

        /* Campos del formulario */
        .field-group {
            width: 100%;
            min-width: 0;
            margin-bottom: 17px;
        }

        .field-label {
            display: block;
            font-size: 0.84rem;
            font-weight: 700;
            line-height: 1.5;
            color: var(--color-slate);
            margin-bottom: 7px;
        }

        .input-wrapper-custom {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
            min-width: 0;
        }

        .input-icon-left {
            position: absolute;
            left: 14px;
            color: #94a3b8;
            font-size: 1.05rem;
            pointer-events: none;
            z-index: 1;
        }

        .input-control-custom {
            display: block;
            width: 100%;
            min-width: 0;
            max-width: 100%;
            height: 47px;
            padding: 10px 12px 10px 42px;
            font-size: 0.88rem;
            font-weight: 500;
            line-height: 1.4;
            color: var(--color-dark);
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .input-control-custom::placeholder {
            color: #94a3b8;
            opacity: 1;
            font-size: 0.82rem;
        }

        .input-control-custom:hover {
            border-color: #94a3b8;
        }

        .input-control-custom:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .input-control-custom.is-invalid {
            border-color: #dc2626;
            background-color: #fef2f2;
        }

        .input-control-custom.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.10);
        }

        .field-error-text {
            color: #dc2626;
            font-size: 0.79rem;
            font-weight: 500;
            line-height: 1.5;
            margin-top: 6px;
            display: flex;
            align-items: flex-start;
            gap: 5px;
        }

        .field-error-text i {
            margin-top: 2px;
            flex-shrink: 0;
        }

        /* Contraseñas: columnas equilibradas */
        .two-cols-row {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            align-items: start;
            gap: 12px;
        }

        .two-cols-row .field-group {
            min-width: 0;
        }

        .field-hint {
            display: block;
            margin-top: 6px;
            color: var(--color-muted);
            font-size: 0.74rem;
            line-height: 1.45;
        }

        .verification-hint {
            display: flex;
            align-items: flex-start;
            gap: 6px;
            margin-top: 6px;
            color: #64748b;
            font-size: 0.74rem;
            line-height: 1.45;
        }

        .verification-hint i {
            flex: 0 0 auto;
            color: #0284c7;
            margin-top: 1px;
        }

        /* Botón de registro */
        .btn-submit-register {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            width: 100%;
            max-width: 100%;
            min-height: 49px;
            padding: 12px 16px;
            margin-top: 8px;
            background: linear-gradient(135deg, #0891b2, #2563eb);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 0.93rem;
            font-weight: 800;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
        }

        .btn-submit-register:hover {
            background: linear-gradient(135deg, #0e7490, #1d4ed8);
            box-shadow: 0 8px 18px rgba(37, 99, 235, 0.22);
        }

        .btn-submit-register:active {
            transform: scale(0.99);
        }

        .btn-submit-register:focus-visible,
        .btn-sso:focus-visible {
            outline: 3px solid rgba(37, 99, 235, 0.25);
            outline-offset: 3px;
        }

        /* Registro con proveedores externos */
        .sso-container {
            display: grid;
            gap: 10px;
            width: 100%;
            margin-bottom: 4px;
        }

        .btn-sso {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            min-width: 0;
            min-height: 47px;
            padding: 11px 14px;
            background-color: #ffffff;
            border: 1px solid var(--color-border);
            border-radius: 10px;
            font-size: 0.88rem;
            font-weight: 700;
            line-height: 1.4;
            color: var(--color-slate);
            cursor: pointer;
            transition: background-color 0.2s, border-color 0.2s, box-shadow 0.2s;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        }

        .btn-sso:hover {
            background-color: #f8fafc;
            border-color: #cbd5e1;
            color: var(--color-dark);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.06);
        }

        .btn-sso svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        /* Separador */
        .divider-box {
            display: flex;
            align-items: center;
            width: 100%;
            margin: 20px 0;
            gap: 12px;
        }

        .divider-box::before,
        .divider-box::after {
            content: '';
            flex: 1;
            min-width: 0;
            height: 1px;
            background-color: var(--color-border);
        }

        .divider-text {
            flex-shrink: 0;
            font-size: 0.74rem;
            color: var(--color-muted);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Enlace de inicio de sesión */
        .login-prompt {
            text-align: center;
            margin-top: 22px;
            font-size: 0.84rem;
            line-height: 1.7;
            color: var(--color-muted);
        }

        .login-prompt a {
            color: var(--color-primary);
            font-weight: 800;
            text-decoration: none;
            white-space: normal;
        }

        .login-prompt a:hover {
            text-decoration: underline;
        }

        /* Mensajes de Firebase */
        #firebase-alert:not(.d-none) {
            display: block;
            margin-bottom: 16px;
            overflow-wrap: anywhere;
        }

        /* Adaptación a celulares */
        @media (max-width: 520px) {
            body {
                align-items: flex-start;
                padding: 16px 12px;
            }

            .register-container {
                max-width: 100%;
            }

            .register-card {
                padding: 27px 20px;
                border-radius: 15px;
            }

            .brand-header {
                margin-bottom: 20px;
            }

            .register-heading {
                margin-bottom: 22px;
            }

            .register-heading h2 {
                font-size: 1.45rem;
            }

            .register-heading p {
                font-size: 0.85rem;
            }

            .two-cols-row {
                grid-template-columns: minmax(0, 1fr);
                gap: 0;
            }

            .field-label {
                line-height: 1.5;
            }

        }

        /* Celulares pequeños */
        @media (max-width: 360px) {
            body {
                padding: 10px 8px;
            }

            .register-card {
                padding: 23px 15px;
            }

            .brand-title {
                font-size: 1.25rem;
            }

            .register-heading h2 {
                font-size: 1.35rem;
            }

            .input-control-custom {
                font-size: 0.84rem;
                padding-left: 38px;
            }

            .input-icon-left {
                left: 12px;
            }

            .divider-text {
                font-size: 0.68rem;
            }
        }

        /* Pantallas con poca altura */
        @media (max-height: 700px) and (min-width: 521px) {
            body {
                align-items: flex-start;
                padding-top: 16px;
                padding-bottom: 16px;
            }

            .register-card {
                padding-top: 27px;
                padding-bottom: 27px;
            }
        }

        /* Accesibilidad: reducir animaciones */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>

<body>

    <main class="register-container">
        <section class="register-card" aria-labelledby="register-title">

            <!-- Marca -->
            <a href="{{ url('/') }}" class="brand-header" aria-label="ProductosPro, página principal">
                <div class="brand-icon-box">
                    <i class="bi bi-cpu-fill" aria-hidden="true"></i>
                </div>
                <h1 class="brand-title">Productos<span>Pro</span></h1>
            </a>

            <!-- Encabezado -->
            <div class="register-heading">
                <h2 id="register-title">Crear Cuenta</h2>
                <p>Ingresa tus datos para registrarte en la plataforma.</p>
            </div>

            @if(config('services.firebase.api_key') && config('services.firebase.auth_domain') && config('services.firebase.project_id'))
            <div class="sso-container">
                <button
                    type="button"
                    class="btn-sso"
                    id="btn-sso-google"
                    title="Registrarse con Google">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17z"/>
                        <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.27v3.15C3.25 21.37 7.31 24 12 24z"/>
                        <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.27C.46 8.2 0 10.04 0 12s.46 3.8 1.27 5.42l4.01-3.15z"/>
                        <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.31 0 3.25 2.63 1.27 6.58l4.01 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
                    </svg>
                    <span>Registrarse con Google</span>
                </button>
            </div>
            @endif

            <!-- Separador -->
            <div class="divider-box" aria-hidden="true">
                <span class="divider-text">o con tu correo</span>
            </div>

            <!-- Mensajes de Firebase -->
            <div id="firebase-alert" class="d-none" role="alert" aria-live="polite"></div>

            <!-- Formulario -->
            <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
                @csrf

                <!-- Nombre completo -->
                <div class="field-group">
                    <label for="names" class="field-label">Nombre Completo</label>

                    <div class="input-wrapper-custom">
                        <i class="bi bi-person input-icon-left" aria-hidden="true"></i>
                        <input
                            id="names"
                            type="text"
                            name="names"
                            class="input-control-custom @error('names') is-invalid @enderror"
                            value="{{ old('names') }}"
                            autocomplete="name"
                            required
                            autofocus
                            placeholder="Ej. Juan Pérez"
                            aria-describedby="@error('names') names-error @enderror">
                    </div>

                    @error('names')
                        <div class="field-error-text" id="names-error">
                            <i class="bi bi-exclamation-circle" aria-hidden="true"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Correo electrónico -->
                <div class="field-group">
                    <label for="email" class="field-label">Correo Electrónico</label>

                    <div class="input-wrapper-custom">
                        <i class="bi bi-envelope input-icon-left" aria-hidden="true"></i>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            class="input-control-custom @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            required
                            placeholder="tu-correo@ejemplo.com"
                            aria-describedby="@error('email') email-error @enderror">
                    </div>

                    <small class="verification-hint" id="email-verification-hint">
                        <i class="bi bi-envelope-check" aria-hidden="true"></i>
                        <span>Te enviaremos un código para verificar tu correo.</span>
                    </small>

                    @error('email')
                        <div class="field-error-text" id="email-error">
                            <i class="bi bi-exclamation-circle" aria-hidden="true"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Nombre de usuario -->
                <div class="field-group">
                    <label for="user_name" class="field-label">Nombre de Usuario (máx. 15 caracteres)</label>

                    <div class="input-wrapper-custom">
                        <i class="bi bi-at input-icon-left" aria-hidden="true"></i>
                        <input
                            id="user_name"
                            type="text"
                            name="user_name"
                            class="input-control-custom @error('user_name') is-invalid @enderror"
                            value="{{ old('user_name') }}"
                            autocomplete="username"
                            required
                            maxlength="15"
                            placeholder="juanperez"
                            aria-describedby="@error('user_name') user-name-error @enderror">
                    </div>

                    @error('user_name')
                        <div class="field-error-text" id="user-name-error">
                            <i class="bi bi-exclamation-circle" aria-hidden="true"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Contraseñas -->
                <div class="two-cols-row">
                    <div class="field-group">
                        <label for="password" class="field-label">Contraseña</label>

                        <div class="input-wrapper-custom">
                            <i class="bi bi-lock input-icon-left" aria-hidden="true"></i>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="input-control-custom @error('password') is-invalid @enderror"
                                autocomplete="new-password"
                                required
                                minlength="8"
                                placeholder="Crea una contraseña"
                                aria-describedby="password-hint">
                        </div>
                        <small class="field-hint" id="password-hint">8+ caracteres, mayúscula, minúscula, número y símbolo.</small>
                        @error('password')
                            <div class="field-error-text" id="password-error">
                                <i class="bi bi-exclamation-circle" aria-hidden="true"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label for="password_confirmation" class="field-label">Confirmar Contraseña</label>

                        <div class="input-wrapper-custom">
                            <i class="bi bi-shield-check input-icon-left" aria-hidden="true"></i>
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                class="input-control-custom"
                                autocomplete="new-password"
                                required
                                minlength="8"
                                placeholder="Repite la clave">
                        </div>
                    </div>
                </div>

                <!-- Botón de registro -->
                <button type="submit" class="btn-submit-register" id="btn-submit-register">
                    <span>Crear cuenta</span>
                    <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </button>
            </form>

            <!-- Acceso al inicio de sesión -->
            <div class="login-prompt">
                ¿Ya tienes una cuenta registrada?
                <a href="{{ route('login') }}">Inicia sesión aquí</a>
            </div>

        </section>
    </main>

    <!-- Scripts de Firebase existentes -->
    @include('auth._firebase-scripts')

</body>
</html>
