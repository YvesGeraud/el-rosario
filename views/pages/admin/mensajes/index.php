<div class="mb-4">
    <h1 class="h3 fw-bold text-dark">Bandeja de Mensajes</h1>
    <p class="text-muted small">Revisa las consultas y cotizaciones de tus clientes.</p>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Cliente</th>
                        <th class="py-3">Mensaje</th>
                        <th class="py-3">Producto Interés</th>
                        <th class="py-3">Fecha</th>
                        <th class="py-3 text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($mensajes)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                No hay mensajes nuevos.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($mensajes as $m): ?>
                            <tr class="<?= $m['leido'] ? 'opacity-75' : 'bg-light-subtle fw-bold' ?>">
                                <td class="ps-4">
                                    <div class="text-dark"><?= htmlspecialchars($m['nombre']) ?></div>
                                    <small class="text-muted">
                                        <i class="bi bi-envelope me-1"></i> <?= htmlspecialchars($m['email']) ?><br>
                                        <i class="bi bi-telephone me-1"></i> <?= htmlspecialchars($m['telefono']) ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 300px;" title="<?= htmlspecialchars($m['mensaje']) ?>">
                                        <?= htmlspecialchars($m['mensaje']) ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($m['producto_nombre']): ?>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 rounded-pill">
                                            <?= htmlspecialchars($m['producto_nombre']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small">Consulta General</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted small">
                                    <?= date('d/m/Y', strtotime($m['fecha_in'])) ?><br>
                                    <?= date('H:i', strtotime($m['fecha_in'])) ?>
                                </td>
                                <td class="text-end pe-4">
                                    <?php if (!$m['leido']): ?>
                                        <a href="/blancos/public/admin/mensajes/leido/<?= $m['id_dt_contacto'] ?>" 
                                           class="btn btn-sm btn-success rounded-circle me-1" title="Marcar como leído">
                                            <i class="bi bi-check-lg"></i>
                                        </a>
                                    <?php endif; ?>
                                    <button onclick="confirmDelete(<?= $m['id_dt_contacto'] ?>)" 
                                            class="btn btn-sm btn-light border rounded-circle" title="Eliminar">
                                        <i class="bi bi-trash text-danger"></i>
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
    if (confirm('¿Estás seguro de que deseas eliminar este mensaje?')) {
        window.location.href = '/blancos/public/admin/mensajes/eliminar/' + id;
    }
}
</script>

<style>
    .bg-light-subtle { background-color: rgba(44, 152, 169, 0.05); }
</style>
