@extends('layouts.app')

@section('content')
<style>
    body {
        background:
            radial-gradient(ellipse at 8% 4%, rgba(59, 130, 246, 0.09), transparent 34rem),
            radial-gradient(ellipse at 92% 18%, rgba(6, 182, 212, 0.08), transparent 30rem),
            #f1f5f9 !important;
    }

    .hero-section {
        background: linear-gradient(120deg, rgba(255, 255, 255, 0.98), rgba(239, 246, 255, 0.94));
        border-radius: 20px;
        padding: 40px 44px;
        margin-bottom: 32px;
        position: relative;
        border: 1px solid #dbeafe;
        box-shadow: 0 14px 36px rgba(15, 23, 42, 0.07);
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ecfeff;
        border: 1px solid #a5f3fc;
        border-radius: 6px;
        padding: 4px 12px;
        font-size: 12px;
        color: #0e7490;
        font-weight: 700;
        margin-bottom: 14px;
        letter-spacing: 0.3px;
    }

    .hero-title {
        font-size: 2.2rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
        letter-spacing: -0.5px;
        margin-bottom: 10px;
    }

    .hero-title span {
        color: #0284c7;
    }

    .hero-subtitle {
        color: #475569;
        font-size: 15px;
        margin-bottom: 0;
        max-width: 420px;
    }

    .hero-search {
        display: flex;
        width: min(100%, 440px);
        margin-top: 22px;
        padding: 5px;
        gap: 8px;
        border: 1px solid #dbe5f0;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
    }

    .hero-search input {
        min-width: 0;
        flex: 1;
        border: 0;
        background: transparent;
        box-shadow: none !important;
    }

    .hero-search input:focus {
        outline: none;
    }

    .hero-search button {
        flex: 0 0 44px;
        border: 0;
        border-radius: 9px;
        color: #fff;
        background: #0369a1;
    }

    .hero-search button:hover {
        background: #075985;
    }

    .hero-carousel {
        position: relative;
        width: min(100%, 520px);
        height: 350px;
        margin: 0 auto;
        overflow: hidden;
        border: 1px solid rgba(148, 163, 184, 0.28);
        border-radius: 20px;
        background: linear-gradient(135deg, #eff6ff, #ecfeff);
        box-shadow: 0 16px 35px rgba(15, 23, 42, 0.14);
    }

    .hero-carousel-slide {
        position: absolute;
        inset: 0;
        display: block;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.55s ease, visibility 0.55s ease;
    }

    .hero-carousel-slide.is-active {
        opacity: 1;
        visibility: visible;
    }

    .hero-carousel-slide img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .hero-carousel-caption {
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
        padding: 42px 18px 17px;
        color: #fff;
        background: linear-gradient(transparent, rgba(15, 23, 42, 0.82));
    }

    .hero-carousel-caption strong {
        display: block;
        overflow: hidden;
        font-size: 0.95rem;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .hero-carousel-caption span {
        display: block;
        margin-top: 3px;
        font-size: 0.82rem;
        font-weight: 700;
    }

    .hero-carousel-control {
        position: absolute;
        z-index: 2;
        top: 50%;
        display: grid;
        width: 36px;
        height: 36px;
        place-items: center;
        transform: translateY(-50%);
        border: 1px solid rgba(255, 255, 255, 0.65);
        border-radius: 50%;
        color: #0f172a;
        background: rgba(255, 255, 255, 0.9);
    }

    .hero-carousel-control:hover {
        background: #fff;
    }

    .hero-carousel-control.previous { left: 12px; }
    .hero-carousel-control.next { right: 12px; }

    .hero-carousel-dots {
        position: absolute;
        z-index: 2;
        right: 0;
        bottom: 12px;
        left: 0;
        display: flex;
        justify-content: center;
        gap: 6px;
        pointer-events: none;
    }

    .hero-carousel-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.65);
    }

    .hero-carousel-dot.is-active {
        width: 18px;
        border-radius: 6px;
        background: #fff;
    }

    .btn-panel {
        background: linear-gradient(135deg, #0891b2, #2563eb);
        border: 1px solid #0284c7;
        color: #ffffff;
        border-radius: 8px;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        position: relative;
        z-index: 10;
    }

    .btn-panel:hover {
        background: linear-gradient(135deg, #0e7490, #1d4ed8);
        border-color: #1d4ed8;
        color: #fff;
    }

    /* Stats bar */
    .stats-bar {
        display: flex;
        gap: 32px;
        margin-top: 28px;
        padding-top: 24px;
        border-top: 1px solid #e2e8f0;
    }

    .stat-item { text-align: left; }
    .stat-number {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
    }
    .stat-label {
        font-size: 12px;
        color: #64748b;
        margin-top: 2px;
    }

    /* Filter bar */
    .filter-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
    }

    .section-subtitle {
        font-size: 13px;
        color: #64748b;
        margin-top: 2px;
    }

    /* Product Cards Light Design */
    .product-card {
        background: #fff;
        border: 1px solid #dbe5f0;
        border-radius: 18px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        position: relative;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: translateY(-5px);
        border-color: #7dd3fc;
        box-shadow: 0 18px 38px rgba(37, 99, 235, 0.13);
    }

    .product-image-wrapper {
        position: relative;
        overflow: hidden;
        height: 210px;
        background: linear-gradient(135deg, #eff6ff, #ecfeff);
    }

    .product-image-wrapper img {
        position: relative;
        z-index: 1;
        width: 100%;
        height: 100%;
        padding: 10px;
        object-fit: contain;
        transition: transform 0.6s ease;
    }

    .product-card:hover .product-image-wrapper img {
        transform: none;
    }

    .product-placeholder {
        position: absolute;
        inset: 0;
        z-index: 0;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 56px;
        opacity: 0.5;
    }

    .price-badge {
        position: absolute;
        bottom: 12px;
        right: 12px;
        background: linear-gradient(135deg, #0891b2, #2563eb);
        border-radius: 12px;
        padding: 5px 13px;
        font-size: 15px;
        font-weight: 800;
        color: #fff;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.26);
        z-index: 2;
    }

    .stock-badge {
        position: absolute;
        top: 14px;
        left: 14px;
        z-index: 2;
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 700;
        backdrop-filter: blur(4px);
    }

    .stock-ok { background: linear-gradient(135deg, #0891b2, #2563eb); color: #fff; }
    .stock-low { background: linear-gradient(135deg, #0e7490, #1d4ed8); color: #fff; }
    .stock-out { background: rgba(239,68,68,0.9); color: #fff; }

    .product-body {
        padding: 17px 18px 12px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .product-name {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
        line-height: 1.3;
        overflow-wrap: anywhere;
        word-break: normal;
        min-height: 2.6em;
    }

    @media (max-width: 768px) {
        .hero-section { padding: 32px 24px; border-radius: 16px; }
        .hero-title { font-size: 1.8rem; }
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

    .product-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 11px 16px 14px;
        border-top: 1px solid #e8eef5;
        background: #fff;
    }

    .stock-info {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-add-cart {
        background: linear-gradient(135deg, #0891b2, #2563eb);
        border: none;
        border-radius: 10px;
        padding: 8px 13px;
        font-size: 12px;
        font-weight: 700;
        color: #fff;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 5px 14px rgba(37, 99, 235, 0.18);
    }

    .btn-add-cart:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(37,99,235,0.28);
    }

    .catalog-heading {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 16px;
        margin: 36px 2px 18px;
    }

    .catalog-heading h2 {
        margin: 0;
        color: #0f172a;
        font-size: 1.25rem;
        font-weight: 800;
    }

    .catalog-heading p {
        margin: 4px 0 0;
        color: #64748b;
        font-size: 0.88rem;
    }

    .catalog-count {
        flex: 0 0 auto;
        padding: 7px 12px;
        border: 1px solid #bae6fd;
        border-radius: 999px;
        background: #f0f9ff;
        color: #0369a1;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .catalog-pagination {
        display: flex;
        justify-content: center;
        margin-top: 28px;
    }

    @media (max-width: 575px) {
        .hero-section { padding: 28px 20px; }
        .hero-carousel { height: 250px; margin-top: 24px; }
        .stats-bar { gap: 22px; }
    }

    @media (prefers-reduced-motion: reduce) {
        .hero-carousel-slide { transition: none; }
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 24px;
    }

    @media (max-width: 991px) {
        .product-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }

    @media (max-width: 575px) {
        .product-grid { grid-template-columns: minmax(0, 1fr); gap: 18px; }
        .product-image-wrapper { height: 205px; }
        .catalog-heading { align-items: flex-start; }
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        color: #64748b;
    }

    .empty-state .empty-icon {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-state h3 {
        font-size: 20px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .empty-state p { font-size: 14px; }

    /* Alert override */
    .alert-success {
        background: rgba(6, 182, 212, 0.1);
        border: 1px solid rgba(8, 145, 178, 0.24);
        color: #155e75;
        border-radius: 12px;
    }
</style>

<div class="container-fluid px-4 py-4">
    <!-- Hero Section -->
    <div class="hero-section mb-4">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="hero-title">Explora nuestros<br><span>productos</span></h1>
                <form method="GET" action="{{ route('products.index') }}#catalogo" class="hero-search" role="search">
                    <label class="visually-hidden" for="catalog-search-hero">Buscar productos</label>
                    <input id="catalog-search-hero" type="search" name="search" class="form-control"
                           value="{{ request('search') }}" maxlength="100" placeholder="¿Qué producto buscas?">
                    <button type="submit" aria-label="Buscar productos">
                        <i class="bi bi-search" aria-hidden="true"></i>
                    </button>
                </form>
                <div class="stats-bar">
                    <div class="stat-item">
                        <div class="stat-number">{{ $catalogProductsCount }}</div>
                        <div class="stat-label">Productos</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ $availableProductsCount }}</div>
                        <div class="stat-label">En Stock</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 mt-4 mt-lg-0">
                @if($featuredProducts->isNotEmpty())
                    <div class="hero-carousel" data-product-carousel aria-label="Productos destacados">
                        @foreach($featuredProducts as $index => $featuredProduct)
                            <a href="{{ route('products.show', $featuredProduct) }}"
                               class="hero-carousel-slide{{ $index === 0 ? ' is-active' : '' }}"
                               data-carousel-slide
                               aria-hidden="{{ $index === 0 ? 'false' : 'true' }}"
                               @if($index !== 0) tabindex="-1" @endif>
                                <img src="{{ str_starts_with($featuredProduct->image, 'http') ? $featuredProduct->image : asset('storage/' . $featuredProduct->image) }}"
                                     alt="{{ $featuredProduct->name }}" @if($index !== 0) loading="lazy" @endif>
                                <span class="hero-carousel-caption">
                                    <strong>{{ $featuredProduct->name }}</strong>
                                    <span>$ {{ number_format($featuredProduct->price, 0, ',', '.') }} COP</span>
                                </span>
                            </a>
                        @endforeach
                        @if($featuredProducts->count() > 1)
                            <button type="button" class="hero-carousel-control previous" data-carousel-previous aria-label="Ver producto anterior">
                                <i class="bi bi-chevron-left" aria-hidden="true"></i>
                            </button>
                            <button type="button" class="hero-carousel-control next" data-carousel-next aria-label="Ver producto siguiente">
                                <i class="bi bi-chevron-right" aria-hidden="true"></i>
                            </button>
                            <div class="hero-carousel-dots" aria-hidden="true">
                                @foreach($featuredProducts as $index => $featuredProduct)
                                    <span class="hero-carousel-dot{{ $index === 0 ? ' is-active' : '' }}" data-carousel-dot></span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @elseif(Auth::check() && Auth::user()->isAdmin())
                    <div class="text-lg-end">
                        <a href="{{ route('products.dashboard') }}" class="btn-panel">
                            <i class="bi bi-speedometer2"></i> Panel de Gestión
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="catalog-heading" id="catalogo">
        <div>
            <h2>Catálogo tecnológico</h2>
            <p>Explora los productos disponibles y encuentra tu próximo equipo.</p>
        </div>
        <span class="catalog-count">{{ $products->total() }} productos</span>
    </div>

    <div class="product-grid">
        @forelse($products as $product)
        <div>
            <div class="product-card">
                <a href="{{ route('products.show', $product) }}" style="text-decoration:none; color:inherit; display:flex; flex-direction:column; flex:1;">
                    <div class="product-image-wrapper">
                        @if($product->stock > 0)
                            @if($product->stock < 5)
                                <div class="stock-badge stock-low"><i class="bi bi-exclamation-circle me-1"></i>¡Solo {{ $product->stock }}!</div>
                            @else
                                <div class="stock-badge stock-ok"><i class="bi bi-check2-circle me-1"></i>Stock: {{ $product->stock }}</div>
                            @endif
                        @else
                            <div class="stock-badge stock-out"><i class="bi bi-x-circle me-1"></i>Agotado</div>
                        @endif

                        <div class="product-placeholder" aria-hidden="true">📦</div>
                        @if($product->image)
                            <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy" onerror="this.remove();">
                        @endif
                        <div class="price-badge">$ {{ number_format($product->price, 0, ',', '.') }} COP</div>
                    </div>

                    <div class="product-body">
                        <div class="product-name">{{ $product->name }}</div>
                        <div class="product-desc">{{ $product->description }}</div>
                    </div>
                </a>

                <div class="product-footer mt-auto">
                    @if($product->stock > 0)
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" style="margin:0;">
                            @csrf
                            <button type="submit" class="btn-add-cart" id="btn-add-to-cart-{{ $product->id }}">
                                <i class="bi bi-cart-plus"></i> Añadir
                            </button>
                        </form>
                    @else
                        <span style="font-size:12px; color: #ef4444; font-weight:700;">Sin stock</span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                @if(request()->hasAny(['search', 'min_price', 'max_price', 'sort']))
                    <h3>No encontramos productos</h3>
                    <p>Prueba con otros términos o ajusta los filtros de precio.</p>
                @else
                    <h3>Aún no hay productos disponibles</h3>
                    <p>Vuelve más tarde para explorar el catálogo.</p>
                @endif
            </div>
        </div>
        @endforelse
    </div>

    @if($products->hasPages())
        <div class="catalog-pagination">
            {{ $products->links() }}
        </div>
    @endif

</div>

<script>
    document.querySelectorAll('[data-product-carousel]').forEach(function (carousel) {
        const slides = Array.from(carousel.querySelectorAll('[data-carousel-slide]'));
        const dots = Array.from(carousel.querySelectorAll('[data-carousel-dot]'));

        if (slides.length < 2) {
            return;
        }

        let currentSlide = 0;

        function showSlide(index) {
            currentSlide = (index + slides.length) % slides.length;
            slides.forEach(function (slide, slideIndex) {
                const isActive = slideIndex === currentSlide;
                slide.classList.toggle('is-active', isActive);
                slide.setAttribute('aria-hidden', String(!isActive));
                slide.tabIndex = isActive ? 0 : -1;
            });
            dots.forEach(function (dot, dotIndex) {
                dot.classList.toggle('is-active', dotIndex === currentSlide);
            });
        }

        let interval = window.matchMedia('(prefers-reduced-motion: reduce)').matches
            ? null
            : window.setInterval(function () { showSlide(currentSlide + 1); }, 4500);

        carousel.querySelector('[data-carousel-previous]').addEventListener('click', function () {
            showSlide(currentSlide - 1);
        });
        carousel.querySelector('[data-carousel-next]').addEventListener('click', function () {
            showSlide(currentSlide + 1);
        });
        carousel.addEventListener('mouseenter', function () {
            if (interval !== null) {
                window.clearInterval(interval);
                interval = null;
            }
        });
        carousel.addEventListener('mouseleave', function () {
            if (interval === null && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                interval = window.setInterval(function () { showSlide(currentSlide + 1); }, 4500);
            }
        });
    });
</script>
@endsection
