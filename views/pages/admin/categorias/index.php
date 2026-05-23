<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold text-dark mb-0">Gestión de Líneas de Productos</h1>
    <a href="<?= URL_BASE ?>/admin/categorias/crear" class="btn btn-primary rounded-pill px-4">
        <i class="bi bi-plus-lg me-2"></i> Nueva Línea de Productos
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Nombre</th>
                        <th class="py-3">Slug</th>
                        <th class="py-3">Estado</th>
                        <th class="py-3">Fecha Registro</th>
                        <th class="py-3 text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($categorias)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                No hay líneas de productos registradas aún.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($categorias as $cat): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-dark"><?= htmlspecialchars($cat['nombre']) ?></td>
                                <td class="text-muted small">/<?= htmlspecialchars($cat['slug']) ?></td>
                                <td>
                                    <?php if ($cat['estado'] == 1): ?>
                                        <span class="badge bg-success-subtle text-success px-3 rounded-pill">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger px-3 rounded-pill">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted small"><?= date('d/m/Y', strtotime($cat['fecha_in'])) ?></td>
                                <td class="text-end pe-4">
                                    <a href="<?= URL_BASE ?>/admin/categorias/editar/<?= $cat['id_ct_categoria'] ?>" 
                                       class="btn btn-sm btn-light border rounded-circle me-1" title="Editar">
                                        <i class="bi bi-pencil-fill text-primary"></i>
                                    </a>
                                    <button onclick="confirmDelete(<?= $cat['id_ct_categoria'] ?>)" 
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
    if (confirm('¿Estás seguro de que deseas desactivar esta línea de productos?')) {
        window.location.href = '<?= URL_BASE ?>/admin/categorias/eliminar/' + id;
    }
}
</script>
