@extends('layouts.app')

@section('content')
<style>
    .vol-wrapper {
        background: #f8fafc;
        min-height: 100vh;
        padding: 32px 24px;
    }

    .page-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
    }

    .page-title { font-size: 26px; font-weight: 800; color: #0f172a; letter-spacing: -0.5px; }
    .page-title span { color: #10b981; }
    .page-sub { font-size: 13px; color: #94a3b8; margin-top: 4px; }

    .btn-back {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 18px;
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: all 0.2s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }

    .btn-back:hover { background: #f1f5f9; color: #334155; }

    /* Stats */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 28px;
    }

    @media (max-width: 768px) { .stats-row { grid-template-columns: 1fr 1fr; } }

    .stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px 18px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 12px rgba(0,0,0,0.03);
        border: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .stat-icon-box {
        width: 48px; height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .icon-violet { background: #f5f3ff; color: #7c3aed; }
    .icon-amber  { background: #fffbeb; color: #d97706; }
    .icon-teal   { background: #f0fdfa; color: #0d9488; }
    .icon-rose   { background: #fff1f2; color: #e11d48; }

    .stat-val { font-size: 26px; font-weight: 800; color: #0f172a; line-height: 1; }
    .stat-lbl { font-size: 12px; color: #94a3b8; margin-top: 4px; }

    /* Table Card */
    .table-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 8px 24px rgba(0,0,0,0.04);
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }

    .table-header {
        padding: 20px 28px;
        border-bottom: 1px solid #f8fafc;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .table-title { font-size: 16px; font-weight: 800; color: #0f172a; }
    .table-count {
        font-size: 12px;
        color: #94a3b8;
        background: #f1f5f9;
        border-radius: 20px;
        padding: 4px 12px;
    }

    table { width: 100%; border-collapse: collapse; }

    thead th {
        background: #f8fafc;
        padding: 12px 16px;
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        border-bottom: 1px solid #f1f5f9;
        text-align: left;
        white-space: nowrap;
    }

    tbody tr { border-bottom: 1px solid #f8fafc; transition: background 0.15s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #fafbff; }

    tbody td {
        padding: 14px 16px;
        font-size: 13px;
        color: #334155;
        vertical-align: middle;
    }

    .user-chip {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .avatar {
        width: 36px; height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }

    .uname { font-weight: 700; color: #0f172a; font-size: 14px; }

    .product-name-cell { min-width: 0; font-weight: 600; color: #334155; overflow-wrap: anywhere; word-break: normal; white-space: normal; }

    .chip {
        display: inline-block;
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 12px;
        font-weight: 700;
    }

    .chip-help  { background: #eef2ff; color: #6366f1; }
    .chip-hours { background: #f0fdf4; color: #16a34a; }
    .chip-pending { background: #fffbeb; color: #d97706; }
    .chip-rose { background: #fff1f2; color: #e11d48; }

    .date-txt { font-size: 12px; color: #94a3b8; }

    .btn-del-vol {
        background: #fff1f2;
        border: 1px solid #fecdd3;
        border-radius: 8px;
        padding: 7px 14px;
        font-size: 12px;
        font-weight: 700;
        color: #e11d48;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s;
    }

    .btn-del-vol:hover { background: #ffe4e6; color: #be123c; }

    .empty-row { text-align: center; padding: 70px 20px; }
    .empty-icon { font-size: 52px; margin-bottom: 14px; opacity: 0.3; }

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
</style>

<div class="vol-wrapper">
    <div class="container-fluid">

        @if(session('success'))
        <div class="alert-ok">
            <i class="bi bi-check-circle-fill" style="color:#16a34a; font-size:18px;"></i>
            {{ session('success') }}
        </div>
        @endif

        <!-- Topbar -->
        <div class="page-topbar">
            <div>
                <div class="page-title">Panel de <span>Voluntarios</span></div>
                <div class="page-sub">Gestiona todos los registros de voluntariado de la comunidad</div>
            </div>
            <a href="{{ route('products.dashboard') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> Volver al Panel
            </a>
        </div>

        <!-- Stats -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon-box icon-violet"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="stat-val">{{ $volunteers->count() }}</div>
                    <div class="stat-lbl">Total Registros</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon-box icon-amber"><i class="bi bi-hourglass-split"></i></div>
                <div>
                    <div class="stat-val">{{ $volunteers->where('status', 'pending')->count() }}</div>
                    <div class="stat-lbl">Pendientes</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon-box icon-teal"><i class="bi bi-person-check-fill"></i></div>
                <div>
                    <div class="stat-val">{{ $volunteers->pluck('user_id')->unique()->count() }}</div>
                    <div class="stat-lbl">Voluntarios Únicos</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon-box icon-rose"><i class="bi bi-clock-fill"></i></div>
                <div>
                    <div class="stat-val">{{ $volunteers->sum('hours_committed') }}</div>
                    <div class="stat-lbl">Horas Comprometidas</div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-card">
            <div class="table-header">
                <div class="table-title">🤝 Registros de Voluntariado</div>
                <span class="table-count">{{ $volunteers->count() }} registros</span>
            </div>
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th style="padding-left:24px;">#</th>
                            <th>Voluntario</th>
                            <th>Producto Requerido</th>
                            <th>Tipo de Ayuda</th>
                            <th>Horas</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Detalles</th>
                            <th style="text-align:center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($volunteers as $volunteer)
                        <tr>
                            <td style="padding-left:24px; color:#94a3b8; font-size:12px;">#{{ $volunteer->id }}</td>
                            <td>
                                <div class="user-chip">
                                    <div class="avatar">{{ strtoupper(substr($volunteer->user->user_name ?? 'U', 0, 1)) }}</div>
                                    <div>
                                        <div class="uname">{{ $volunteer->user->person->names ?? $volunteer->user->user_name ?? 'Usuario eliminado' }}</div>
                                        <div style="font-size: 11px; color: #94a3b8;">{{ $volunteer->user->person->email ?? 'Sin correo' }}</div>
                                        @if($volunteer->phone)
                                            <div style="font-size: 11px; font-weight: 600; color: #10b981; margin-top: 2px;">
                                                <i class="bi bi-whatsapp"></i> {{ $volunteer->phone }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2" style="width: 180px; max-width: 100%;">
                                    @if($volunteer->product && $volunteer->product->image)
                                        <img src="{{ str_starts_with($volunteer->product->image, 'http') ? $volunteer->product->image : asset('storage/' . $volunteer->product->image) }}" class="rounded shadow-sm flex-shrink-0" style="width: 34px; height: 34px; object-fit: cover;">
                                    @else
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center border flex-shrink-0" style="width: 34px; height: 34px;">
                                            <i class="bi bi-box text-muted" style="font-size:14px;"></i>
                                        </div>
                                    @endif
                                    <span class="product-name-cell" title="{{ $volunteer->product->name ?? 'Eliminado' }}">{{ $volunteer->product->name ?? 'Producto Eliminado' }}</span>
                                </div>
                            </td>
                            <td><span class="chip chip-help">{{ $volunteer->help_type }}</span></td>
                            <td><span class="chip chip-hours"><i class="bi bi-clock me-1"></i>{{ $volunteer->hours_committed }}h</span></td>
                            <td>
                                @if($volunteer->status == 'accepted')
                                    <span class="chip chip-hours">✅ Aceptado</span>
                                @elseif($volunteer->status == 'rejected')
                                    <span class="chip chip-rose">❌ Rechazado</span>
                                @else
                                    <span class="chip chip-pending">⏳ Pendiente</span>
                                @endif
                            </td>
                            <td><span class="date-txt">{{ $volunteer->created_at->format('d/m/Y') }}</span></td>
                            <td>
                                <span style="font-size:12px; color:#94a3b8;">
                                    {{ $volunteer->details ? \Illuminate\Support\Str::limit($volunteer->details, 28) : '—' }}
                                </span>
                            </td>
                            <td style="text-align:center;">
                                <div class="d-flex justify-content-center gap-2">
                                    @if($volunteer->status == 'pending')
                                        <form action="{{ route('volunteers.updateStatus', $volunteer->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="accepted">
                                            <button type="submit" class="btn btn-sm btn-success fw-bold" style="border-radius:8px;">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('volunteers.updateStatus', $volunteer->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="btn btn-sm btn-warning fw-bold" style="border-radius:8px;">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('volunteers.destroy', $volunteer->id) }}" method="POST" class="vol-del-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger fw-bold btn-del-volunteer" style="border-radius:8px;">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty-row">
                                    <div class="empty-icon">🤝</div>
                                    <strong style="font-size:16px; color:#475569;">Sin registros aún</strong>
                                    <p style="color:#94a3b8; margin-top:6px; font-size:13px;">Los voluntarios aparecerán aquí cuando se postulen.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('.btn-del-volunteer').click(function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        Swal.fire({
            title: '¿Eliminar registro?',
            text: 'Se eliminará el registro de este voluntario.',
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
