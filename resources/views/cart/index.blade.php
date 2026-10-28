@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3 border-0 rounded-top-4 d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-dark fw-bold">
                <i class="bi bi-cart3 me-2 text-primary"></i>Tu Carrito
            </h4>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i>Seguir Comprando
            </a>
        </div>
        <div class="card-body p-0">
            @if(count($cart) > 0)
                @php($canCheckout = collect($cart)->every(fn ($item) => !empty($item['available'])))
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary small text-uppercase fw-bold">
                            <tr>
                                <th class="ps-4">Producto</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                                <th class="text-center pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $id => $details)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            @if(!empty($details['image']))
                                                <img src="{{ str_starts_with($details['image'], 'http') ? $details['image'] : asset('storage/' . $details['image']) }}" alt="{{ $details['name'] ?? 'Producto' }}" class="rounded shadow-sm me-3" style="width: 50px; height: 50px; object-fit: cover;" onerror="this.remove();">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center border me-3" style="width: 50px; height: 50px;">
                                                    <i class="bi bi-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div class="fw-bold text-dark">
                                                {{ $details['name'] ?? 'Producto' }}
                                                @unless($details['available'])
                                                    <span class="d-block text-danger small fw-normal">No disponible en la cantidad solicitada</span>
                                                @endunless
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($details['available'])
                                            $ {{ number_format($details['price'], 0, ',', '.') }} COP
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        <input type="number" value="{{ $details['quantity'] ?? 1 }}" min="1" max="{{ $details['stock'] ?? 0 }}" class="form-control form-control-sm text-center quantity update-cart" data-id="{{ $id }}" style="width: 70px;" @disabled(!$details['available'])>
                                    </td>
                                    <td class="fw-bold text-dark">
                                        @if($details['available'])
                                            $ {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }} COP
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="pe-4 text-center">
                                        <form action="{{ route('cart.remove') }}" method="POST" class="mb-0 cart-confirm-form"
                                              data-confirm-title="¿Eliminar producto?"
                                              data-confirm-text="Se quitará este producto de tu carrito"
                                              data-confirm-button="Sí, eliminar">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="id" value="{{ $id }}">
                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3" aria-label="Eliminar {{ $details['name'] ?? 'producto' }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="3" class="text-end fw-bold ps-4">Total:</td>
                                <td colspan="2" class="fw-bold text-primary fs-5 pe-4 ps-1">
                                    $ {{ number_format($total, 0, ',', '.') }} COP
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-footer bg-white border-0 py-3 rounded-bottom-4 d-flex justify-content-end gap-2">
                    <form action="{{ route('cart.clear') }}" method="POST" class="mb-0 cart-confirm-form"
                          data-confirm-title="¿Vaciar carrito?"
                          data-confirm-text="Se eliminarán todos los productos de tu carrito"
                          data-confirm-button="Sí, vaciar">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link text-danger text-decoration-none small">
                            Vaciar Carrito
                        </button>
                    </form>
                    @if($canCheckout)
                        <a href="{{ route('orders.checkout') }}" class="btn btn-success rounded-pill px-4 shadow-sm fw-bold" id="btn-proceed-checkout">
                            Continuar compra <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    @else
                        <button type="button" class="btn btn-secondary rounded-pill px-4" disabled>
                            Revisa los productos no disponibles
                        </button>
                    @endif
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-cart-x fs-1 d-block mb-3"></i>
                    <p class="mb-4">Tu carrito está vacío.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-4">
                        Ver Productos
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    document.querySelectorAll('.cart-confirm-form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            Swal.fire({
                title: form.dataset.confirmTitle,
                text: form.dataset.confirmText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: form.dataset.confirmButton,
                cancelButtonText: 'Cancelar'
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    $(".update-cart").change(function (e) {
        e.preventDefault();
        var ele = $(this);
        var qty = parseInt(ele.val());
        var maxQty = parseInt(ele.attr('max'));

        // Validación: no permitir 0 ni negativos
        if (isNaN(qty) || qty < 1) {
            Swal.fire({
                icon: 'error',
                title: 'Cantidad inválida',
                text: 'La cantidad debe ser mínimo 1.',
                confirmButtonColor: '#e11d48'
            });
            ele.val(1);
            return;
        }

        // Validación: no superar el stock disponible
        if (qty > maxQty) {
            Swal.fire({
                icon: 'warning',
                title: 'Stock insuficiente',
                text: 'Solo hay ' + maxQty + ' unidades disponibles.',
                confirmButtonColor: '#f59e0b'
            });
            ele.val(maxQty);
            return;
        }

        $.ajax({
            url: '{{ route('cart.update') }}',
            method: "patch",
            data: {
                _token: '{{ csrf_token() }}', 
                id: ele.attr("data-id"), 
                quantity: qty
            },
            success: function (response) {
                window.location.reload();
            }
        });
    });

</script>
@endsection
