@extends('layouts.app')

@section('content')
<style>
    .checkout-page {
        padding: 38px 0 56px;
    }

    .checkout-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 26px;
    }

    .checkout-heading h1 {
        margin: 0;
        color: #0f172a;
        font-size: clamp(1.6rem, 3vw, 2.1rem);
        font-weight: 800;
        letter-spacing: -0.04em;
    }

    .checkout-heading p {
        margin: 7px 0 0;
        color: #64748b;
    }

    .checkout-progress {
        display: flex;
        align-items: center;
        width: min(100%, 430px);
    }

    .checkout-step {
        display: flex;
        flex: 1;
        align-items: center;
        gap: 9px;
        color: #94a3b8;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .checkout-step-number {
        display: grid;
        width: 32px;
        height: 32px;
        flex: 0 0 32px;
        place-items: center;
        border-radius: 50%;
        border: 1px solid #cbd5e1;
        background: #fff;
    }

    .checkout-step.is-active,
    .checkout-step.is-complete {
        color: #0369a1;
    }

    .checkout-step.is-active .checkout-step-number,
    .checkout-step.is-complete .checkout-step-number {
        border-color: #0284c7;
        color: #fff;
        background: #0284c7;
    }

    .checkout-step-line {
        height: 1px;
        flex: 0 0 34px;
        margin: 0 12px;
        background: #cbd5e1;
    }

    .checkout-step-panel[hidden] {
        display: none !important;
    }

    .checkout-step-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
    }

    .checkout-step-actions.split {
        justify-content: space-between;
        gap: 12px;
    }

    .checkout-next,
    .checkout-back {
        min-height: 46px;
        padding-inline: 20px;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 700;
    }

    .checkout-back {
        border: 1px solid #cbd5e1;
        color: #334155;
        background: #fff;
    }

    .checkout-card {
        overflow: hidden;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.06);
    }

    .checkout-card-header {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 22px 24px;
        border-bottom: 1px solid #eef2f7;
    }

    .checkout-section-icon {
        display: grid;
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        place-items: center;
        border-radius: 13px;
        color: #0369a1;
        background: #eff6ff;
        font-size: 1.15rem;
    }

    .checkout-card-header h2 {
        margin: 0;
        color: #0f172a;
        font-size: 1rem;
        font-weight: 800;
    }

    .checkout-card-header p {
        margin: 3px 0 0;
        color: #64748b;
        font-size: 0.82rem;
    }

    .checkout-card-body {
        padding: 24px;
    }

    .checkout-step-panel[data-checkout-panel="2"] > .row {
        align-items: stretch;
    }

    .checkout-step-panel[data-checkout-panel="2"] > .row > [class*="col-"] {
        display: flex;
    }

    .checkout-step-panel[data-checkout-panel="2"] .checkout-card {
        width: 100%;
    }

    .checkout-step-panel[data-checkout-panel="2"] .checkout-card:not(.checkout-summary) .checkout-card-body {
        display: flex;
        flex-direction: column;
    }

    .checkout-step-panel[data-checkout-panel="2"] .checkout-step-actions {
        margin-top: auto;
        padding-top: 20px;
    }

    .checkout-field label {
        display: block;
        margin-bottom: 7px;
        color: #334155;
        font-size: 0.82rem;
        font-weight: 700;
    }

    .checkout-field .form-control {
        min-height: 47px;
        border-color: #dbe5f0;
        border-radius: 10px;
        background-color: #fff;
        font-size: 0.92rem;
    }

    .checkout-field .form-control[readonly] {
        background-color: #f8fafc;
        color: #475569;
    }

    .payment-choice {
        position: relative;
    }

    .payment-choice input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .payment-choice label {
        display: flex;
        min-height: 96px;
        height: 100%;
        align-items: center;
        gap: 12px;
        padding: 16px;
        border: 1px solid #dbe5f0;
        border-radius: 12px;
        background: #fff;
        cursor: pointer;
        transition: border-color 0.18s ease, background-color 0.18s ease, box-shadow 0.18s ease;
    }

    .payment-choice label::before {
        width: 18px;
        height: 18px;
        flex: 0 0 18px;
        border: 1px solid #94a3b8;
        border-radius: 50%;
        background: #fff;
        content: "";
    }

    .payment-choice input:checked + label::before {
        border: 5px solid #0284c7;
    }

    .payment-choice-brand {
        display: flex;
        min-height: 38px;
        align-items: center;
        color: #1e293b;
        font-size: 0.95rem;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .payment-choice-brand.bancolombia {
        color: #263c84;
    }

    .payment-choice-brand.mastercard {
        gap: 7px;
        color: #111827;
        font-size: 0.83rem;
    }

    .mastercard-mark {
        display: inline-flex;
        align-items: center;
    }

    .mastercard-mark::before,
    .mastercard-mark::after {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        content: "";
    }

    .mastercard-mark::before {
        margin-right: -7px;
        background: #eb001b;
    }

    .mastercard-mark::after {
        background: rgba(247, 158, 27, 0.9);
    }

    .payment-choice-brand.daviplata {
        color: #e11d48;
    }

    .payment-choice input:checked + label {
        border-color: #0284c7;
        background: #f0f9ff;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.1);
    }

    .payment-choice input:focus-visible + label {
        outline: 3px solid rgba(14, 165, 233, 0.4);
        outline-offset: 3px;
    }

    .payment-choice-icon {
        display: grid;
        width: 46px;
        height: 46px;
        flex: 0 0 46px;
        place-items: center;
        border-radius: 13px;
        color: #0369a1;
        background: #e0f2fe;
        font-size: 1.3rem;
    }

    .payment-choice-copy strong {
        display: block;
        color: #0f172a;
        font-size: 0.9rem;
    }

    .payment-choice-copy span {
        display: block;
        margin-top: 4px;
        color: #64748b;
        font-size: 0.78rem;
        line-height: 1.45;
    }

    .checkout-summary {
        display: flex;
        flex-direction: column;
    }

    .checkout-summary-footer {
        margin-top: auto;
    }

    .checkout-summary-product {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        padding: 15px 20px;
        border-bottom: 1px solid #f1f5f9;
    }

    .checkout-summary-product:last-child {
        border-bottom: 0;
    }

    .checkout-summary-product strong {
        display: block;
        color: #1e293b;
        font-size: 0.84rem;
        line-height: 1.4;
    }

    .checkout-summary-product span {
        display: block;
        margin-top: 4px;
        color: #64748b;
        font-size: 0.78rem;
    }

    .checkout-summary-price {
        flex: 0 0 auto;
        color: #334155 !important;
        font-weight: 700;
    }

    .checkout-summary-footer {
        padding: 20px;
        border-top: 1px solid #eef2f7;
        background: #f8fafc;
    }

    .checkout-total {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 12px;
        margin: 17px 0 20px;
        padding-top: 17px;
        border-top: 1px solid #e2e8f0;
    }

    .checkout-total strong {
        color: #0f172a;
        font-size: 1.1rem;
    }

    .checkout-total span {
        color: #0369a1;
        font-size: 1.25rem;
        font-weight: 800;
        white-space: nowrap;
    }

    @media (max-width: 767px) {
        .checkout-page { padding: 26px 0 40px; }
        .checkout-heading { align-items: flex-start; flex-direction: column; }
        .checkout-card-header { padding: 18px; }
        .checkout-card-body { padding: 18px; }
        .checkout-step-panel[data-checkout-panel="2"] > .row > [class*="col-"] { display: block; }
        .checkout-step-panel[data-checkout-panel="2"] .checkout-card:not(.checkout-summary) .checkout-card-body { display: block; }
        .checkout-step-panel[data-checkout-panel="2"] .checkout-step-actions { margin-top: 20px; padding-top: 0; }
        .checkout-summary-footer { margin-top: 0; }
    }
</style>

<div class="container checkout-page">
    <div class="checkout-heading">
        <div>
            <h1>Finaliza tu pedido</h1>
            <p>Confirma tus datos de entrega y el método de pago.</p>
        </div>
        <div class="checkout-progress" aria-label="Progreso de compra">
            <div class="checkout-step is-active" data-step-indicator="1" aria-current="step">
                <span class="checkout-step-number">1</span>
                <span>Entrega</span>
            </div>
            <span class="checkout-step-line" aria-hidden="true"></span>
            <div class="checkout-step" data-step-indicator="2">
                <span class="checkout-step-number">2</span>
                <span>Pago y revisión</span>
            </div>
        </div>
    </div>
    <form action="{{ route('orders.process') }}" method="POST" id="checkout-form">
        @csrf
        <input type="hidden" name="checkout_token" value="{{ $checkoutToken }}">
        <section class="checkout-step-panel" data-checkout-panel="1" aria-labelledby="shipping-title">
            <div class="checkout-card">
                <div class="checkout-card-header">
                    <span class="checkout-section-icon"><i class="bi bi-truck" aria-hidden="true"></i></span>
                    <div>
                        <h2 id="shipping-title">Información de envío</h2>
                        <p>¿Dónde y a quién entregamos tu pedido?</p>
                    </div>
                </div>
                <div class="checkout-card-body">
                    <div class="row g-3">
                        <div class="col-md-6 checkout-field">
                            <label for="checkout-name">Nombre completo</label>
                            <input id="checkout-name" type="text" class="form-control" value="{{ Auth::user()->person->names ?? 'Usuario' }} {{ Auth::user()->person->last_name ?? '' }}" readonly>
                        </div>
                        <div class="col-md-6 checkout-field">
                            <label for="checkout-email">Correo de contacto</label>
                            <input id="checkout-email" type="email" class="form-control" value="{{ Auth::user()->person->email ?? 'sin-email@dominio.com' }}" readonly>
                        </div>
                        <div class="col-md-6 checkout-field">
                            <label for="checkout-phone">Teléfono de entrega</label>
                            <input id="checkout-phone" type="text" name="phone" class="form-control" value="{{ Auth::user()->person->phone ?? '' }}" placeholder="Ej.: 302 385 0997" required>
                        </div>
                        <div class="col-md-6 checkout-field">
                            <label for="checkout-address">Dirección de entrega</label>
                            <textarea id="checkout-address" name="address" class="form-control" rows="2" placeholder="Ej.: Calle 48 E # 93-53" required>{{ Auth::user()->person->address ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="checkout-step-actions">
                        <button type="button" class="btn btn-primary checkout-next" data-checkout-next>
                            Continuar al pago <i class="bi bi-arrow-right ms-2" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <section class="checkout-step-panel" data-checkout-panel="2" aria-labelledby="payment-title" hidden>
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="checkout-card">
                        <div class="checkout-card-header">
                            <span class="checkout-section-icon"><i class="bi bi-credit-card" aria-hidden="true"></i></span>
                            <div>
                                <h2 id="payment-title" tabindex="-1">Selecciona tu método de pago</h2>
                                <p>Elige tu método de pago preferido.</p>
                            </div>
                        </div>
                        <div class="checkout-card-body">
                            <div class="row g-3">
                                <div class="col-md-4 payment-choice">
                                    <input type="radio" name="payment_method" id="bancolombia" autocomplete="off" checked value="bancolombia" required>
                                    <label for="bancolombia">
                                        <span class="payment-choice-brand bancolombia">Bancolombia</span>
                                    </label>
                                </div>
                                <div class="col-md-4 payment-choice">
                                    <input type="radio" name="payment_method" id="mastercard" autocomplete="off" value="mastercard">
                                    <label for="mastercard">
                                        <span class="payment-choice-brand mastercard"><span class="mastercard-mark" aria-hidden="true"></span> Mastercard</span>
                                    </label>
                                </div>
                                <div class="col-md-4 payment-choice">
                                    <input type="radio" name="payment_method" id="daviplata" autocomplete="off" value="daviplata">
                                    <label for="daviplata">
                                        <span class="payment-choice-brand daviplata">Daviplata</span>
                                    </label>
                                </div>
                            </div>
                            <div class="checkout-step-actions split">
                                <button type="button" class="btn checkout-back" data-checkout-back>
                                    <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>Volver a entrega
                                </button>
                                <button type="submit" class="btn btn-success checkout-next">
                                    Confirmar pedido <i class="bi bi-check2 ms-2" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="checkout-card checkout-summary">
                        <div class="checkout-card-header">
                            <span class="checkout-section-icon"><i class="bi bi-receipt" aria-hidden="true"></i></span>
                            <div>
                                <h2>Resumen de compra</h2>
                                <p>{{ count($cart) }} {{ count($cart) === 1 ? 'producto' : 'productos' }}</p>
                            </div>
                        </div>
                        <div>
                            @foreach($cart as $id => $item)
                                <div class="checkout-summary-product">
                                    <div>
                                        <strong>{{ $item['name'] }}</strong>
                                        <span>Cantidad: {{ $item['quantity'] }}</span>
                                    </div>
                                    <span class="checkout-summary-price">$ {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="checkout-summary-footer">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span>$ {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3 text-success fw-bold">
                                <span>Envío</span>
                                <span>Gratis</span>
                            </div>
                            <div class="checkout-total">
                                <strong>Total a pagar</strong>
                                <span>$ {{ number_format($total, 0, ',', '.') }} COP</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>
</div>
<script>
    (function () {
        const form = document.getElementById('checkout-form');
        const shippingPanel = document.querySelector('[data-checkout-panel="1"]');
        const paymentPanel = document.querySelector('[data-checkout-panel="2"]');
        const indicators = Array.from(document.querySelectorAll('[data-step-indicator]'));

        function showStep(step) {
            shippingPanel.hidden = step !== 1;
            paymentPanel.hidden = step !== 2;
            indicators.forEach(function (indicator, index) {
                const indicatorStep = index + 1;
                const isActive = indicatorStep === step;
                indicator.classList.toggle('is-active', isActive);
                indicator.classList.toggle('is-complete', indicatorStep < step);
                if (isActive) {
                    indicator.setAttribute('aria-current', 'step');
                } else {
                    indicator.removeAttribute('aria-current');
                }
            });
        }

        document.querySelector('[data-checkout-next]').addEventListener('click', function () {
            const requiredFields = shippingPanel.querySelectorAll('[required]');
            for (const field of requiredFields) {
                if (!field.reportValidity()) {
                    field.focus();
                    return;
                }
            }

            showStep(2);
            document.getElementById('payment-title').focus({ preventScroll: true });
        });

        document.querySelector('[data-checkout-back]').addEventListener('click', function () {
            showStep(1);
            document.getElementById('checkout-phone').focus({ preventScroll: true });
        });

        form.addEventListener('submit', function (event) {
            if (paymentPanel.hidden) {
                event.preventDefault();
                document.querySelector('[data-checkout-next]').click();
            }
        });
    })();
</script>
@endsection
