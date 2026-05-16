<div class="mb-4">
    <a href="<?= URL_BASE ?>/admin/categorias" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i> Volver al listado
    </a>
    <h1 class="h3 fw-bold text-dark mt-2"><?= $categoria ? 'Editar' : 'Nueva' ?> Línea de Productos</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="<?= URL_BASE ?>/admin/categorias/<?= $categoria ? 'editar/'.$categoria['id_ct_categoria'] : 'crear' ?>" method="POST">
                    <div class="mb-4">
                        <label for="nombre" class="form-label fw-bold">Nombre de la Línea de Productos</label>
                        <input type="text" class="form-control form-control-lg bg-light border-0" 
                               id="nombre" name="nombre" 
                               value="<?= $categoria ? htmlspecialchars($categoria['nombre']) : '' ?>" 
                               placeholder="Ej: Sábanas de Algodón" required>
                        <small class="text-muted">El slug se generará automáticamente a partir del nombre.</small>
                    </div>

                    <div class="mb-4">
                        <label for="descripcion" class="form-label fw-bold">Descripción (Opcional)</label>
                        <textarea class="form-control bg-light border-0" id="descripcion" name="descripcion" 
                                  rows="4" placeholder="Breve descripción de los productos en esta línea"><?= $categoria ? htmlspecialchars($categoria['descripcion']) : '' ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold d-block">Estado</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="estado" id="estado1" value="1" 
                                   <?= (!$categoria || $categoria['estado'] == 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="estado1">Activa</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="estado" id="estado0" value="0" 
                                   <?= ($categoria && $categoria['estado'] == 0) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="estado0">Inactiva</label>
                        </div>
                    </div>

                    <hr class="my-4 opacity-10">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= URL_BASE ?>/admin/categorias" class="btn btn-light rounded-pill px-4">Cancelar</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                            <i class="bi bi-save me-2"></i> <?= $categoria ? 'Actualizar' : 'Guardar' ?> Línea de Productos
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-light border-start border-primary border-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i> Ayuda</h5>
                <p class="small text-muted mb-0">
                    Las líneas de productos sirven para organizar el catálogo público. Asegúrate de usar nombres claros y descriptivos. 
                    <br><br>
                    <strong>Nota:</strong> Si desactivas una línea de productos, sus productos asociados dejarán de aparecer en el menú de filtros del catálogo.
                </p>
            </div>
        </div>
    </div>
</div>
