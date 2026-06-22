<?php
    $badges = [
        'pendiente'  => 'warning',
        'confirmado' => 'info',
        'enviado'    => 'primary',
        'entregado'  => 'success',
        'cancelado'  => 'danger',
    ];
    $b = $badges[$pedido['estado']] ?? 'secondary';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="<?= URL_BASE ?>/admin/pedidos" class="btn btn-sm btn-outline-secondary rounded-pill mb-2">
            <i class="bi bi-arrow-left me-1"></i>Volver a pedidos
        </a>
        <h4 class="fw-bold mb-0">Pedido <?= htmlspecialchars($pedido['folio']) ?></h4>
        <p class="text-muted small mb-0">Recibido el <?= date('d/m/Y \a \l\a\s H:i', strtotime($pedido['fecha_in'])) ?></p>
    </div>
    <span class="badge bg-<?= $b ?>-subtle text-<?= $b ?> fs-6 px-4 py-2 rounded-pill">
        <?= ucfirst($pedido['estado']) ?>
    </span>
</div>

<?php if (isset($success)): ?>
    <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
        <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($success) ?>
        <?php unset($_SESSION['pedido_success']); ?>
    </div>
<?php endif; ?>

<div class="row g-4">
    <!-- Productos del pedido -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4"><i class="bi bi-bag me-2 text-primary"></i>Productos</h5>
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-center">Precio unit.</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($item['nombre_producto']) ?></strong>
                                        <?php if ($item['variante_descripcion']): ?>
                                            <br><small class="text-muted"><?= htmlspecialchars($item['variante_descripcion']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><?= $item['cantidad'] ?></td>
                                    <td class="text-center">$<?= number_format($item['precio_unitario'], 2) ?></td>
                                    <td class="text-end fw-bold">$<?= number_format($item['subtotal'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end text-muted small">Subtotal</td>
                                <td class="text-end">$<?= number_format($pedido['subtotal'], 2) ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end text-muted small">Envío</td>
                                <td class="text-end">
                                    <?= $pedido['costo_envio'] > 0 ? '$' . number_format($pedido['costo_envio'], 2) : '<span class="text-success">Gratis</span>' ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total</td>
                                <td class="text-end fw-bold text-primary fs-5">$<?= number_format($pedido['total'], 2) ?> MXN</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Notas -->
        <?php if ($pedido['notas']): ?>
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-2"><i class="bi bi-chat-left-text me-2 text-primary"></i>Notas del cliente</h6>
                    <p class="mb-0 text-muted"><?= nl2br(htmlspecialchars($pedido['notas'])) ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Panel lateral -->
    <div class="col-lg-4">
        <!-- Cambiar estado -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-arrow-repeat me-2 text-primary"></i>Cambiar Estado</h6>
                <form action="<?= URL_BASE ?>/admin/pedidos/<?= $pedido['id_ct_pedido'] ?>/estado" method="POST">
                    <select name="estado" class="form-select bg-light border-0 rounded-3 mb-3">
                        <?php foreach (['pendiente','confirmado','enviado','entregado','cancelado'] as $est): ?>
                            <option value="<?= $est ?>" <?= $pedido['estado'] === $est ? 'selected' : '' ?>>
                                <?= ucfirst($est) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-primary rounded-pill w-100">Actualizar Estado</button>
                </form>
            </div>
        </div>

        <!-- Datos del cliente -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-person me-2 text-primary"></i>Datos del cliente</h6>
                <div class="d-flex flex-column gap-2 small text-muted">
                    <div><i class="bi bi-person me-2"></i><?= htmlspecialchars($pedido['nombre_cliente']) ?></div>
                    <?php if ($pedido['email_cliente']): ?>
                        <div><i class="bi bi-envelope me-2"></i><?= htmlspecialchars($pedido['email_cliente']) ?></div>
                    <?php endif; ?>
                    <?php if ($pedido['telefono_cliente']): ?>
                        <div><i class="bi bi-telephone me-2"></i><?= htmlspecialchars($pedido['telefono_cliente']) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Datos de entrega -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-geo-alt me-2 text-primary"></i>Dirección de entrega</h6>
                <div class="small text-muted">
                    <p class="mb-1"><?= htmlspecialchars($pedido['direccion_entrega']) ?></p>
                    <p class="mb-1"><?= htmlspecialchars($pedido['municipio'] ?? '') ?><?= $pedido['estado_entrega'] ? ', ' . htmlspecialchars($pedido['estado_entrega']) : '' ?></p>
                    <?php if ($pedido['cp']): ?><p class="mb-0">CP <?= htmlspecialchars($pedido['cp']) ?></p><?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Pago -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-credit-card me-2 text-primary"></i>Método de pago</h6>
                <?php if ($pedido['metodo_pago'] === 'transferencia'): ?>
                    <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2">
                        <i class="bi bi-bank2 me-1"></i>Transferencia bancaria
                    </span>
                <?php elseif ($pedido['metodo_pago'] === 'tarjeta_stripe'): ?>
                    <span class="badge rounded-pill px-3 py-2" style="background:#f0edff;color:#6772e5;">
                        <i class="bi bi-credit-card-2-front me-1"></i>Tarjeta — Stripe
                    </span>
                    <?php if ($pedido['stripe_intent_id']): ?>
                        <p class="text-muted small mt-2 mb-0">
                            Ref: <code><?= htmlspecialchars($pedido['stripe_intent_id']) ?></code>
                        </p>
                    <?php endif; ?>
                <?php else: ?>
                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                        <i class="bi bi-cash-stack me-1"></i>Pago contra entrega
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
