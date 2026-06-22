<div class="container py-5">
    <!-- Header -->
    <div class="account-header mb-5">
        <div class="d-flex align-items-center gap-3 mb-2">
            <div class="account-avatar">
                <?= strtoupper(substr($cliente['nombre'], 0, 1)) ?>
            </div>
            <div>
                <h1 class="mb-0"><?= htmlspecialchars($cliente['nombre']) ?></h1>
                <p class="text-muted mb-0 small"><?= htmlspecialchars($cliente['email']) ?></p>
            </div>
        </div>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($success) ?>
            <?php unset($_SESSION['perfil_success']); ?>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Historial de Pedidos -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4"><i class="bi bi-bag-check me-2 text-primary"></i>Mis Pedidos</h4>

                    <?php if (empty($pedidos)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-bag-x" style="font-size:3rem; color:#dee2e6;"></i>
                            <p class="text-muted mt-3">Aún no has realizado ningún pedido.</p>
                            <a href="<?= URL_BASE ?>/productos" class="btn btn-primary rounded-pill px-4">
                                Explorar productos
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Folio</th>
                                        <th>Fecha</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pedidos as $p): ?>
                                        <tr>
                                            <td><strong class="text-primary"><?= htmlspecialchars($p['folio']) ?></strong></td>
                                            <td class="text-muted small"><?= date('d/m/Y', strtotime($p['fecha_in'])) ?></td>
                                            <td><strong>$<?= number_format($p['total'], 2) ?> MXN</strong></td>
                                            <td>
                                                <?php
                                                    $badges = [
                                                        'pendiente'  => 'warning',
                                                        'confirmado' => 'info',
                                                        'enviado'    => 'primary',
                                                        'entregado'  => 'success',
                                                        'cancelado'  => 'danger',
                                                    ];
                                                    $b = $badges[$p['estado']] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?= $b ?>-subtle text-<?= $b ?> rounded-pill px-3">
                                                    <?= ucfirst($p['estado']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?= URL_BASE ?>/pedido/<?= $p['folio'] ?>" class="btn btn-sm btn-outline-primary rounded-pill">
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
        </div>

        <!-- Datos del perfil -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-person me-2 text-primary"></i>Mis Datos</h5>
                    <form action="<?= URL_BASE ?>/mi-cuenta/actualizar" method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nombre</label>
                            <input type="text" name="nombre" class="form-control bg-light border-0 rounded-3"
                                   value="<?= htmlspecialchars($cliente['nombre']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Teléfono</label>
                            <input type="tel" name="telefono" class="form-control bg-light border-0 rounded-3"
                                   value="<?= htmlspecialchars($cliente['telefono'] ?? '') ?>">
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Dirección de entrega predeterminada</label>
                            <textarea name="direccion_defecto" class="form-control bg-light border-0 rounded-3" rows="3"
                                      placeholder="Calle, número, colonia..."><?= htmlspecialchars($cliente['direccion_defecto'] ?? '') ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill w-100">
                            Guardar cambios
                        </button>
                    </form>
                    <hr>
                    <a href="<?= URL_BASE ?>/logout-cliente" class="btn btn-outline-danger rounded-pill w-100 btn-sm">
                        <i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
