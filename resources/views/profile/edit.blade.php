@extends('layouts.app')

@section('content')
<style>
    body {
        background:
            radial-gradient(ellipse at 8% 4%, rgba(59, 130, 246, 0.09), transparent 34rem),
            radial-gradient(ellipse at 92% 18%, rgba(6, 182, 212, 0.08), transparent 30rem),
            #f1f5f9 !important;
    }
    
    .profile-hero {
        background: linear-gradient(115deg, #0b1220 0%, #123d5a 58%, #0284c7 100%);
        color: white;
        padding: 38px 25px 88px;
        position: relative;
        border-radius: 0 0 24px 24px;
        overflow: hidden;
    }

    .profile-hero::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        right: -70px;
        top: -150px;
        border-radius: 50%;
        background: rgba(103, 232, 249, 0.12);
        pointer-events: none;
    }

    .profile-hero h1 {
        font-size: clamp(1.5rem, 3vw, 2rem);
        font-weight: 800;
        margin-bottom: 8px;
    }

    .profile-hero p {
        margin: 0;
        color: #dbeafe;
    }

    .profile-hero-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        position: relative;
        z-index: 1;
    }

    .profile-heading {
        max-width: 520px;
    }

    .profile-eyebrow {
        display: inline-block;
        margin-bottom: 8px;
        color: #a5f3fc;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    .profile-identity {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 16px 12px 12px;
        border: 1px solid rgba(255, 255, 255, 0.16);
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(8px);
        min-width: 240px;
    }

    .profile-avatar {
        width: 54px;
        height: 54px;
        flex: 0 0 54px;
        display: grid;
        place-items: center;
        border-radius: 17px;
        background: linear-gradient(135deg, #22d3ee, #3b82f6);
        color: #fff;
        font-size: 1.35rem;
        font-weight: 800;
        box-shadow: 0 8px 20px rgba(2, 132, 199, 0.25);
    }

    .profile-identity-name {
        font-size: 1.05rem;
        font-weight: 800;
        color: #fff;
        margin: 0;
    }

    .profile-identity-email {
        color: #cbd5e1;
        font-size: 0.88rem;
        overflow-wrap: anywhere;
    }

    .profile-grid {
        width: min(100% - 30px, 1120px);
        padding: 0 0 12px;
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 22px;
        margin: -48px auto 0;
        position: relative;
        z-index: 2;
    }

    .profile-card {
        background: #fff;
        border-radius: 18px;
        padding: 24px;
        text-align: left;
        text-decoration: none;
        color: #334155;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.07);
        transition: box-shadow 0.18s ease, border-color 0.18s ease, background-color 0.18s ease;
        border: 1px solid #dbe5f0;
        min-height: 232px;
    }

    .profile-card:hover {
        background: #fcfdff;
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.09);
        border-color: #bfcedd;
    }

    .profile-card:focus-visible {
        outline: 3px solid rgba(14, 165, 233, 0.45);
        outline-offset: 3px;
    }

    .profile-card-content {
        flex: 1;
    }

    .card-icon {
        width: 52px;
        height: 52px;
        display: grid;
        place-items: center;
        border-radius: 16px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 23px;
        margin-bottom: 20px;
    }

    .profile-card:nth-child(2) .card-icon {
        background: #ecfeff;
        color: #0891b2;
    }

    .profile-card:nth-child(3) .card-icon {
        background: #f0fdf4;
        color: #16a34a;
    }

    .card-title {
        font-size: 1.05rem;
        font-weight: 800;
        margin-bottom: 8px;
        color: #0f172a;
    }

    .card-desc {
        font-size: 0.875rem;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .profile-card-action {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 15px;
        border-top: 1px solid #eef2f7;
        color: #0369a1;
        font-size: 0.82rem;
        font-weight: 700;
    }

    .logout-section {
        margin-top: 28px;
        padding: 0 15px 48px;
        max-width: 380px;
        margin-left: auto;
        margin-right: auto;
    }

    .btn-logout-alt {
        width: 100%;
        background: #fff1f2;
        color: #e11d48;
        border: 2px solid #fb7185;
        padding: 15px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(225, 29, 72, 0.1);
    }

    .btn-logout-alt:hover {
        background: #e11d48;
        color: white;
    }

    @media (max-width: 576px) {
        .profile-hero { padding: 28px 20px 72px; }
        .profile-hero-content {
            align-items: flex-start;
            flex-direction: column;
            gap: 20px;
        }
        .profile-grid {
            width: min(100% - 30px, 420px);
            grid-template-columns: minmax(0, 1fr);
            gap: 14px;
            margin-top: -36px;
        }
        .profile-card { min-height: 0; padding: 20px; }
    }

    @media (min-width: 577px) and (max-width: 900px) {
        .profile-hero-content { align-items: flex-start; flex-direction: column; }
        .profile-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
</style>

<div class="profile-hero">
    <div class="container profile-hero-content">
        <div class="profile-heading">
            <span class="profile-eyebrow">Área personal</span>
            <h1>Mi cuenta</h1>
            <p>Consulta tus pedidos y administra tu información personal.</p>
        </div>
        <div class="profile-identity">
            <div class="profile-avatar">{{ strtoupper(substr(Auth::user()->user_name, 0, 1)) }}</div>
            <div>
                <h2 class="profile-identity-name">{{ Auth::user()->user_name }}</h2>
                <div class="profile-identity-email">{{ Auth::user()->person?->email }}</div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="profile-grid">
        <!-- Mi Perfil -->
        <a href="{{ route('profile.info') }}" class="profile-card">
            <div class="profile-card-content">
                <div class="card-icon"><i class="bi bi-person-vcard"></i></div>
                <div class="card-title">Mi Perfil</div>
                <div class="card-desc">Revisa y edita tus datos personales, contraseña y seguridad.</div>
            </div>
            <div class="profile-card-action"><span>Administrar perfil</span><i class="bi bi-arrow-right"></i></div>
        </a>

        <!-- Direcciones de envío (Simulado como Compras) -->
        <a href="{{ route('orders.index') }}" class="profile-card">
            <div class="profile-card-content">
                <div class="card-icon"><i class="bi bi-box-seam"></i></div>
                <div class="card-title">Mis Pedidos</div>
                <div class="card-desc">Gestiona tus pedidos realizados, historial y estados de entrega.</div>
            </div>
            <div class="profile-card-action"><span>Ver mis pedidos</span><i class="bi bi-arrow-right"></i></div>
        </a>

        <!-- Carrito -->
        <a href="{{ route('cart.index') }}" class="profile-card">
            <div class="profile-card-content">
                <div class="card-icon"><i class="bi bi-cart3"></i></div>
                <div class="card-title">Mi Carrito</div>
                <div class="card-desc">Finaliza la compra de los productos que tienes seleccionados.</div>
            </div>
            <div class="profile-card-action"><span>Ir al carrito</span><i class="bi bi-arrow-right"></i></div>
        </a>
    </div>

    <div class="logout-section">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout-alt">
                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
            </button>
        </form>
    </div>
</div>
@endsection
