@extends('layouts.app')

@section('content')
<div class="container py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1"><i class="bi bi-tags-fill me-2 text-primary"></i>Categorías</h1>
            <p class="text-muted mb-0">Organiza los productos del inventario.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary rounded-pill">
            <i class="bi bi-arrow-left me-1"></i>Volver al panel
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger rounded-4">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 rounded-4">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold mb-3">Nueva categoría</h2>
                    <form method="POST" action="{{ route('admin.categories.store') }}">
                        @csrf
                        <label for="category-name" class="form-label">Nombre</label>
                        <input id="category-name" name="name" value="{{ old('name') }}" maxlength="100" required
                            class="form-control mb-3 @error('name') is-invalid @enderror">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <button class="btn btn-primary rounded-pill w-100" type="submit">
                            <i class="bi bi-plus-lg me-1"></i>Crear categoría
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card border-0 rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Categoría</th>
                                <th>Productos</th>
                                <th class="text-end pe-4">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td class="ps-4 fw-semibold">{{ $category->name }}</td>
                                    <td>{{ $category->products_count }}</td>
                                    <td class="text-end pe-4">
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                            onsubmit="return confirm('¿Eliminar esta categoría? Los productos se conservarán sin categoría.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
                                                <i class="bi bi-trash me-1"></i>Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-5">Aún no hay categorías registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
