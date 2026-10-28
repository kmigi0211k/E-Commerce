@extends('layouts.app')

@section('content')
<style>
    .account-page {
        background:
            radial-gradient(ellipse at 8% 4%, rgba(59, 130, 246, 0.08), transparent 34rem),
            radial-gradient(ellipse at 92% 18%, rgba(6, 182, 212, 0.07), transparent 30rem);
        min-height: 100%;
    }

    .account-card {
        border: 1px solid #dbe5f0;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.08);
    }

    .account-section-title {
        color: #0e7490;
        border-color: #dbeafe !important;
    }

    .account-page .form-control,
    .account-page .input-group-text {
        border-color: #dbe5f0;
    }

    .account-page .form-control:focus {
        border-color: #38bdf8;
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.12);
    }

    .account-page .input-group-text {
        color: #0284c7;
        background: #f0f9ff !important;
    }
</style>
<div class="account-page py-4">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h3 class="fw-bold mb-0">Mis Datos Personales</h3>
                    <div class="text-secondary small">Administra tu cuenta y tus datos de contacto.</div>
                </div>
            </div>

            <div class="card account-card border-0 rounded-4 overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="{{ route('profile.info.update') }}">
                        @csrf
                        @method('PATCH')

                        <h5 class="account-section-title fw-bold mb-4 border-bottom pb-2">Información de la Cuenta</h5>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Nombre de Usuario</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="bi bi-person-badge"></i></span>
                                    <input type="text" name="user_name" class="form-control border-start-0 rounded-end-3" value="{{ old('user_name', $user->user_name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Correo Electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control border-start-0 rounded-end-3" value="{{ $user->person->email ?? '' }}" readonly>
                                </div>
                                <small class="form-text text-muted">El correo verificado no se puede cambiar desde esta pantalla.</small>
                            </div>
                        </div>

                        <h5 class="account-section-title fw-bold mb-4 border-bottom pb-2">Información Personal</h5>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Nombres</label>
                                <input type="text" name="names" class="form-control rounded-3" value="{{ old('names', $user->person->names ?? '') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Apellidos</label>
                                <input type="text" name="last_name" class="form-control rounded-3" value="{{ old('last_name', $user->person->last_name ?? '') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Teléfono / WhatsApp</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="bi bi-whatsapp"></i></span>
                                    <input type="text" name="phone" class="form-control border-start-0 rounded-end-3" value="{{ old('phone', $user->person->phone ?? '') }}" placeholder="Ej: 302 385 0997">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Dirección de Residencia</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="bi bi-geo-alt"></i></span>
                                    <input type="text" name="address" class="form-control border-start-0 rounded-end-3" value="{{ old('address', $user->person->address ?? '') }}" placeholder="Ej: Calle 48 E # 93-53">
                                </div>
                            </div>
                        </div>

                        <h5 class="account-section-title fw-bold mb-4 border-bottom pb-2">Seguridad (Opcional)</h5>
                        <p class="text-muted small mb-3">Deja estos campos vacíos si no deseas cambiar tu contraseña.</p>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Nueva Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="bi bi-shield-lock"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0 rounded-end-3" placeholder="Mínimo 8 caracteres">
                                </div>
                                @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Confirmar Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="bi bi-shield-check"></i></span>
                                    <input type="password" name="password_confirmation" class="form-control border-start-0 rounded-end-3" placeholder="Repite la contraseña">
                                </div>
                            </div>
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm">
                                Guardar Cambios <i class="bi bi-check-circle ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
