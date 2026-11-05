@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-0 rounded-top-4">
                    <h4 class="mb-0 text-dark fw-bold text-center">
                        <i class="bi bi-plus-circle me-2 text-primary"></i>Crear Nuevo Producto
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4 text-center">
                            <label class="form-label d-block fw-bold text-muted small">Imagen del Producto</label>
                            
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label small text-muted">Subir archivo:</label>
                                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror shadow-sm rounded-pill">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small text-muted">O pegar URL de internet:</label>
                                    <input type="url" name="image_url" class="form-control @error('image_url') is-invalid @enderror shadow-sm rounded-pill" value="{{ old('image_url') }}" placeholder="https://ejemplo.com/foto.jpg">
                                </div>
                            </div>
                            
                            @error('image')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                            @error('image_url')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Descripción</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Detalles del producto...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Precio ($)</label>
                                <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" placeholder="Ej: 50000">
                                <div class="form-text small">Escribe el número sin puntos de mil (Ej: 50000 para cincuenta mil).</div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Stock</label>
                                <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock') }}" placeholder="0">
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 form-check mt-3">
                            <input type="checkbox" name="is_active" class="form-check-input" id="isActiveCheck" checked>
                            <label class="form-check-label fw-bold" for="isActiveCheck">
                                Producto Activo (visible en el catálogo)
                            </label>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button class="btn btn-primary btn-lg rounded-pill shadow-sm">
                                <i class="bi bi-save me-2"></i>Guardar Producto
                            </button>
                            <a href="{{ route('products.index') }}" class="btn btn-light btn-lg rounded-pill">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection