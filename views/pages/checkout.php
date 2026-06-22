<?php
    $stripePublicKey = $_ENV['STRIPE_PUBLIC_KEY'] ?? '';
    $stripeActivo    = !empty($stripePublicKey) && !str_starts_with($stripePublicKey, 'pk_test_REEMPLAZA');
?>
<div class="container py-5">
    <div class="checkout-header mb-5">
        <h1 class="fw-bold mb-1">Finalizar Pedido</h1>
        <div class="checkout-steps">
            <div class="step done"><i class="bi bi-bag-check"></i> Carrito</div>
            <div class="step-divider"></div>
            <div class="step active"><i class="bi bi-truck"></i> Envío y Pago</div>
            <div class="step-divider"></div>
            <div class="step"><i class="bi bi-check-circle"></i> Confirmación</div>
        </div>
    </div>

    <?php if (isset($error) && $error): ?>
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error) ?>
            <?php unset($_SESSION['checkout_error']); ?>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Formulario -->
        <div class="col-lg-7">

            <!-- Datos de contacto -->
            <div class="checkout-section">
                <h5 class="fw-bold mb-4"><i class="bi bi-person me-2 text-primary"></i>Datos de contacto</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Nombre completo *</label>
                        <input type="text" id="f_nombre" class="form-control bg-light border-0 rounded-3 py-3"
                               value="<?= htmlspecialchars($cliente['nombre'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Teléfono *</label>
                        <input type="tel" id="f_telefono" class="form-control bg-light border-0 rounded-3 py-3"
                               value="<?= htmlspecialchars($cliente['telefono'] ?? '') ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold">Correo electrónico</label>
                        <input type="email" id="f_email" class="form-control bg-light border-0 rounded-3 py-3"
                               value="<?= htmlspecialchars($cliente['email'] ?? '') ?>">
                    </div>
                </div>
            </div>

            <!-- Dirección de envío -->
            <div class="checkout-section mt-4">
                <h5 class="fw-bold mb-4"><i class="bi bi-geo-alt me-2 text-primary"></i>Dirección de entrega</h5>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label small fw-bold">Calle y número *</label>
                        <input type="text" id="f_direccion" class="form-control bg-light border-0 rounded-3 py-3"
                               value="<?= htmlspecialchars($cliente['direccion_defecto'] ?? '') ?>"
                               placeholder="Ej: Calle Juárez 123, Col. Centro" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small fw-bold">Municipio / Ciudad *</label>
                        <input type="text" id="f_municipio" class="form-control bg-light border-0 rounded-3 py-3"
                               placeholder="Tu municipio" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Estado</label>
                        <input type="text" id="f_estado" class="form-control bg-light border-0 rounded-3 py-3"
                               placeholder="Estado">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Código Postal</label>
                        <input type="text" id="f_cp" class="form-control bg-light border-0 rounded-3 py-3"
                               placeholder="CP" maxlength="5">
                    </div>
                </div>
            </div>

            <!-- Método de pago -->
            <div class="checkout-section mt-4">
                <h5 class="fw-bold mb-4"><i class="bi bi-credit-card me-2 text-primary"></i>Método de pago</h5>
                <div class="metodo-pago-grid<?= $stripeActivo ? ' metodo-pago-3col' : '' ?>">

                    <label class="metodo-pago-card" for="mp_transferencia">
                        <input type="radio" name="metodo_pago" id="mp_transferencia"
                               value="transferencia" checked>
                        <div class="metodo-pago-content">
                            <i class="bi bi-bank2"></i>
                            <strong>Transferencia bancaria</strong>
                            <small>Depósito o transferencia SPEI</small>
                        </div>
                    </label>

                    <label class="metodo-pago-card" for="mp_contra_entrega">
                        <input type="radio" name="metodo_pago" id="mp_contra_entrega"
                               value="contra_entrega">
                        <div class="metodo-pago-content">
                            <i class="bi bi-cash-stack"></i>
                            <strong>Pago contra entrega</strong>
                            <small>Paga al recibir tu pedido</small>
                        </div>
                    </label>

                    <?php if ($stripeActivo): ?>
                    <label class="metodo-pago-card metodo-stripe" for="mp_tarjeta">
                        <input type="radio" name="metodo_pago" id="mp_tarjeta"
                               value="tarjeta_stripe">
                        <div class="metodo-pago-content">
                            <span class="stripe-card-icons">
                                <i class="bi bi-credit-card-2-front"></i>
                            </span>
                            <strong>Pagar con tarjeta</strong>
                            <small>Visa · Mastercard · AMEX</small>
                            <div class="stripe-badge">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 25" height="16">
                                    <path fill="#6772E5" d="M59.64 14.28h-8.06c.19 1.93 1.6 2.55 3.2 2.55 1.64 0 2.96-.37 4.05-.95v3.32a14.2 14.2 0 0 1-4.45.77c-3.72 0-6.3-2.35-6.3-6.65 0-3.71 2.27-6.54 5.83-6.54 3.51 0 5.7 2.85 5.7 6.4 0 .56-.06 1.05-.06 1.05h.09zm-5.7-6.24c-1.33 0-2.14.94-2.35 2.13h4.7c-.21-1.2-1.02-2.13-2.35-2.13zm-25.24 8.97l-.94-3.3c-.19-.65-.59-2.21-.76-2.86h-.06c-.17.65-.54 2.19-.73 2.86l-.94 3.3H22.8l-3.48-12.37h3.5l1.48 6.12c.19.75.41 1.78.5 2.28h.07c.09-.5.3-1.54.5-2.28l1.48-6.12h3.27l1.48 6.12c.19.75.41 1.78.5 2.28h.07c.09-.5.3-1.54.5-2.28l1.48-6.12h3.47l-3.5 12.37h-3.46zm-15.26.47c-3.57 0-6.04-2.75-6.04-6.58 0-3.82 2.47-6.58 6.04-6.58s6.04 2.76 6.04 6.58c0 3.83-2.47 6.58-6.04 6.58zm0-9.73c-1.52 0-2.62 1.26-2.62 3.15 0 1.89 1.1 3.15 2.62 3.15s2.62-1.26 2.62-3.15c0-1.89-1.1-3.15-2.62-3.15zM5.27 20.48V7.2h3.5v1.31c.73-.84 1.91-1.56 3.22-1.56v3.41c-1.36.09-2.72.63-2.72 2.59v7.53H5.27z"/>
                                </svg>
                                <span>Powered by Stripe</span>
                            </div>
                        </div>
                    </label>
                    <?php endif; ?>
                </div>

                <!-- Info: Transferencia -->
                <div id="info-transferencia" class="metodo-info mt-3">
                    <?php if (!empty($config['banco_nombre'])): ?>
                        <div class="alert alert-info border-0 rounded-3">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Datos bancarios:</strong> <?= htmlspecialchars($config['banco_nombre']) ?>
                            • Cuenta: <?= htmlspecialchars($config['banco_cuenta']) ?>
                            • CLABE: <?= htmlspecialchars($config['banco_clabe']) ?>
                            • Titular: <?= htmlspecialchars($config['banco_titular']) ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted small">Los datos bancarios te serán enviados al confirmar el pedido.</p>
                    <?php endif; ?>
                </div>

                <!-- Formulario de tarjeta Stripe -->
                <?php if ($stripeActivo): ?>
                <div id="stripe-form" class="mt-3" style="display:none;">
                    <div class="stripe-card-wrapper">
                        <label class="form-label small fw-bold mb-2">Datos de la tarjeta</label>
                        <div id="stripe-card-element"></div>
                        <div id="stripe-card-errors" class="stripe-error-msg mt-2" style="display:none;"></div>
                    </div>
                    <div class="alert alert-secondary border-0 rounded-3 mt-3 small">
                        <i class="bi bi-shield-lock me-2"></i>Tu pago está protegido con cifrado SSL. Usamos
                        <strong>Stripe</strong> — no almacenamos datos de tu tarjeta.
                        <br><span class="text-muted">Modo prueba: usa <strong>4242 4242 4242 4242</strong>, cualquier fecha futura y CVV.</span>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Notas -->
            <div class="checkout-section mt-4">
                <h5 class="fw-bold mb-4">
                    <i class="bi bi-chat-left-text me-2 text-primary"></i>Notas del pedido
                    <span class="text-muted fw-normal small">(opcional)</span>
                </h5>
                <textarea id="f_notas" class="form-control bg-light border-0 rounded-3 py-3" rows="3"
                          placeholder="Instrucciones especiales de entrega, referencias, etc."></textarea>
            </div>
        </div>

        <!-- Resumen del pedido -->
        <div class="col-lg-5">
            <div class="cart-summary sticky-top" style="top: 100px;">
                <h5 class="fw-bold mb-4"><i class="bi bi-receipt me-2"></i>Tu pedido</h5>

                <?php foreach ($items as $item): ?>
                    <div class="checkout-item">
                        <div class="checkout-item-qty"><?= $item['cantidad'] ?>×</div>
                        <div class="checkout-item-info">
                            <span class="checkout-item-nombre"><?= htmlspecialchars($item['nombre']) ?></span>
                            <?php if ($item['variante_desc']): ?>
                                <small class="text-muted d-block"><?= htmlspecialchars($item['variante_desc']) ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="checkout-item-precio">
                            $<?= number_format($item['precio'] * $item['cantidad'], 2) ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <hr>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>$<?= number_format($subtotal, 2) ?></span>
                </div>
                <div class="summary-row">
                    <span>Envío</span>
                    <span><?= $envio == 0 ? '<span class="text-success fw-bold">¡Gratis!</span>' : '$' . number_format($envio, 2) ?></span>
                </div>
                <hr>
                <div class="summary-row summary-total">
                    <span>Total</span>
                    <span>$<?= number_format($total, 2) ?> MXN</span>
                </div>

                <button id="btn-confirmar" type="button" class="btn btn-primary btn-lg w-100 rounded-pill mt-4"
                        onclick="procesarPedido()">
                    <i class="bi bi-bag-check-fill me-2"></i>Confirmar Pedido
                </button>
                <p class="text-center text-muted small mt-2">
                    <i class="bi bi-shield-lock me-1"></i>Tus datos están protegidos
                </p>
            </div>
        </div>
    </div>
</div>

<?php if ($stripeActivo): ?>
<!-- Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>
<?php endif; ?>

<script>
const URL_BASE        = '<?= URL_BASE ?>';
const STRIPE_ACTIVO   = <?= $stripeActivo ? 'true' : 'false' ?>;
<?php if ($stripeActivo): ?>
const STRIPE_PK       = '<?= htmlspecialchars($stripePublicKey) ?>';
const stripe          = Stripe(STRIPE_PK);
const elements        = stripe.elements();
const cardElement     = elements.create('card', {
    style: {
        base: {
            fontFamily: 'Inter, system-ui, sans-serif',
            fontSize: '15px',
            color: '#1e293b',
            '::placeholder': { color: '#94a3b8' },
        },
        invalid: { color: '#dc2626' }
    },
    hidePostalCode: true,
});
cardElement.mount('#stripe-card-element');

cardElement.on('change', function(event) {
    const errDiv = document.getElementById('stripe-card-errors');
    if (event.error) {
        errDiv.style.display = 'block';
        errDiv.textContent   = event.error.message;
    } else {
        errDiv.style.display = 'none';
    }
});
<?php endif; ?>

// ── Toggle visibilidad de secciones según método ──────────────────────────
function toggleInfoPago() {
    const metodo = document.querySelector('input[name="metodo_pago"]:checked')?.value;

    const infoTransferencia = document.getElementById('info-transferencia');
    if (infoTransferencia) {
        infoTransferencia.style.display = metodo === 'transferencia' ? 'block' : 'none';
    }

    if (STRIPE_ACTIVO) {
        const stripeForm = document.getElementById('stripe-form');
        if (stripeForm) {
            stripeForm.style.display = metodo === 'tarjeta_stripe' ? 'block' : 'none';
        }
    }
}
document.querySelectorAll('input[name="metodo_pago"]').forEach(r => r.addEventListener('change', toggleInfoPago));
toggleInfoPago(); // Ejecutar al cargar

// ── Recolectar datos del formulario ──────────────────────────────────────
function getDatosPedido() {
    const nombre    = document.getElementById('f_nombre')?.value?.trim();
    const telefono  = document.getElementById('f_telefono')?.value?.trim();
    const direccion = document.getElementById('f_direccion')?.value?.trim();
    const municipio = document.getElementById('f_municipio')?.value?.trim();

    if (!nombre || !telefono || !direccion || !municipio) {
        alert('Por favor completa todos los campos obligatorios (*).');
        return null;
    }

    return {
        nombre_cliente    : nombre,
        telefono_cliente  : telefono,
        email_cliente     : document.getElementById('f_email')?.value?.trim()    || '',
        direccion_entrega : direccion,
        municipio         : municipio,
        estado_entrega    : document.getElementById('f_estado')?.value?.trim()   || '',
        cp                : document.getElementById('f_cp')?.value?.trim()       || '',
        notas             : document.getElementById('f_notas')?.value?.trim()    || '',
    };
}

// ── Procesar pedido ───────────────────────────────────────────────────────
async function procesarPedido() {
    const btn    = document.getElementById('btn-confirmar');
    const metodo = document.querySelector('input[name="metodo_pago"]:checked')?.value;
    const datos  = getDatosPedido();
    if (!datos) return;

    // Transferencia o contra entrega → enviar como form normal
    if (metodo !== 'tarjeta_stripe') {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = URL_BASE + '/checkout';

        const allData = Object.assign({}, datos, { metodo_pago: metodo });
        for (const [key, val] of Object.entries(allData)) {
            const input = document.createElement('input');
            input.type  = 'hidden';
            input.name  = key;
            input.value = val;
            form.appendChild(input);
        }
        document.body.appendChild(form);
        form.submit();
        return;
    }

    // ── Pago con Stripe ───────────────────────────────────────────────────
    btn.disabled    = true;
    btn.innerHTML   = '<span class="spinner-border spinner-border-sm me-2"></span>Procesando pago...';

    try {
        // 1. Crear PaymentIntent en el servidor
        const piRes  = await fetch(URL_BASE + '/stripe/create-payment-intent', { method: 'POST' });
        const piData = await piRes.json();

        if (piData.error) throw new Error(piData.error);

        // 2. Confirmar pago con Stripe.js (muestra modal de autenticación si hace falta)
        const result = await stripe.confirmCardPayment(piData.clientSecret, {
            payment_method: {
                card: cardElement,
                billing_details: {
                    name  : datos.nombre_cliente,
                    email : datos.email_cliente || undefined,
                    phone : datos.telefono_cliente,
                }
            }
        });

        if (result.error) throw new Error(result.error.message);

        if (result.paymentIntent.status === 'succeeded') {
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Confirmando pedido...';

            // 3. Guardar el pedido en el servidor
            const orderRes  = await fetch(URL_BASE + '/stripe/confirmar-pago', {
                method  : 'POST',
                headers : { 'Content-Type': 'application/x-www-form-urlencoded' },
                body    : new URLSearchParams({
                    payment_intent_id : result.paymentIntent.id,
                    datos_pedido      : JSON.stringify(datos),
                }).toString(),
            });
            const orderData = await orderRes.json();

            if (orderData.success) {
                window.location.href = orderData.redirect;
            } else {
                throw new Error(orderData.error || 'Error al guardar el pedido.');
            }
        }

    } catch (err) {
        const errDiv = document.getElementById('stripe-card-errors');
        if (errDiv) {
            errDiv.style.display = 'block';
            errDiv.textContent   = err.message;
        } else {
            alert('Error: ' + err.message);
        }
        btn.disabled  = false;
        btn.innerHTML = '<i class="bi bi-bag-check-fill me-2"></i>Confirmar Pedido';
    }
}
</script>
