<div class="container py-5">
    <h1 class="fw-bold mb-1">Mi Carrito</h1>
    <p class="text-muted mb-5"><?= count($items) ?> producto(s) en tu carrito</p>

    <?php if (empty($items)): ?>
        <div class="carrito-vacio text-center py-5">
            <i class="bi bi-cart-x" style="font-size:4rem; color: #dee2e6;"></i>
            <h4 class="mt-4 fw-bold">Tu carrito está vacío</h4>
            <p class="text-muted">Agrega productos desde el catálogo para comenzar.</p>
            <a href="<?= URL_BASE ?>/productos" class="btn btn-primary rounded-pill px-5 mt-2">
                <i class="bi bi-bag me-2"></i>Ver Catálogo
            </a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <!-- Lista de productos -->
            <div class="col-lg-8">
                <div class="cart-items">
                    <?php foreach ($items as $item): ?>
                        <div class="cart-item" id="cart-item-<?= htmlspecialchars($item['key']) ?>">
                            <div class="cart-item-img">
                                <?php if ($item['imagen']): ?>
                                    <img src="<?= htmlspecialchars($item['imagen']) ?>" alt="<?= htmlspecialchars($item['nombre']) ?>">
                                <?php else: ?>
                                    <div class="cart-item-img-placeholder"><i class="bi bi-image"></i></div>
                                <?php endif; ?>
                            </div>
                            <div class="cart-item-info">
                                <h6 class="cart-item-name"><?= htmlspecialchars($item['nombre']) ?></h6>
                                <?php if ($item['variante_desc']): ?>
                                    <span class="cart-item-variante"><?= htmlspecialchars($item['variante_desc']) ?></span>
                                <?php endif; ?>
                                <span class="cart-item-precio">$<?= number_format($item['precio'], 2) ?> MXN</span>
                            </div>
                            <div class="cart-item-qty">
                                <div class="qty-control">
                                    <button class="qty-btn" onclick="updateCart('<?= htmlspecialchars($item['key']) ?>', <?= $item['cantidad'] - 1 ?>)">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <span class="qty-value" id="qty-<?= htmlspecialchars($item['key']) ?>"><?= $item['cantidad'] ?></span>
                                    <button class="qty-btn" onclick="updateCart('<?= htmlspecialchars($item['key']) ?>', <?= $item['cantidad'] + 1 ?>)">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="cart-item-subtotal">
                                <span id="sub-<?= htmlspecialchars($item['key']) ?>">
                                    $<?= number_format($item['precio'] * $item['cantidad'], 2) ?>
                                </span>
                            </div>
                            <button class="cart-item-remove" onclick="removeItem('<?= htmlspecialchars($item['key']) ?>')" title="Eliminar">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>

                <a href="<?= URL_BASE ?>/productos" class="btn btn-outline-secondary rounded-pill mt-3">
                    <i class="bi bi-arrow-left me-2"></i>Seguir comprando
                </a>
            </div>

            <!-- Resumen del pedido -->
            <div class="col-lg-4">
                <div class="cart-summary">
                    <h5 class="fw-bold mb-4">Resumen del pedido</h5>

                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span id="summary-subtotal">$<?= number_format($subtotal, 2) ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Envío</span>
                        <span id="summary-envio">
                            <?php if ($envio == 0): ?>
                                <span class="text-success fw-bold">¡Gratis!</span>
                            <?php else: ?>
                                $<?= number_format($envio, 2) ?>
                            <?php endif; ?>
                        </span>
                    </div>

                    <?php
                        $umbral = (float)($config['envio_gratis_desde'] ?? 0);
                        if ($umbral > 0 && $subtotal < $umbral):
                            $faltan = $umbral - $subtotal;
                    ?>
                        <div class="envio-progress mt-3 mb-3">
                            <p class="small text-muted mb-1">
                                Te faltan <strong>$<?= number_format($faltan, 2) ?></strong> para envío gratis
                            </p>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success"
                                     style="width: <?= min(100, ($subtotal / $umbral) * 100) ?>%"></div>
                            </div>
                        </div>
                    <?php elseif ($umbral > 0 && $envio == 0): ?>
                        <div class="envio-gratis-badge mt-2 mb-3">
                            <i class="bi bi-truck me-1"></i> ¡Envío gratis aplicado!
                        </div>
                    <?php endif; ?>

                    <hr>
                    <div class="summary-row summary-total">
                        <span>Total</span>
                        <span id="summary-total">$<?= number_format($total, 2) ?> MXN</span>
                    </div>

                    <a href="<?= URL_BASE ?>/checkout" class="btn btn-primary btn-lg w-100 rounded-pill mt-4">
                        <i class="bi bi-lock-fill me-2"></i>Proceder al pago
                    </a>
                    <p class="text-center text-muted small mt-2">
                        <i class="bi bi-shield-check me-1"></i>Compra 100% segura
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function updateCart(key, cantidad) {
    fetch('<?= URL_BASE ?>/carrito/actualizar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `key=${encodeURIComponent(key)}&cantidad=${cantidad}`
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            if (cantidad <= 0) {
                document.getElementById('cart-item-' + key)?.remove();
            } else {
                document.getElementById('qty-' + key).textContent = cantidad;
                // subtotal del ítem no retornado individualmente, recargamos
                location.reload();
            }
            updateCartBadge(data.cart_count);
            refreshSummary(data);
            if (data.cart_count === 0) location.reload();
        }
    });
}

function removeItem(key) {
    fetch('<?= URL_BASE ?>/carrito/eliminar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `key=${encodeURIComponent(key)}`
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('cart-item-' + key)?.remove();
            updateCartBadge(data.cart_count);
            refreshSummary(data);
            if (data.cart_count === 0) location.reload();
        }
    });
}

function refreshSummary(data) {
    const subtotalEl = document.getElementById('summary-subtotal');
    const totalEl    = document.getElementById('summary-total');
    if (subtotalEl) subtotalEl.textContent = '$' + data.subtotal;
    if (totalEl)    totalEl.textContent    = '$' + data.total + ' MXN';
}

function updateCartBadge(count) {
    document.querySelectorAll('.cart-badge').forEach(el => {
        el.textContent = count;
        el.style.display = count > 0 ? 'flex' : 'none';
    });
}
</script>
