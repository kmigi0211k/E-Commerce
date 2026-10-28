@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0"><i class="bi bi-heart-half me-2 text-success"></i>Mis Postulaciones</h3>
        <a href="{{ route('products.comunidad') }}" class="btn btn-success rounded-pill px-4 shadow-sm fw-bold">
            <i class="bi bi-search me-2"></i>Ver más productos
        </a>
    </div>

    @if($volunteers->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 py-5 text-center">
            <div class="card-body">
                <i class="bi bi-emoji-smile display-1 text-muted mb-3 opacity-50"></i>
                <h4 class="fw-bold text-dark">Aún no tienes postulaciones de voluntariado</h4>
                <p class="text-secondary mb-4">Ayuda a la comunidad del 12 de Octubre y obtén productos como recompensa.</p>
                <a href="{{ route('products.comunidad') }}" class="btn btn-outline-success rounded-pill px-4 fw-bold">
                    Ir al voluntariado
                </a>
            </div>
        </div>
    @else
        <div class="row row-cols-1 g-4">
            @foreach($volunteers as $volunteer)
                <div class="col">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-light py-3 border-bottom-0 d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                @if($volunteer->status == 'accepted')
                                    <span class="badge bg-success rounded-pill px-3 py-2 fw-bold shadow-sm me-2"><i class="bi bi-check-circle me-1"></i>Aceptado</span>
                                @elseif($volunteer->status == 'rejected')
                                    <span class="badge bg-danger rounded-pill px-3 py-2 fw-bold shadow-sm me-2"><i class="bi bi-x-circle me-1"></i>Rechazado</span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2 fw-bold shadow-sm me-2"><i class="bi bi-hourglass-split me-1"></i>Pendiente</span>
                                @endif
                                <span class="text-muted fw-bold small">POSTULACIÓN #{{ str_pad($volunteer->id, 6, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="text-muted small fw-bold">
                                <i class="bi bi-calendar3 me-1"></i>{{ $volunteer->created_at->format('d/m/Y - h:i A') }}
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-8 mb-3 mb-md-0">
                                    <h6 class="fw-bold text-uppercase text-muted small mb-3">Producto Seleccionado</h6>
                                    <div class="d-flex align-items-center mb-2">
                                        @if($volunteer->product && $volunteer->product->image)
                                            <img src="{{ str_starts_with($volunteer->product->image, 'http') ? $volunteer->product->image : asset('storage/' . $volunteer->product->image) }}" class="rounded-3 shadow-sm me-3" style="width: 55px; height: 55px; object-fit: cover;">
                                        @else
                                            <div class="rounded-3 bg-success bg-opacity-10 d-flex align-items-center justify-content-center shadow-sm me-3" style="width: 55px; height: 55px;">
                                                <i class="bi bi-box fs-4 text-success"></i>
                                            </div>
                                        @endif
                                        <div style="min-width: 0;">
                                            <span class="fw-bold text-dark fs-5 d-block" style="line-height: 1.2; overflow-wrap: anywhere; word-break: normal;">{{ $volunteer->product->name ?? 'Producto no disponible' }}</span>
                                            <span class="text-muted small mt-1 d-block">{{ $volunteer->product->description ?? '' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end border-start-md ps-md-4">
                                    <h6 class="fw-bold text-uppercase text-muted small mb-2">Tu Compromiso</h6>
                                    
                                    <div class="mb-2">
                                        <span class="badge bg-light text-dark border"><i class="bi bi-person-arms-up me-1"></i>{{ $volunteer->help_type }}</span>
                                    </div>
                                    <h4 class="fw-bold text-success mb-0"><i class="bi bi-clock-history me-2"></i>{{ $volunteer->hours_committed }} horas</h4>
                                    
                                    @if($volunteer->status == 'accepted')
                                        <div class="mt-3 small text-success fw-bold">
                                            ¡El administrador se pondrá en contacto contigo pronto!
                                        </div>
                                    @elseif($volunteer->status == 'pending')
                                        <div class="mt-3 small text-muted">
                                            Esperando revisión del administrador...
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    @media (min-width: 768px) {
        .border-start-md {
            border-left: 1px solid #dee2e6 !important;
        }
    }
</style>
@endsection
