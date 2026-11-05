<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ProductosPro') }} - Acceso seguro</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
                background:
                    radial-gradient(ellipse at 8% 4%, rgba(59, 130, 246, 0.12), transparent 34rem),
                    radial-gradient(ellipse at 92% 18%, rgba(6, 182, 212, 0.11), transparent 30rem),
                    #f1f5f9;
            }

            .guest-shell {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 2rem 1rem;
            }

            .guest-brand {
                display: inline-flex;
                align-items: center;
                gap: 0.75rem;
                margin-bottom: 1.5rem;
                color: #0b1220;
                font-size: 1.35rem;
                font-weight: 800;
                text-decoration: none;
            }

            .guest-brand-icon {
                width: 2.75rem;
                height: 2.75rem;
                display: grid;
                place-items: center;
                border-radius: 0.9rem;
                background: linear-gradient(135deg, #38bdf8, #2563eb);
                color: #fff;
                box-shadow: 0 8px 20px rgba(37, 99, 235, 0.24);
            }

            .guest-brand span { color: #0891b2; }

            .guest-card {
                width: 100%;
                max-width: 30rem;
                padding: clamp(1.5rem, 5vw, 2.5rem);
                border: 1px solid #dbe5f0;
                border-radius: 1.5rem;
                background: rgba(255, 255, 255, 0.96);
                box-shadow: 0 24px 60px rgba(15, 23, 42, 0.12);
            }

            .guest-card input {
                border-radius: 0.75rem;
            }

            .guest-card input:focus {
                border-color: #38bdf8;
                box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.12);
            }

            .guest-card button[type="submit"] {
                border: 0;
                border-radius: 999px;
                background: linear-gradient(135deg, #0891b2, #2563eb);
                color: #fff;
                font-weight: 700;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <main class="guest-shell">
            <a href="{{ url('/') }}" class="guest-brand" aria-label="ProductosPro, página principal">
                <span class="guest-brand-icon"><i class="bi bi-cpu-fill"></i></span>
                <span>Productos<span>Pro</span></span>
            </a>
            <div class="guest-card">
                {{ $slot }}
            </div>
        </main>
    </body>
</html>
