@extends('layouts.app')

@section('content')
<style>
    body {
        background:
            radial-gradient(ellipse at 8% 4%, rgba(59, 130, 246, 0.09), transparent 34rem),
            radial-gradient(ellipse at 92% 18%, rgba(6, 182, 212, 0.08), transparent 30rem),
            #f1f5f9 !important;
    }

    .product-details-card {
        background: #fff;
        border: 1px solid #dbe5f0;
        border-radius: 26px;
        overflow: hidden;
        box-shadow: 0 18px 44px rgba(15, 23, 42, 0.09);
    }

    .product-image-container {
        position: relative;
        height: 500px;
        padding: 20px;
        background:
            radial-gradient(circle at 20% 15%, rgba(56, 189, 248, 0.16), transparent 42%),
            linear-gradient(135deg, #eff6ff, #ecfeff);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-image-container img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .product-placeholder-large {
        font-size: 100px;
        color: #cbd5e1;
    }

    .product-info-container {
        padding: 48px;
    }

    .badge-stock {
        font-size: 13px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 16px;
    }

    .badge-stock-ok { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
    .badge-stock-low { background: #ecfeff; color: #0e7490; border: 1px solid #a5f3fc; }
    .badge-stock-out { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }

    .product-title {
        font-size: 36px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
        margin-bottom: 12px;
        letter-spacing: -0.5px;
        overflow-wrap: anywhere;
        word-break: normal;
    }

    .product-price {
        font-size: 42px;
        font-weight: 800;
        color: #0369a1;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .product-description {
        font-size: 16px;
        color: #475569;
        line-height: 1.7;
        margin-bottom: 32px;
        padding-bottom: 32px;
        border-bottom: 1px solid #f1f5f9;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
        color: #64748b;
        font-size: 14px;
        font-weight: 600;
    }

    .meta-icon {
        width: 32px; height: 32px;
        background: #f8fafc;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #075985;
        font-size: 16px;
    }

    .action-container {
        margin-top: 36px;
        display: flex;
        gap: 16px;
    }

    .btn-buy-lg {
        flex: 1;
        background: linear-gradient(135deg, #0891b2, #2563eb);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 16px;
        font-size: 16px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    }

    .btn-buy-lg:hover:not(:disabled) {
        background: linear-gradient(135deg, #0e7490, #1d4ed8);
        transform: translateY(-2px);
        box-shadow: 0 10px 22px -3px rgba(37,99,235,0.3);
    }

    .btn-buy-lg:disabled {
        background: #cbd5e1;
        cursor: not-allowed;
    }

    .btn-back-link {
        color: #64748b;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 24px;
        transition: color 0.2s;
    }

    .btn-back-link:hover { color: #0f172a; }

    @media (max-width: 991px) {
        .product-image-container { height: 400px; }
        .product-info-container { padding: 32px; }
        .product-title { font-size: 28px; }
    }
</style>

<div class="container py-4">
    <a href="{{ route('products.index') }}" class="btn-back-link">
        <i class="bi bi-arrow-left"></i> Volver a la Tienda
    </a>

    <div class="product-details-card">
        <div class="row g-0">
            <!-- Columna de la Imagen -->
            <div class="col-lg-6">
                <div class="product-image-container">
                    @if($product->image)
                        <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=800&q=80';">
                    @else
                        <div class="product-placeholder-large">
                            <i class="bi bi-image"></i>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Columna de Detalles -->
            <div class="col-lg-6">
                <div class="product-info-container">
                    
                    @if($product->stock > 0)
                        @if($product->stock < 5)
                            <div class="badge-stock badge-stock-low"><i class="bi bi-exclamation-circle"></i> ¡Últimas {{ $product->stock }} unidades!</div>
                        @else
                            <div class="badge-stock badge-stock-ok"><i class="bi bi-check2-circle"></i> Disponible ({{ $product->stock }} en stock)</div>
                        @endif
                    @else
                        <div class="badge-stock badge-stock-out"><i class="bi bi-x-circle"></i> Agotado</div>
                    @endif

                    <h1 class="product-title">{{ $product->name }}</h1>
                    
                    <div class="product-price">
                        $ {{ number_format($product->price, 0, ',', '.') }} COP
                    </div>

                    <p class="product-description">
                        {{ $product->description }}
                    </p>

                    <div class="product-meta">
                        <div class="meta-item">
                            <div class="meta-icon"><i class="bi bi-shield-check"></i></div>
                            <div>Garantía Oficial ProductosPro</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-icon"><i class="bi bi-truck"></i></div>
                            <div>Envíos a todo el país</div>
                        </div>
                    </div>

                    <div class="action-container">
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" style="flex:1; margin:0;">
                            @csrf
                            <button type="submit" class="btn-buy-lg" {{ $product->stock == 0 ? 'disabled' : '' }}>
                                <i class="bi bi-cart-plus"></i> Añadir al Carrito
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
