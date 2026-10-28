@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 text-center">
            <div class="card border-0 shadow-sm rounded-5 py-5 px-4 overflow-hidden position-relative">
                <!-- Decorative element -->
                <div class="position-absolute top-0 start-0 w-100 h-1 bg-success"></div>
                
                <div class="mb-4">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                        <i class="bi bi-check-lg fs-1 text-success"></i>
                    </div>
                </div>
                
                <h2 class="fw-bold text-dark mb-2">¡Pedido registrado!</h2>
                <p class="text-secondary mb-5">Gracias, <strong>{{ Auth::user()->person->names ?? Auth::user()->user_name }}</strong>. Tu pedido #{{ $order->id }} está {{ strtolower($order->status_label) }}.</p>
                
                <div class="row text-start bg-light rounded-4 p-4 mb-5">
                    <div class="col-12 mb-3 border-bottom pb-2">
                        <h6 class="fw-bold text-uppercase small text-muted mb-0">Información del Pedido</h6>
                    </div>
                    <div class="col-6 mb-2">
                        <span class="text-muted small d-block">Número de Orden</span>
                        <span class="fw-bold">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="col-6 mb-2 text-end">
                        <span class="text-muted small d-block">Fecha y Hora</span>
                        <span class="fw-bold">{{ $order->created_at->format('d/m/Y - h:i A') }}</span>
                    </div>
                    <div class="col-12 mb-2">
                        <span class="text-muted small d-block">Estado</span>
                        <span class="fw-bold">{{ $order->status_label }}</span>
                    </div>
                    <div class="col-12 mb-2">
                        <span class="text-muted small d-block">Método de pago seleccionado</span>
                        <span class="fw-bold">
                            {{ match ($order->payment_method) {
                                'cash' => 'Contra entrega',
                                'transfer' => 'Transferencia',
                                'bancolombia' => 'Bancolombia',
                                'mastercard' => 'Mastercard',
                                'daviplata' => 'Daviplata',
                                default => 'No registrado',
                            } }}
                        </span>
                    </div>
                    
                    <div class="col-12 mt-3 mb-3 border-bottom pb-2">
                        <h6 class="fw-bold text-uppercase small text-muted mb-0">Detalle de Artículos</h6>
                    </div>
                    @foreach($order->items as $item)
                        <div class="col-12 d-flex justify-content-between mb-2">
                            <span style="min-width: 0; overflow-wrap: anywhere; word-break: normal;">{{ $item->product->name ?? 'Producto' }} (x{{ $item->quantity }})</span>
                            <span class="fw-bold">$ {{ number_format($item->price * $item->quantity, 0, ',', '.') }} COP</span>
                        </div>
                    @endforeach
                    <div class="col-12 border-top mt-3 pt-3 d-flex justify-content-between align-items-center">
                        <span class="h5 fw-bold mb-0">Total del pedido</span>
                        <span class="h4 fw-bold mb-0 text-success">$ {{ number_format($order->total, 0, ',', '.') }} COP</span>
                    </div>
                </div>

                <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm fw-bold">
                        <i class="bi bi-bag-plus me-2"></i>Seguir Comprando
                    </a>
                    <button onclick="window.print()" class="btn btn-outline-secondary btn-lg rounded-pill px-4 fw-bold">
                        <i class="bi bi-printer me-2"></i>Imprimir Recibo
                    </button>
                </div>
            </div>
            
            <p class="mt-4 text-muted small">
                Se ha enviado una confirmación a su correo electrónico: <strong>{{ Auth::user()->person->email ?? 'N/A' }}</strong>
            </p>
        </div>
    </div>
</div>
@endsection
