@extends('layouts.app')

@section('content')
<style>
    body {
        background:
            radial-gradient(ellipse at 8% 4%, rgba(59, 130, 246, 0.09), transparent 34rem),
            radial-gradient(ellipse at 92% 18%, rgba(6, 182, 212, 0.08), transparent 30rem),
            #f1f5f9 !important;
    }

    .community-hero {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border: 1px solid #bbf7d0;
        border-radius: 24px;
        padding: 52px 48px;
        margin-bottom: 36px;
        position: relative;
        overflow: hidden;
    }

    .community-hero::before {
        content: '';
        position: absolute;
        width: 350px; height: 350px;
        background: radial-gradient(circle, rgba(16,185,129,0.12), transparent 70%);
        border-radius: 50%;
        top: -80px; right: -80px;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #d1fae5;
        border: 1px solid #a7f3d0;
        border-radius: 50px;
        padding: 6px 14px;
        font-size: 12px;
        color: #059669;
        font-weight: 700;
        margin-bottom: 16px;
    }

    .hero-title {
        font-size: 2.4rem;
        font-weight: 800;
        color: #064e3b;
        line-height: 1.2;
        letter-spacing: -0.5px;
        margin-bottom: 12px;
    }

    .hero-title span {
        background: linear-gradient(135deg, #059669, #047857);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-desc {
        color: #065f46;
        font-size: 15px;
        max-width: 500px;
        line-height: 1.6;
    }

    .how-it-works {
        display: flex;
        gap: 24px;
        margin-top: 28px;
        padding-top: 24px;
        border-top: 1px solid #bbf7d0;
        flex-wrap: wrap;
    }

    .step {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        color: #065f46;
        font-weight: 600;
    }

    .step-num {
        width: 28px; height: 28px;
        background: #10b981;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
        box-shadow: 0 2px 4px rgba(16,185,129,0.3);
    }

    /* Cards Light Design */
    .volunteer-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        overflow: hidden;
        height: 100%;
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        display: flex;
        flex-direction: column;
    }

    .volunteer-card:hover {
        transform: translateY(-8px);
        border-color: #cbd5e1;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .card-img-wrap {
        position: relative;
        height: 220px;
        overflow: hidden;
        background: #f8fafc;
    }

    .card-img-wrap img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .volunteer-card:hover .card-img-wrap img { transform: scale(1.05); }

    .card-img-placeholder {
        width: 100%; height: 100%;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 56px;
        opacity: 0.5;
    }

    .solidarity-badge {
        position: absolute;
        top: 14px; left: 14px;
        background: #10b981;
        border-radius: 8px;
        padding: 5px 10px;
        font-size: 11px;
        font-weight: 700;
        color: #fff;
        box-shadow: 0 2px 4px rgba(16,185,129,0.3);
    }

    .card-body-custom {
        padding: 24px 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .product-name {
        font-size: 17px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
        line-height: 1.3;
        overflow-wrap: anywhere;
        word-break: normal;
    }

    .product-desc {
        font-size: 13px;
        color: #64748b;
        line-height: 1.5;
        margin-bottom: 16px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .btn-postulate {
        margin-top: auto;
        width: 100%;
        background: linear-gradient(135deg, #10b981, #059669);
        border: none;
        border-radius: 12px;
        padding: 12px;
        font-size: 14px;
        font-weight: 700;
        color: #fff;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(16,185,129,0.3);
    }

    .btn-postulate:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16,185,129,0.5);
    }

    /* Modal Light Design */
    .modal-content {
        background: #fff;
        border: none;
        border-radius: 20px;
        color: #0f172a;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .modal-header {
        border-color: #f1f5f9;
        padding: 20px 24px;
        background: #f8fafc;
        border-radius: 20px 20px 0 0;
    }

    .modal-title { font-weight: 800; font-size: 16px; color: #0f172a; }
    .btn-close { filter: none; }

    .modal-body { padding: 24px; }
    .modal-footer {
        border-color: #f1f5f9;
        padding: 16px 24px;
        background: #f8fafc;
        border-radius: 0 0 20px 20px;
    }

    .form-label-modal {
        font-size: 11px;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 7px;
    }

    .form-control-dark {
        background: #fff;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        color: #0f172a;
        padding: 11px 14px;
        font-size: 14px;
        width: 100%;
        transition: all 0.3s;
    }

    .form-control-dark::placeholder { color: #94a3b8; }
    .form-control-dark:focus {
        outline: none;
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16,185,129,0.15);
        background: #fff;
    }

    .form-control-dark option {
        background-color: #fff;
        color: #0f172a;
    }

    .btn-confirm {
        background: #10b981;
        border: none;
        border-radius: 10px;
        padding: 11px 24px;
        font-size: 14px;
        font-weight: 700;
        color: #fff;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 6px rgba(16,185,129,0.2);
    }

    .btn-confirm:hover { background: #059669; transform: translateY(-1px); box-shadow: 0 6px 12px rgba(16,185,129,0.3); }

    .btn-cancel-modal {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 11px 20px;
        font-size: 14px;
        font-weight: 600;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-cancel-modal:hover { background: #e2e8f0; }

    .section-header { margin-bottom: 24px; }
    .section-title { font-size: 18px; font-weight: 800; color: #0f172a; }
    .section-sub { font-size: 13px; color: #64748b; margin-top: 3px; }
</style>

<div class="container-fluid px-4 py-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Hero -->
    <div class="community-hero">
        <div class="row align-items-center">
            <div class="col-lg-8">
                @auth
                    <div class="mb-2">
                        <span class="text-success fw-bold small">¡Hola, {{ Auth::user()->user_name }}! 👋</span>
                    </div>
                @endauth
                <div class="hero-badge"><i class="bi bi-heart-fill"></i> Comunidad "12 de Octubre"</div>
                <h1 class="hero-title">Mercado<br><span>Solidario</span></h1>
                <p class="hero-desc">
                    Adquiere un producto totalmente gratis comprometiendo horas de servicio 
                    para realizar obras benéficas en nuestro barrio 12 de Octubre. 
                    ¡Juntos construimos una mejor comunidad!
                </p>
                <div class="how-it-works">
                    <div class="step">
                        <div class="step-num">1</div>
                        <span>Elige un producto</span>
                    </div>
                    <div class="step">
                        <div class="step-num">2</div>
                        <span>Define tu tipo de ayuda y horas</span>
                    </div>
                    <div class="step">
                        <div class="step-num">3</div>
                        <span>Espera confirmación del administrador</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products -->
    <div class="section-header">
        <div class="section-title">Productos Disponibles</div>
        <div class="section-sub">{{ $products->count() }} productos disponibles para voluntariado</div>
    </div>

    <div class="row g-4">
        @forelse($products as $product)
        <div class="col-sm-6 col-lg-4 col-xl-3">
            <div class="volunteer-card">
                <div class="card-img-wrap">
                    @if($product->image)
                        <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                    @else
                        <div class="card-img-placeholder">🌿</div>
                    @endif
                    <div class="solidarity-badge"><i class="bi bi-heart me-1"></i>Solidario</div>
                </div>

                <div class="card-body-custom">
                    <div class="product-name">{{ $product->name }}</div>
                    <div class="product-desc">{{ $product->description }}</div>

                    @auth
                        <button type="button" class="btn-postulate" id="btn-volunteer-open-{{ $product->id }}"
                            data-bs-toggle="modal" data-bs-target="#vModal{{ $product->id }}">
                            <i class="bi bi-hand-thumbs-up-fill"></i> Postularme como Voluntario
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="btn-postulate" style="text-decoration:none;">
                            <i class="bi bi-box-arrow-in-right"></i> Inicia sesión para postularte
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Modal de Voluntariado -->
        @auth
        <div class="modal fade" id="vModal{{ $product->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('volunteers.store', $product->id) }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="bi bi-heart-fill text-success me-2"></i>
                                Postulación: {{ $product->name }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label-modal">¿Cómo quieres ayudar?</label>
                                <select name="help_type" class="form-control-dark" required>
                                    <option value="">Selecciona una opción...</option>
                                    <option value="Limpieza de parques">🌳 Limpieza de parques</option>
                                    <option value="Apoyo en comedor comunitario">🍽️ Apoyo en comedor comunitario</option>
                                    <option value="Mantenimiento de vías">🛠️ Mantenimiento de vías</option>
                                    <option value="Cuidado de adultos mayores">👴 Cuidado de adultos mayores</option>
                                    <option value="Apoyo educativo">📚 Apoyo educativo</option>
                                    <option value="Otro">✋ Otro</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label-modal">Horas de Compromiso</label>
                                <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background:#f0fdf4; border:1px solid #bbf7d0;">
                                    <div class="text-center">
                                        <div style="font-size:2rem; font-weight:800; color:#059669; line-height:1;">{{ $product->hours_required ?? 4 }}</div>
                                        <div style="font-size:11px; color:#065f46; font-weight:600;">HORAS</div>
                                    </div>
                                    <div style="font-size:13px; color:#065f46;">
                                        Este producto requiere <strong>{{ $product->hours_required ?? 4 }} horas</strong> de servicio comunitario establecidas por el administrador.
                                    </div>
                                </div>
                                <input type="hidden" name="hours_committed" value="{{ $product->hours_required ?? 4 }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label-modal">Número de Teléfono (WhatsApp)</label>
                                <input type="text" name="phone"
                                    class="form-control-dark" required
                                    placeholder="Ej: 3001234567">
                            </div>
                            <div>
                                <label class="form-label-modal">Detalles adicionales (Opcional)</label>
                                <textarea name="details" class="form-control-dark" rows="3"
                                    placeholder="Cuéntanos más sobre cómo puedes ayudar..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn-confirm" id="btn-volunteer-confirm">
                                <i class="bi bi-check-lg me-1"></i> Confirmar Postulación
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endauth
        @empty
        <div class="col-12 text-center py-5" style="color:rgba(255,255,255,0.3);">
            <div style="font-size:60px; margin-bottom:16px;">🌿</div>
            <h4 style="color:rgba(255,255,255,0.4);">No hay productos disponibles</h4>
        </div>
        @endforelse
    </div>
</div>
@endsection
