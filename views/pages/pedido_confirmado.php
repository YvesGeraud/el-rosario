<div class="container py-5">
    <!-- Confirmación Header -->
    <div class="text-center confirm-hero mb-5">
        <div class="confirm-icon">
            <i class="bi bi-bag-check-fill"></i>
        </div>
        <h1 class="fw-bold mt-4">¡Pedido Confirmado!</h1>
        <p class="lead text-muted">Tu pedido ha sido recibido. Te contactaremos pronto.</p>
        <div class="folio-badge">
            Folio: <strong><?= htmlspecialchars($pedido['folio']) ?></strong>
        </div>
    </div>

    <div class="row g-4 justify-content-center">
        <!-- Resumen del pedido -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-receipt me-2 text-primary"></i>Detalle del Pedido</h5>
                    <?php foreach ($items as $item): ?>
                        <div class="confirm-item">
                            <div class="confirm-item-qty"><?= $item['cantidad'] ?>×</div>
                            <div class="confirm-item-info">
                                <strong><?= htmlspecialchars($item['nombre_producto']) ?></strong>
                                <?php if ($item['variante_descripcion']): ?>
                                    <small class="text-muted d-block"><?= htmlspecialchars($item['variante_descripcion']) ?></small>
                                <?php endif; ?>
                            </div>
                            <div class="confirm-item-precio fw-bold">
                                $<?= number_format($item['subtotal'], 2) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <hr>
                    <div class="d-flex justify-content-between text-muted small mb-1">
                        <span>Subtotal</span><span>$<?= number_format($pedido['subtotal'], 2) ?></span>
                    </div>
                    <div class="d-flex justify-content-between text-muted small mb-2">
                        <span>Envío</span>
                        <span>
                            <?= $pedido['costo_envio'] > 0
                                ? '$' . number_format($pedido['costo_envio'], 2)
                                : '<span class="text-success">Gratis</span>' ?>
                        </span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total</span>
                        <span class="text-primary">$<?= number_format($pedido['total'], 2) ?> MXN</span>
                    </div>
                </div>
            </div>

            <!-- Método de pago -->
            <?php if ($pedido['metodo_pago'] === 'transferencia'): ?>
                <div class="card border-0 shadow-sm rounded-4 mb-4 border-warning-subtle">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-bank2 me-2 text-warning"></i>Instrucciones de Pago</h5>
                        <p class="text-muted mb-3">Para confirmar tu pedido, realiza una transferencia o depósito con los siguientes datos:</p>
                        <div class="bank-info">
                            <?php if (!empty($config['banco_nombre'])): ?>
                                <div class="bank-row"><span>Banco</span><strong><?= htmlspecialchars($config['banco_nombre']) ?></strong></div>
                            <?php endif; ?>
                            <?php if (!empty($config['banco_titular'])): ?>
                                <div class="bank-row"><span>Titular</span><strong><?= htmlspecialchars($config['banco_titular']) ?></strong></div>
                            <?php endif; ?>
                            <?php if (!empty($config['banco_cuenta'])): ?>
                                <div class="bank-row"><span>No. de cuenta</span><strong><?= htmlspecialchars($config['banco_cuenta']) ?></strong></div>
                            <?php endif; ?>
                            <?php if (!empty($config['banco_clabe'])): ?>
                                <div class="bank-row"><span>CLABE</span><strong><?= htmlspecialchars($config['banco_clabe']) ?></strong></div>
                            <?php endif; ?>
                            <div class="bank-row"><span>Monto a transferir</span><strong class="text-primary">$<?= number_format($pedido['total'], 2) ?> MXN</strong></div>
                            <div class="bank-row"><span>Concepto / Referencia</span><strong><?= htmlspecialchars($pedido['folio']) ?></strong></div>
                        </div>
                        <div class="alert alert-warning border-0 rounded-3 mt-3 small">
                            <i class="bi bi-info-circle me-2"></i>
                            Envía tu comprobante de pago por WhatsApp para agilizar la confirmación.
                        </div>
                        <?php if (!empty($config['contacto_whatsapp'])): ?>
                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $config['contacto_whatsapp']) ?>?text=Hola%2C+realicé+mi+pago+para+el+pedido+<?= urlencode($pedido['folio']) ?>"
                               target="_blank" class="btn btn-success rounded-pill px-4">
                                <i class="bi bi-whatsapp me-2"></i>Enviar comprobante por WhatsApp
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php elseif ($pedido['metodo_pago'] === 'tarjeta_stripe'): ?>
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-credit-card-2-front me-2 text-primary"></i>Pago con Tarjeta</h5>
                        <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background:#f0fdf4;border:1px solid #bbf7d0;">
                            <div style="width:44px;height:44px;background:#22c55e;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-check-lg text-white" style="font-size:1.3rem;"></i>
                            </div>
                            <div>
                                <strong class="text-success d-block">¡Pago confirmado!</strong>
                                <span class="text-muted small">Tu pago de <strong>$<?= number_format($pedido['total'], 2) ?> MXN</strong> fue procesado correctamente con Stripe.</span>
                            </div>
                        </div>
                        <?php if ($pedido['stripe_intent_id']): ?>
                            <p class="text-muted small mt-3 mb-0">Referencia Stripe: <code><?= htmlspecialchars($pedido['stripe_intent_id']) ?></code></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-cash-stack me-2 text-success"></i>Pago Contra Entrega</h5>
                        <p class="text-muted mb-0">Pagarás <strong>$<?= number_format($pedido['total'], 2) ?> MXN</strong> al momento de recibir tu pedido. ¡Nada más sencillo!</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Datos de entrega -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt me-2 text-primary"></i>Datos de Entrega</h5>
                    <div class="confirm-detail-row">
                        <i class="bi bi-person text-muted"></i>
                        <span><?= htmlspecialchars($pedido['nombre_cliente']) ?></span>
                    </div>
                    <?php if ($pedido['telefono_cliente']): ?>
                        <div class="confirm-detail-row">
                            <i class="bi bi-telephone text-muted"></i>
                            <span><?= htmlspecialchars($pedido['telefono_cliente']) ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="confirm-detail-row">
                        <i class="bi bi-house text-muted"></i>
                        <span><?= htmlspecialchars($pedido['direccion_entrega']) ?></span>
                    </div>
                    <?php if ($pedido['municipio']): ?>
                        <div class="confirm-detail-row">
                            <i class="bi bi-map text-muted"></i>
                            <span><?= htmlspecialchars($pedido['municipio']) ?><?= $pedido['estado_entrega'] ? ', ' . htmlspecialchars($pedido['estado_entrega']) : '' ?><?= $pedido['cp'] ? ' CP ' . htmlspecialchars($pedido['cp']) : '' ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($pedido['notas']): ?>
                        <hr>
                        <div class="confirm-detail-row">
                            <i class="bi bi-chat-left-text text-muted"></i>
                            <span><?= htmlspecialchars($pedido['notas']) ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="d-grid gap-2">
                <a href="<?= URL_BASE ?>/mi-cuenta" class="btn btn-outline-primary rounded-pill">
                    <i class="bi bi-person me-2"></i>Ver mis pedidos
                </a>
                <a href="<?= URL_BASE ?>/productos" class="btn btn-primary rounded-pill">
                    <i class="bi bi-bag me-2"></i>Seguir comprando
                </a>
            </div>
        </div>
    </div>
</div>
