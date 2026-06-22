<div class="mb-4">
    <h1 class="h3 fw-bold text-dark">Configuración General</h1>
    <p class="text-muted small">Administra los datos de contacto y redes sociales que aparecen en el sitio público.</p>
</div>

<?php if (isset($_SESSION['config_success'])): ?>
    <div class="alert alert-success border-0 shadow-sm mb-4 animate__animated animate__fadeIn">
        <i class="bi bi-check-circle-fill me-2"></i> <?= $_SESSION['config_success'] ?>
    </div>
    <?php unset($_SESSION['config_success']); ?>
<?php endif; ?>

<form action="<?= URL_BASE ?>/admin/configuracion" method="POST">
    <input type="hidden" name="csrf_token" value="<?= \App\Core\Request::csrfToken() ?>">
    <div class="row g-4">
        <!-- Datos de Contacto -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 border-bottom pb-2">
                        <i class="bi bi-person-lines-fill me-2 text-primary"></i> Información de Contacto
                    </h5>
                    
                    <?php foreach ($configuraciones as $conf): ?>
                        <?php if (str_starts_with($conf['clave'], 'contacto_')): ?>
                            <div class="mb-3">
                                <label for="<?= $conf['clave'] ?>" class="form-label fw-bold small text-muted text-uppercase"><?= $conf['descripcion'] ?></label>
                                <?php if ($conf['clave'] === 'contacto_direccion'): ?>
                                    <textarea class="form-control bg-light border-0" id="<?= $conf['clave'] ?>" name="config[<?= $conf['clave'] ?>]" rows="3"><?= htmlspecialchars($conf['valor']) ?></textarea>
                                <?php else: ?>
                                    <input type="text" class="form-control bg-light border-0" id="<?= $conf['clave'] ?>" name="config[<?= $conf['clave'] ?>]" value="<?= htmlspecialchars($conf['valor']) ?>">
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Redes Sociales -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 border-bottom pb-2">
                        <i class="bi bi-share-fill me-2 text-primary"></i> Redes Sociales
                    </h5>

                    <?php foreach ($configuraciones as $conf): ?>
                        <?php if (str_starts_with($conf['clave'], 'redes_')): ?>
                            <div class="mb-3">
                                <label for="<?= $conf['clave'] ?>" class="form-label fw-bold small text-muted text-uppercase"><?= $conf['descripcion'] ?></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="bi bi-<?= str_replace('redes_', '', $conf['clave']) ?>"></i>
                                    </span>
                                    <input type="url" class="form-control bg-light border-0" id="<?= $conf['clave'] ?>" name="config[<?= $conf['clave'] ?>]" value="<?= htmlspecialchars($conf['valor']) ?>" placeholder="https://...">
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <div class="alert alert-info mt-4 border-0 small">
                        <i class="bi bi-info-circle me-1"></i> Estos enlaces aparecerán en el pie de página y en la sección de contacto del sitio público.
                    </div>
                </div>
            </div>
        </div>

        <!-- Envíos -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 border-bottom pb-2">
                        <i class="bi bi-truck me-2 text-primary"></i> Configuración de Envíos
                    </h5>
                    <?php foreach ($configuraciones as $conf): ?>
                        <?php if (str_starts_with($conf['clave'], 'envio_')): ?>
                            <div class="mb-3">
                                <label for="<?= $conf['clave'] ?>" class="form-label fw-bold small text-muted text-uppercase"><?= $conf['descripcion'] ?></label>
                                <input type="text" class="form-control bg-light border-0" id="<?= $conf['clave'] ?>"
                                       name="config[<?= $conf['clave'] ?>]" value="<?= htmlspecialchars($conf['valor']) ?>">
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <div class="alert alert-info mt-3 border-0 small">
                        <i class="bi bi-info-circle me-1"></i> Pon <strong>0</strong> en "Envío gratis desde" para desactivar el envío gratis.
                    </div>
                </div>
            </div>
        </div>

        <!-- Datos bancarios -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 border-bottom pb-2">
                        <i class="bi bi-bank2 me-2 text-primary"></i> Datos para Transferencia Bancaria
                    </h5>
                    <?php foreach ($configuraciones as $conf): ?>
                        <?php if (str_starts_with($conf['clave'], 'banco_')): ?>
                            <div class="mb-3">
                                <label for="<?= $conf['clave'] ?>" class="form-label fw-bold small text-muted text-uppercase"><?= $conf['descripcion'] ?></label>
                                <input type="text" class="form-control bg-light border-0" id="<?= $conf['clave'] ?>"
                                       name="config[<?= $conf['clave'] ?>]" value="<?= htmlspecialchars($conf['valor']) ?>">
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="col-12 text-end mt-4">
            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-sm">
                <i class="bi bi-save me-2"></i> Guardar Cambios
            </button>
        </div>
    </div>
</form>
