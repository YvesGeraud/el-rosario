<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Pedidos</h4>
        <p class="text-muted small mb-0">Gestión de pedidos recibidos en la tienda</p>
    </div>
</div>

<?php if (isset($success)): ?>
    <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
        <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($success) ?>
        <?php unset($_SESSION['pedido_success']); ?>
    </div>
<?php endif; ?>

<!-- Filtros por estado -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <div class="d-flex flex-wrap gap-2">
            <?php
                $filtros = [
                    null        => ['Todos', 'secondary', $conteos['todos']],
                    'pendiente' => ['Pendientes', 'warning', $conteos['pendiente']],
                    'confirmado'=> ['Confirmados', 'info', $conteos['confirmado']],
                    'enviado'   => ['Enviados', 'primary', $conteos['enviado']],
                    'entregado' => ['Entregados', 'success', $conteos['entregado']],
                    'cancelado' => ['Cancelados', 'danger', $conteos['cancelado']],
                ];
                foreach ($filtros as $est => $info):
                    $isActive = ($estadoActual === $est);
                    $url = URL_BASE . '/admin/pedidos' . ($est ? '?estado=' . $est : '');
            ?>
                <a href="<?= $url ?>" class="btn btn-sm <?= $isActive ? 'btn-' . $info[1] : 'btn-outline-' . $info[1] ?> rounded-pill">
                    <?= $info[0] ?>
                    <span class="badge bg-white text-<?= $info[1] ?> ms-1 rounded-pill"><?= $info[2] ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Tabla de pedidos -->
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <?php if (empty($pedidos)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-bag-x" style="font-size:3rem;"></i>
                <p class="mt-3">No hay pedidos<?= $estadoActual ? ' con estado "' . ucfirst($estadoActual) . '"' : '' ?>.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4">Folio</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Método de pago</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th class="pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $p):
                            $badges = [
                                'pendiente'  => 'warning',
                                'confirmado' => 'info',
                                'enviado'    => 'primary',
                                'entregado'  => 'success',
                                'cancelado'  => 'danger',
                            ];
                            $b = $badges[$p['estado']] ?? 'secondary';
                        ?>
                            <tr>
                                <td class="ps-4">
                                    <strong class="text-primary"><?= htmlspecialchars($p['folio']) ?></strong>
                                </td>
                                <td>
                                    <div><?= htmlspecialchars($p['nombre_cliente']) ?></div>
                                    <?php if ($p['telefono_cliente']): ?>
                                        <small class="text-muted"><?= htmlspecialchars($p['telefono_cliente']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted small"><?= date('d/m/Y H:i', strtotime($p['fecha_in'])) ?></td>
                                <td>
                                    <?php if ($p['metodo_pago'] === 'transferencia'): ?>
                                        <span class="badge bg-info-subtle text-info rounded-pill px-3">
                                            <i class="bi bi-bank2 me-1"></i>Transferencia
                                        </span>
                                    <?php elseif ($p['metodo_pago'] === 'tarjeta_stripe'): ?>
                                        <span class="badge rounded-pill px-3" style="background:#f0edff;color:#6772e5;">
                                            <i class="bi bi-credit-card-2-front me-1"></i>Stripe
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-success-subtle text-success rounded-pill px-3">
                                            <i class="bi bi-cash-stack me-1"></i>Contra entrega
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td><strong>$<?= number_format($p['total'], 2) ?></strong></td>
                                <td>
                                    <span class="badge bg-<?= $b ?>-subtle text-<?= $b ?> rounded-pill px-3">
                                        <?= ucfirst($p['estado']) ?>
                                    </span>
                                </td>
                                <td class="pe-4">
                                    <a href="<?= URL_BASE ?>/admin/pedidos/<?= $p['id_ct_pedido'] ?>"
                                       class="btn btn-sm btn-outline-primary rounded-pill">
                                        Ver detalle
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
