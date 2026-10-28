@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0"><i class="bi bi-bag-check me-2 text-primary"></i>Mis Compras</h3>
        <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
            <i class="bi bi-shop me-2"></i>Seguir Comprando
        </a>
    </div>

    @if($orders->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 py-5 text-center">
            <div class="card-body">
                <i class="bi bi-bag-x display-1 text-muted mb-3 opacity-50"></i>
                <h4 class="fw-bold text-dark">Aún no tienes compras</h4>
                <p class="text-secondary mb-4">Explora nuestro catálogo y encuentra lo que necesitas.</p>
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold">
                    Ir al catálogo
                </a>
            </div>
        </div>
    @else
        <div class="row row-cols-1 g-4">
            @foreach($orders as $order)
                <div class="col">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-light py-3 border-bottom-0 d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <span class="badge {{ $order->status === 'pending_payment' ? 'bg-warning text-dark' : 'bg-success' }} rounded-pill px-3 py-2 fw-bold shadow-sm me-2">{{ $order->status_label }}</span>
                                <span class="text-muted fw-bold small">PEDIDO #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="text-muted small fw-bold">
                                <i class="bi bi-calendar3 me-1"></i>{{ $order->created_at->format('d/m/Y - h:i A') }}
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-8 mb-3 mb-md-0">
                                    <h6 class="fw-bold text-uppercase text-muted small mb-3">Artículos</h6>
                                    @foreach($order->items as $item)
                                        <div class="d-flex align-items-center mb-2">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ str_starts_with($item->product->image, 'http') ? $item->product->image : asset('storage/' . $item->product->image) }}" class="rounded-3 shadow-sm me-3" style="width: 45px; height: 45px; object-fit: cover;">
                                            @else
                                                <div class="rounded-3 bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center shadow-sm me-3" style="width: 45px; height: 45px;">
                                                    <i class="bi bi-box fs-5 text-secondary"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <span class="fw-bold text-dark d-block" style="line-height: 1.2; overflow-wrap: anywhere; word-break: normal;">{{ $item->product->name ?? 'Producto Eliminado' }}</span>
                                                <span class="text-muted small">Cantidad: {{ $item->quantity }} x $ {{ number_format($item->price, 0, ',', '.') }} COP</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-md-4 text-md-end border-start-md ps-md-4">
                                    <div class="text-muted small mb-2">
                                        <i class="bi bi-credit-card me-1"></i>
                                        {{ match ($order->payment_method) {
                                            'cash' => 'Contra entrega',
                                            'transfer' => 'Transferencia',
                                            'bancolombia' => 'Bancolombia',
                                            'mastercard' => 'Mastercard',
                                            'daviplata' => 'Daviplata',
                                            default => 'Pago no registrado',
                                        } }}
                                    </div>
                                    <h6 class="fw-bold text-uppercase text-muted small mb-2">Total del pedido</h6>
                                    <h3 class="fw-bold text-success mb-0">$ {{ number_format($order->total, 0, ',', '.') }} COP</h3>
                                    <a href="{{ route('orders.success', $order->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 mt-3 fw-bold">
                                        <i class="bi bi-receipt me-1"></i>Ver Recibo
                                    </a>
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
