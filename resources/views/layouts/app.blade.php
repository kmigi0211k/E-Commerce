<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Inventario') }} | Sistema de Inventario</title>

        <!-- Google Fonts: Plus Jakarta Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Bootstrap 5 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Global Design System & Custom Color Palette -->
        <style>
            :root {
                --font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
                --brand-primary: #2563eb;
                --brand-primary-hover: #1d4ed8;
                --brand-primary-light: #eff6ff;
                --brand-emerald: #0891b2;
                --brand-emerald-hover: #0e7490;
                --brand-emerald-light: #ecfeff;
                --brand-cyan: #22d3ee;
                --brand-amber: #d97706;
                --brand-dark: #0b1220;
                --brand-slate: #334155;
                --brand-bg: #f1f5f9;
                --card-border: #dbe5f0;
            }

            body {
                font-family: var(--font-sans);
                background:
                    radial-gradient(ellipse at 8% 4%, rgba(59, 130, 246, 0.09), transparent 34rem),
                    radial-gradient(ellipse at 92% 18%, rgba(6, 182, 212, 0.08), transparent 30rem),
                    var(--brand-bg);
                color: #1e293b;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                letter-spacing: -0.01em;
            }

            .btn {
                font-weight: 700;
                transition: transform 0.18s ease, box-shadow 0.18s ease, background-color 0.18s ease;
            }

            .btn-primary,
            .btn-success {
                color: #fff;
                background: linear-gradient(135deg, #0891b2, #2563eb);
                border-color: transparent;
                box-shadow: 0 6px 16px rgba(37, 99, 235, 0.18);
            }

            .btn-primary:hover,
            .btn-success:hover {
                color: #fff;
                background: linear-gradient(135deg, #0e7490, #1d4ed8);
                border-color: transparent;
                transform: translateY(-1px);
                box-shadow: 0 9px 20px rgba(37, 99, 235, 0.24);
            }

            .btn-outline-primary {
                --bs-btn-color: #0369a1;
                --bs-btn-border-color: #7dd3fc;
                --bs-btn-hover-bg: #0284c7;
                --bs-btn-hover-border-color: #0284c7;
                --bs-btn-active-bg: #0369a1;
                --bs-btn-active-border-color: #0369a1;
            }

            .form-control,
            .form-select {
                border-color: #dbe5f0;
                border-radius: 0.75rem;
            }

            .form-control:focus,
            .form-select:focus {
                border-color: #38bdf8;
                box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.12);
            }

            .card {
                border-color: var(--card-border);
                box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
            }

            .table > :not(caption) > * > * {
                border-bottom-color: #e8eef5;
            }

            .table thead th {
                color: #475569;
            }

            .text-primary {
                color: #0369a1 !important;
            }

            /* Professional Navbar */
            .modern-navbar {
                background: linear-gradient(110deg, #0b1220 0%, #10294a 58%, #123d5a 100%);
                border-bottom: 1px solid rgba(125, 211, 252, 0.2);
                box-shadow: 0 8px 24px rgba(15, 23, 42, 0.18);
                padding: 0.75rem 0;
            }

            .navbar-brand-custom {
                display: flex;
                align-items: center;
                gap: 10px;
                text-decoration: none;
            }

            .navbar-brand-logo {
                width: 38px;
                height: 38px;
                border-radius: 12px;
                background: linear-gradient(135deg, #38bdf8, #2563eb);
                display: flex;
                align-items: center;
                justify-content: center;
                color: #ffffff;
                font-size: 1.2rem;
                box-shadow: 0 5px 14px rgba(14, 165, 233, 0.28);
            }

            .navbar-brand-title {
                font-weight: 800;
                font-size: 1.3rem;
                color: #ffffff;
                line-height: 1.1;
                margin: 0;
            }

            .navbar-brand-title span {
                color: #67e8f9;
            }

            .db-tag-badge {
                font-size: 0.72rem;
                font-weight: 600;
                padding: 4px 10px;
                border-radius: 6px;
                background: rgba(255, 255, 255, 0.1);
                color: #e2e8f0;
                border: 1px solid rgba(255, 255, 255, 0.15);
            }

            /* Module Navigation Links */
            .nav-pill-link {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                min-height: 42px;
                padding: 8px 14px;
                border-radius: 10px;
                font-weight: 600;
                font-size: 0.88rem;
                text-decoration: none;
                transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
                border: 1px solid transparent;
                background: rgba(255, 255, 255, 0.05);
                color: #cbd5e1;
                cursor: pointer;
            }

            .nav-pill-link:hover {
                background: rgba(255, 255, 255, 0.12);
                color: #ffffff;
                transform: translateY(-1px);
            }

            .nav-pill-link.active-shop {
                background: linear-gradient(135deg, #0284c7, #2563eb);
                color: #ffffff;
                box-shadow: 0 5px 14px rgba(37, 99, 235, 0.24);
            }

            .btn-icon-soft.active-shop i {
                color: #ffffff !important;
            }

            .nav-pill-link.active-community {
                background-color: #2563eb;
                color: #ffffff;
            }

            .nav-pill-link.active-panel {
                background: linear-gradient(135deg, #0284c7, #2563eb);
                color: #ffffff;
            }

            .navbar-actions,
            .nav-primary-actions,
            .nav-account-actions {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .navbar-actions,
            .nav-account-actions {
                flex-wrap: wrap;
            }

            .nav-account-actions {
                padding-left: 12px;
                margin-left: 4px;
                border-left: 1px solid rgba(148, 163, 184, 0.3);
            }

            .admin-nav-dropdown .dropdown-menu {
                min-width: 230px;
                padding: 8px;
                border: 1px solid #dbe5f0;
                border-radius: 12px;
                box-shadow: 0 14px 30px rgba(15, 23, 42, 0.18);
            }

            .admin-nav-dropdown {
                flex: 0 0 auto;
            }

            #btn-edit-profile .profile-user-name {
                max-width: 120px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .admin-nav-dropdown .dropdown-item {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 10px 12px;
                border-radius: 8px;
                color: #334155;
                font-size: 0.88rem;
                font-weight: 600;
            }

            .admin-nav-dropdown .dropdown-item i {
                width: 18px;
                color: #0369a1;
            }

            .admin-nav-dropdown .dropdown-item:hover,
            .admin-nav-dropdown .dropdown-item.active {
                color: #075985;
                background: #f0f9ff;
            }

            /* Action Buttons & Avatar */
            .btn-icon-soft {
                background: rgba(255, 255, 255, 0.08);
                border: 1px solid rgba(255, 255, 255, 0.15);
                color: #e2e8f0;
                border-radius: 8px;
                padding: 6px 14px;
                font-weight: 600;
                font-size: 0.85rem;
                min-height: 42px;
                display: inline-flex;
                align-items: center;
                gap: 7px;
                text-decoration: none;
                transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
            }

            .btn-icon-soft:hover {
                background: rgba(255, 255, 255, 0.18);
                color: #ffffff;
                transform: translateY(-1px);
            }

            .btn-icon-soft:focus-visible,
            .nav-pill-link:focus-visible {
                outline: 3px solid #67e8f9;
                outline-offset: 3px;
            }

            .nav-auth-link {
                min-width: 112px;
                min-height: 42px;
                justify-content: center;
                padding: 0 12px;
                border-radius: 8px;
            }

            .nav-register-link {
                border: 1px solid rgba(103, 232, 249, 0.48);
                background: linear-gradient(135deg, #0891b2, #2563eb);
                color: #fff;
            }

            .nav-register-link:hover {
                background: linear-gradient(135deg, #0e7490, #1d4ed8);
                color: #fff;
            }

            .user-avatar-circle {
                width: 28px;
                height: 28px;
                border-radius: 50%;
                background: linear-gradient(135deg, #06b6d4, #2563eb);
                display: inline-flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                font-weight: 700;
                font-size: 0.82rem;
            }

            .cart-counter-badge {
                font-size: 0.68rem;
                font-weight: 800;
                padding: 3px 6px;
            }

            /* Alerts */
            .alert-modern {
                border-radius: 10px;
                border: 1px solid;
                padding: 14px 18px;
            }

            /* Global Footer */
            .modern-footer {
                background-color: #0b1220;
                color: #94a3b8;
                border-top: 1px solid #1e293b;
                margin-top: auto;
                padding: 32px 0 24px;
            }

            .modern-footer a {
                color: #cbd5e1;
                text-decoration: none;
                transition: color 0.2s;
            }

            .modern-footer a:hover {
                color: #67e8f9;
            }

            .status-indicator-dot {
                width: 8px;
                height: 8px;
                background-color: #10b981;
                border-radius: 50%;
                display: inline-block;
            }

            @media (max-width: 767.98px) {
                .navbar-actions {
                    width: 100%;
                    justify-content: space-between;
                }

                .nav-account-actions {
                    margin-left: auto;
                }
            }

            @media (max-width: 575.98px) {
                .nav-account-actions {
                    padding-left: 0;
                    margin-left: 0;
                    border-left: 0;
                }

                .btn-icon-soft,
                .nav-pill-link {
                    padding-right: 10px;
                    padding-left: 10px;
                }

                #btn-edit-profile .profile-user-name {
                    max-width: 82px;
                }
            }

        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <!-- Header / Navbar -->
        <nav class="modern-navbar mb-4 sticky-top">
            <div class="container d-flex flex-wrap align-items-center justify-content-between gap-3">
                
                <!-- Logo & Brand -->
                <div class="d-flex align-items-center gap-3">
                    <a class="navbar-brand-custom" href="{{ url('/') }}">
                        <div class="navbar-brand-logo">
                            <i class="bi bi-cpu-fill"></i>
                        </div>
                        <div>
                            <h1 class="navbar-brand-title">Inventario</h1>
                        </div>
                    </a>
                    
                </div>

                <div class="navbar-actions">
                    <div class="nav-primary-actions">
                        @if(Auth::check() && Auth::user()->isAdmin())
                            <a class="nav-pill-link {{ request()->routeIs('products.index') ? 'active-shop' : '' }}" href="{{ route('products.index') }}" id="btn-tienda-admin-nav">
                                <i class="bi bi-grid-fill"></i>
                                <span>Tienda</span>
                            </a>
                            <div class="dropdown admin-nav-dropdown">
                                <button class="nav-pill-link dropdown-toggle {{ request()->routeIs('products.dashboard', 'products.create', 'products.edit', 'admin.*', 'orders.admin') ? 'active-panel' : '' }}" type="button" id="admin-nav-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-speedometer2 text-warning"></i>
                                    <span>Administración</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="admin-nav-dropdown">
                                    <li>
                                        <a class="dropdown-item {{ request()->routeIs('products.dashboard') ? 'active' : '' }}" href="{{ route('products.dashboard') }}">
                                            <i class="bi bi-speedometer2"></i><span>Panel de administración</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item {{ request()->routeIs('orders.admin') ? 'active' : '' }}" href="{{ route('orders.admin') }}">
                                            <i class="bi bi-receipt-cutoff"></i><span>Ventas</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                                            <i class="bi bi-people-fill"></i><span>Gestión de usuarios</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <a class="nav-pill-link {{ request()->routeIs('products.index') ? 'active-shop' : '' }}" href="{{ route('products.index') }}" id="btn-tienda-nav">
                                <i class="bi bi-grid-fill"></i>
                                <span>Tienda</span>
                            </a>
                        @endif
                    </div>
                    <div class="nav-account-actions">
                        @auth
                            <a href="{{ route('cart.index') }}" class="btn-icon-soft position-relative {{ request()->routeIs('cart.*') ? 'active-shop' : '' }}" title="Carrito de compras" aria-label="Carrito de compras">
                                <i class="bi bi-bag-check fs-5 text-info"></i>
                                @if(session('cart') && count(session('cart')) > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-counter-badge">
                                        {{ count(session('cart')) }}
                                    </span>
                                @endif
                            </a>

                            @if(!Auth::user()->isAdmin())
                                <a href="{{ route('orders.index') }}" class="btn-icon-soft {{ request()->routeIs('orders.index') ? 'active-shop' : '' }}" title="Mis compras">
                                    <i class="bi bi-receipt text-success"></i>
                                    <span>Mis Compras</span>
                                </a>
                            @endif

                            <a href="{{ route('profile.edit') }}" class="btn-icon-soft {{ request()->routeIs('profile.*') ? 'active-shop' : '' }}" id="btn-edit-profile">
                                <div class="user-avatar-circle">
                                    {{ strtoupper(substr(Auth::user()->user_name, 0, 1)) }}
                                </div>
                                <span class="profile-user-name d-none d-md-inline fw-bold">{{ Auth::user()->user_name }}</span>
                                @if(Auth::user()->isAdmin())
                                    <span class="badge bg-warning text-dark px-2 py-1 rounded-pill small" style="font-size: 0.65rem;">Admin</span>
                                @endif
                            </a>

                            <form method="POST" action="{{ route('logout') }}" class="mb-0 d-inline">
                                @csrf
                                <button type="submit" class="btn-icon-soft text-danger" id="btn-logout" title="Cerrar sesión" aria-label="Cerrar sesión" style="border-color: rgba(239, 68, 68, 0.3);">
                                    <i class="bi bi-box-arrow-right fs-5"></i>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn-icon-soft nav-auth-link" id="btn-entrar">
                                <i class="bi bi-box-arrow-in-right"></i> Entrar
                            </a>
                            <a href="{{ route('register') }}" class="btn-icon-soft nav-auth-link nav-register-link fw-bold shadow-sm" id="btn-registro-nav">
                                <i class="bi bi-person-plus me-1"></i> Registro
                            </a>
                        @endauth
                    </div>
                </div>

            </div>
        </nav>
        
        <!-- Main Content Area -->
        <main class="flex-grow-1">
            <div class="container">
                @if(session('success'))
                    <div class="alert alert-success alert-modern alert-dismissible fade show mb-4 bg-success bg-opacity-10 border-success border-opacity-25" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <div>
                                <strong class="d-block text-success">¡Operación Exitosa!</strong>
                                <span class="text-dark">{{ session('success') }}</span>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-modern alert-dismissible fade show mb-4 bg-danger bg-opacity-10 border-danger border-opacity-25" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-octagon-fill text-danger fs-4 me-3"></i>
                            <div>
                                <strong class="d-block text-danger">Atención</strong>
                                <span class="text-dark">{{ session('error') }}</span>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('warning'))
                    <div class="alert alert-warning alert-modern alert-dismissible fade show mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('temporary_password'))
                    <div class="alert alert-warning alert-modern mb-4" role="alert">
                        <strong>Contraseña temporal (se muestra una sola vez):</strong>
                        <code class="user-select-all ms-2">{{ session('temporary_password') }}</code>
                        <p class="small mb-0 mt-2">Compártela con el usuario por un canal seguro. No vuelvas a solicitarla: no se almacena en texto plano.</p>
                    </div>
                @endif
            </div>

            @yield('content')
        </main>

                <!-- Footer Minimalista y Limpio -->
        <footer class="py-3 mt-auto text-center text-muted small border-top bg-white">
            <div class="container">
                <span>&copy; {{ date('Y') }} Inventario. Todos los derechos reservados.</span>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
