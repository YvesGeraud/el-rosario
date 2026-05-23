<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold text-dark mb-0">Gestión de Productos</h1>
    <a href="<?= URL_BASE ?>/admin/productos/crear" class="btn btn-primary rounded-pill px-4">
        <i class="bi bi-plus-lg me-2"></i> Nuevo Producto
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Producto</th>
                        <th class="py-3">Línea de Productos</th>
                        <th class="py-3">Precio Base</th>
                        <th class="py-3">Estado</th>
                        <th class="py-3">Destacado</th>
                        <th class="py-3 text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($productos)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                No hay productos registrados aún.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($productos as $p): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <img src="<?= $p['imagen_principal'] ?? 'https://via.placeholder.com/50x50' ?>" 
                                             alt="Thumbnail" class="rounded me-3 shadow-sm" style="width: 45px; height: 45px; object-fit: cover;">
                                        <div>
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($p['nombre']) ?></div>
                                            <small class="text-muted">/<?= htmlspecialchars($p['slug']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($p['categoria_nombre']) ?></span></td>
                                <td class="fw-bold text-primary">$<?= number_format($p['precio_base'], 2) ?></td>
                                <td>
                                    <?php if ($p['estado'] == 1): ?>
                                        <span class="badge bg-success-subtle text-success px-3 rounded-pill">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger px-3 rounded-pill">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($p['destacado'] == 1): ?>
                                        <span class="badge bg-warning-subtle text-warning border border-warning px-2"><i class="bi bi-star-fill"></i></span>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="<?= URL_BASE ?>/admin/productos/editar/<?= $p['id_ct_producto'] ?>" 
                                       class="btn btn-sm btn-light border rounded-circle me-1" title="Editar">
                                        <i class="bi bi-pencil-fill text-primary"></i>
                                    </a>
                                    <button onclick="confirmDelete(<?= $p['id_ct_producto'] ?>)" 
                                            class="btn btn-sm btn-light border rounded-circle" title="Eliminar">
                                        <i class="bi bi-trash-fill text-danger"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('¿Estás seguro de que deseas desactivar este producto?')) {
        window.location.href = '<?= URL_BASE ?>/admin/productos/eliminar/' + id;
    }
}
</script>
