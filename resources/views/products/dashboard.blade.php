@extends('layouts.app')

@section('content')
<style>
    /* Panel administrativo: inventario principal con herramientas laterales. */
    .dash-wrapper {
        background:
            radial-gradient(ellipse at 8% 4%, rgba(59, 130, 246, 0.07), transparent 34rem),
            radial-gradient(ellipse at 92% 18%, rgba(6, 182, 212, 0.06), transparent 30rem),
            #f1f5f9;
        min-height: 100vh;
        padding: 28px clamp(16px, 2.5vw, 36px) 48px;
    }

    .dash-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        padding: 28px 32px;
        margin-bottom: 24px;
        border: 1px solid rgba(125, 211, 252, 0.22);
        border-radius: 22px;
        background: linear-gradient(115deg, #0b1220 0%, #123d5a 62%, #0369a1 100%);
        box-shadow: 0 16px 34px rgba(15, 23, 42, 0.14);
    }

    .dash-greeting {
        font-size: clamp(23px, 2.5vw, 30px);
        font-weight: 800;
        color: #fff;
        letter-spacing: -0.7px;
    }

    .dash-greeting span { color: #67e8f9; }

    .dash-date {
        font-size: 13px;
        color: #cbd5e1;
        margin-top: 4px;
    }

    .btn-primary-new {
        background: linear-gradient(135deg, #0891b2, #2563eb);
        color: #fff;
        border: none;
        border-radius: 13px;
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.25s;
        box-shadow: 0 7px 18px rgba(14, 165, 233, 0.28);
    }

    .btn-primary-new:hover {
        background: linear-gradient(135deg, #0e7490, #1d4ed8);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 10px 24px rgba(37, 99, 235, 0.35);
    }

    .btn-secondary-action {
        background: #f0f9ff;
        color: #0369a1;
        border: 1px solid #bae6fd;
        border-radius: 12px;
        padding: 11px 20px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: all 0.25s;
    }

    .btn-secondary-action:hover {
        background: #e0f2fe;
        border-color: #38bdf8;
        color: #075985;
    }

    .dash-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 285px;
        gap: 22px;
        align-items: start;
    }

    .inventory-column,
    .admin-rail { min-width: 0; }

    .stats-row {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;
        margin-bottom: 16px;
    }

    .stat-card {
        background: #fff;
        border-radius: 15px;
        padding: 16px;
        box-shadow: 0 5px 18px rgba(15, 23, 42, 0.05);
        border: 1px solid #dbe5f0;
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .stat-icon-box {
        width: 44px; height: 44px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        flex-shrink: 0;
    }

    .icon-indigo { background: #e0f2fe; color: #0369a1; }
    .icon-green  { background: #ecfeff; color: #0e7490; }
    .icon-amber  { background: #fffbeb; color: #d97706; }
    .icon-red    { background: #fff1f2; color: #e11d48; }

    .stat-info { flex: 1; }
    .stat-value { font-size: 25px; font-weight: 800; color: #0f172a; line-height: 1; }
    .stat-label { font-size: 13px; color: #94a3b8; margin-top: 5px; }

    .rail-card {
        padding: 20px;
        margin-bottom: 16px;
        border: 1px solid #dbe5f0;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.96);
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
    }

    .rail-title {
        margin: 0 0 14px;
        color: #0f172a;
        font-size: 15px;
        font-weight: 800;
    }

    .admin-link {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 12px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        color: #334155;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: border-color 0.2s ease, background 0.2s ease, transform 0.2s ease;
    }

    .admin-link + .admin-link { margin-top: 9px; }
    .admin-link:hover {
        color: #075985;
        background: #f0f9ff;
        border-color: #7dd3fc;
        transform: translateX(2px);
    }

    .admin-link i {
        width: 34px;
        height: 34px;
        display: grid;
        place-items: center;
        border-radius: 10px;
        background: #e0f2fe;
        color: #0284c7;
        font-size: 16px;
    }

    .table-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        border: 1px solid #dbe5f0;
        overflow: hidden;
    }

    .table-header {
        padding: 22px 28px;
        border-bottom: 1px solid #e8eef5;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .table-title {
        font-size: 17px;
        font-weight: 800;
        color: #0f172a;
    }

    .table-count {
        font-size: 12px;
        color: #94a3b8;
        background: #f1f5f9;
        border-radius: 20px;
        padding: 4px 12px;
    }

    table { width: 100%; min-width: 980px; border-collapse: collapse; }

    thead th {
        background: #f8fafc;
        padding: 13px 20px;
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        border-bottom: 1px solid #f1f5f9;
        text-align: left;
    }

    tbody tr {
        border-bottom: 1px solid #f8fafc;
        transition: background 0.15s;
    }

    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #fafbff; }

    tbody td {
        padding: 16px 20px;
        font-size: 14px;
        color: #334155;
        vertical-align: middle;
    }

    .product-row {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .product-thumb {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid #f1f5f9;
        flex-shrink: 0;
    }

    .product-thumb-placeholder {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
        border: 2px solid #f1f5f9;
    }

    .product-name-big {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.3;
        overflow-wrap: anywhere;
    }

    .product-row > div:last-child { min-width: 0; }

    .product-desc-small {
        font-size: 12px;
        color: #94a3b8;
        margin-top: 3px;
        max-width: 180px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .badge-price {
        display: inline-block;
        background: #ecfeff;
        color: #0e7490;
        border: 1px solid #a5f3fc;
        border-radius: 8px;
        padding: 5px 12px;
        font-size: 14px;
        font-weight: 700;
    }

    .badge-stock-ok {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
        border-radius: 8px;
        padding: 5px 10px;
        font-size: 13px;
        font-weight: 700;
    }

    .badge-stock-low {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #fffbeb;
        color: #d97706;
        border: 1px solid #fde68a;
        border-radius: 8px;
        padding: 5px 10px;
        font-size: 13px;
        font-weight: 700;
    }

    .badge-stock-zero {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #fff1f2;
        color: #e11d48;
        border: 1px solid #fecdd3;
        border-radius: 8px;
        padding: 5px 10px;
        font-size: 13px;
        font-weight: 700;
    }

    /* Action Buttons */
    .actions-wrap { display: flex; align-items: center; justify-content: center; gap: 8px; flex-wrap: nowrap; }
    .actions-cell { min-width: 180px; text-align: center; white-space: nowrap; }
    .actions-cell .delete-form { display: inline-flex; margin: 0; }

    .btn-act {
        border-radius: 9px;
        padding: 7px 14px;
        font-size: 12px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s;
    }

    .btn-cart  { background: #eef2ff; color: #6366f1; }
    .btn-cart:hover  { background: #e0e7ff; color: #4f46e5; }

    .btn-edit  { background: #fffbeb; color: #d97706; }
    .btn-edit:hover  { background: #fef3c7; color: #b45309; }

    .btn-del   { background: #fff1f2; color: #e11d48; }
    .btn-del:hover   { background: #ffe4e6; color: #be123c; }

    /* Empty state */
    .empty-cell { text-align: center; padding: 70px 20px; }
    .empty-cell .empty-icon { font-size: 52px; margin-bottom: 12px; opacity: 0.35; }
    .empty-cell p { color: #94a3b8; font-size: 14px; margin-top: 6px; }

    /* Alert */
    .alert-ok {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        color: #166534;
        padding: 14px 18px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 24px;
    }

    /* iOS Switch */
    .ios-switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
        margin: 0;
    }
    .ios-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #cbd5e1;
        transition: .3s;
        border-radius: 24px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .3s;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    input:checked + .slider {
        background: linear-gradient(135deg, #0891b2, #2563eb);
    }
    input:checked + .slider:before {
        transform: translateX(20px);
    }
</style>

<div class="dash-wrapper">
    <div class="container-fluid">

        @if(session('success'))
        <div class="alert-ok">
            <i class="bi bi-check-circle-fill" style="color:#16a34a; font-size:18px;"></i>
            {{ session('success') }}
        </div>
        @endif

        <div class="dash-topbar">
            <div>
                <div class="dash-greeting">
                    Gestión de <span>inventario</span>
                </div>
                <div class="dash-date">
                    Panel de administración · Hola, <strong>{{ Auth::user()->user_name }}</strong>
                </div>
            </div>
            <a href="{{ route('products.create') }}" class="btn-primary-new">
                    <i class="bi bi-plus-lg"></i> Nuevo Producto
            </a>
        </div>

        <div class="dash-layout">
            <section class="inventory-column" aria-label="Inventario de productos">
                <div class="table-card">
                    <div class="table-header">
                        <div class="table-title"><i class="bi bi-box-seam-fill me-2" style="color:#0891b2;"></i>Inventario de productos</div>
                        <span class="table-count">{{ $products->count() }} productos</span>
                    </div>
                    <div style="overflow-x:auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th style="padding-left:28px; min-width:300px; width:36%;">Producto</th>
                                    <th style="min-width:145px;">Precio</th>
                                    <th style="min-width:115px;">Stock</th>
                                    <th style="text-align:center; min-width:100px;">Estado</th>
                                    <th class="actions-cell">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr>
                                    <td style="padding-left:28px;">
                                        <div class="product-row">
                                            @if($product->image)
                                                <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" class="product-thumb">
                                            @else
                                                <div class="product-thumb-placeholder">📦</div>
                                            @endif
                                            <div>
                                                <div class="product-name-big">{{ $product->name }}</div>
                                                <div class="product-desc-small">{{ $product->description }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge-price">$ {{ number_format($product->price, 0, ',', '.') }} COP</span></td>
                                    <td>
                                        @if($product->stock > 5)
                                            <span class="badge-stock-ok"><i class="bi bi-archive"></i>{{ $product->stock }} unid.</span>
                                        @elseif($product->stock > 0)
                                            <span class="badge-stock-low"><i class="bi bi-exclamation-triangle"></i>{{ $product->stock }} unid.</span>
                                        @else
                                            <span class="badge-stock-zero"><i class="bi bi-x-circle"></i>Sin stock</span>
                                        @endif
                                    </td>
                                    <td style="text-align:center;">
                                        <form action="{{ route('products.toggleActive', $product->id) }}" method="POST" class="toggle-active-form">
                                            @csrf
                                            @method('PATCH')
                                            <label class="ios-switch">
                                                <input type="checkbox" onchange="this.form.submit()" {{ $product->is_active ? 'checked' : '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </form>
                                    </td>
                                    <td class="actions-cell">
                                        <div class="actions-wrap">
                                            <a href="{{ route('products.edit', $product->id) }}" class="btn-act btn-edit">
                                                <i class="bi bi-pencil-fill"></i> Editar
                                            </a>
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn-act btn-del btn-delete">
                                                    <i class="bi bi-trash-fill"></i> Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-cell">
                                            <div class="empty-icon">📭</div>
                                            <strong style="font-size:16px; color:#475569;">No hay productos aún</strong>
                                            <p>Empieza añadiendo tu primer producto.</p>
                                            <a href="{{ route('products.create') }}" class="btn-primary-new" style="display:inline-flex; margin-top:12px;">
                                                <i class="bi bi-plus-lg"></i> Crear producto
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <aside class="admin-rail" aria-label="Herramientas de administración">
                <section class="rail-card">
                    <h2 class="rail-title">Accesos rápidos</h2>
                    <a href="{{ route('admin.users') }}" class="admin-link">
                        <i class="bi bi-people-fill"></i><span>Gestión de usuarios</span>
                    </a>
                    <a href="{{ route('orders.admin') }}" class="admin-link">
                        <i class="bi bi-receipt-cutoff"></i><span>Historial de compras</span>
                    </a>
                </section>

                <section class="rail-card">
                    <h2 class="rail-title">Estado del inventario</h2>
                    <div class="stats-row">
                        <div class="stat-card">
                            <div class="stat-icon-box icon-indigo"><i class="bi bi-box-seam-fill"></i></div>
                            <div class="stat-info">
                                <div class="stat-value">{{ $products->count() }}</div>
                                <div class="stat-label">Total productos</div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon-box icon-green"><i class="bi bi-check-circle-fill"></i></div>
                            <div class="stat-info">
                                <div class="stat-value">{{ $products->where('stock', '>', 5)->count() }}</div>
                                <div class="stat-label">Stock suficiente</div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon-box icon-amber"><i class="bi bi-exclamation-triangle-fill"></i></div>
                            <div class="stat-info">
                                <div class="stat-value">{{ $products->where('stock', '<=', 5)->where('stock', '>', 0)->count() }}</div>
                                <div class="stat-label">Stock bajo</div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon-box icon-red"><i class="bi bi-x-circle-fill"></i></div>
                            <div class="stat-info">
                                <div class="stat-value">{{ $products->where('stock', 0)->count() }}</div>
                                <div class="stat-label">Sin stock</div>
                            </div>
                        </div>
                    </div>
                </section>
            </aside>
        </div>

    </div>
</div>

<style>
    @media (max-width: 1100px) {
        .dash-layout { grid-template-columns: 1fr; }
        .admin-rail { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); align-items: start; }
    }

    @media (max-width: 700px) {
        .dash-topbar { align-items: flex-start; flex-direction: column; padding: 22px; }
        .dash-layout { gap: 16px; }
        .admin-rail { grid-template-columns: 1fr; }
        .table-header { padding: 18px; }
        .actions-cell { min-width: 180px; }
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('.btn-delete').click(function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        Swal.fire({
            title: '¿Eliminar producto?',
            text: "Esta acción no se puede revertir",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) { form.submit(); }
        });
    });
</script>
@endsection