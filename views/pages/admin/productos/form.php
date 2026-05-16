<div class="mb-4">
    <a href="<?= URL_BASE ?>/admin/productos" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i> Volver al listado
    </a>
    <h1 class="h3 fw-bold text-dark mt-2"><?= $producto ? 'Editar' : 'Nuevo' ?> Producto</h1>
</div>

<form action="<?= URL_BASE ?>/admin/productos/<?= $producto ? 'editar/'.$producto['id_ct_producto'] : 'crear' ?>" method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <!-- Columna Izquierda: Datos Básicos -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 border-bottom pb-2">Información General</h5>
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre del Producto</label>
                        <input type="text" class="form-control bg-light border-0" id="nombre" name="nombre" 
                               value="<?= $producto ? htmlspecialchars($producto['nombre']) : '' ?>" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="id_ct_categoria" class="form-label fw-bold">Línea de Productos</label>
                            <select class="form-select bg-light border-0" id="id_ct_categoria" name="id_ct_categoria" required>
                                <option value="">Seleccionar línea de productos...</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= $cat['id_ct_categoria'] ?>" <?= ($producto && $producto['id_ct_categoria'] == $cat['id_ct_categoria']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="precio_base" class="form-label fw-bold">Precio Base</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">$</span>
                                <input type="number" step="0.01" class="form-control bg-light border-0" id="precio_base" name="precio_base" 
                                       value="<?= $producto ? $producto['precio_base'] : '' ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label fw-bold">Descripción</label>
                        <textarea class="form-control bg-light border-0" id="descripcion" name="descripcion" rows="5"><?= $producto ? htmlspecialchars($producto['descripcion']) : '' ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Variantes -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                        <h5 class="fw-bold mb-0">Variantes (Tallas, Colores, etc.)</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" id="add-variant">
                            <i class="bi bi-plus-circle me-1"></i> Añadir Variante
                        </button>
                    </div>
                    
                    <div id="variants-container">
                        <?php if (empty($variantes)): ?>
                            <div class="alert alert-light border border-dashed text-center py-4 variant-empty-msg">
                                No hay variantes agregadas. Haz clic en "Añadir Variante" para empezar.
                            </div>
                        <?php else: ?>
                            <?php foreach ($variantes as $index => $v): ?>
                                <div class="row g-2 mb-3 variant-row shadow-sm p-3 rounded-3 bg-white border">
                                    <div class="col-md-3">
                                        <input type="text" class="form-control form-control-sm" name="variantes[<?= $index ?>][nombre]" placeholder="Nombre (Ej: Matrimonial)" value="<?= htmlspecialchars($v['nombre']) ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-select form-select-sm" name="variantes[<?= $index ?>][tipo]">
                                            <option value="Talla" <?= $v['tipo'] == 'Talla' ? 'selected' : '' ?>>Talla</option>
                                            <option value="Color" <?= $v['tipo'] == 'Color' ? 'selected' : '' ?>>Color</option>
                                            <option value="Material" <?= $v['tipo'] == 'Material' ? 'selected' : '' ?>>Material</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control form-control-sm" name="variantes[<?= $index ?>][valor]" placeholder="Valor (Ej: XL)" value="<?= htmlspecialchars($v['valor']) ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" step="0.01" class="form-control form-control-sm" name="variantes[<?= $index ?>][precio_extra]" placeholder="+ Precio" value="<?= $v['precio_extra'] ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" class="form-control form-control-sm" name="variantes[<?= $index ?>][stock]" placeholder="Stock" value="<?= $v['stock'] ?>">
                                    </div>
                                    <div class="col-md-1 text-end">
                                        <button type="button" class="btn btn-sm btn-outline-danger border-0 remove-variant"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Multimedia y Opciones -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 border-bottom pb-2">Imágenes</h5>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Subir nuevas imágenes</label>
                        <input type="file" class="form-control bg-light border-0" name="imagenes[]" multiple accept="image/*">
                        <small class="text-muted d-block mt-2">Puedes seleccionar varios archivos.</small>
                    </div>

                    <?php if (!empty($imagenes)): ?>
                        <div class="row g-2">
                            <?php foreach ($imagenes as $img): ?>
                                <div class="col-4">
                                    <div class="position-relative">
                                        <img src="<?= $img['ruta'] ?>" class="img-fluid rounded border shadow-sm" style="height: 80px; width: 100%; object-fit: cover;">
                                        <?php if ($img['es_principal']): ?>
                                            <span class="badge bg-primary position-absolute top-0 start-0 m-1" style="font-size: 0.5rem;">P</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 border-bottom pb-2">Publicación</h5>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="destacado" name="destacado" <?= ($producto && $producto['destacado']) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold" for="destacado">Producto Destacado</label>
                        <small class="text-muted d-block">Aparecerá en el carrusel de inicio.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold d-block">Estado</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="estado" id="est1" value="1" <?= (!$producto || $producto['estado'] == 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="est1">Activo</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="estado" id="est0" value="0" <?= ($producto && $producto['estado'] == 0) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="est0">Inactivo</label>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold">
                            <i class="bi bi-save me-2"></i> <?= $producto ? 'Actualizar' : 'Guardar' ?> Producto
                        </button>
                        <a href="<?= URL_BASE ?>/admin/productos" class="btn btn-light rounded-pill">Cancelar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('variants-container');
    const addButton = document.getElementById('add-variant');
    const emptyMsg = document.querySelector('.variant-empty-msg');
    let variantIndex = <?= count($variantes) ?>;

    addButton.addEventListener('click', function() {
        if (emptyMsg) emptyMsg.style.display = 'none';

        const html = `
            <div class="row g-2 mb-3 variant-row shadow-sm p-3 rounded-3 bg-white border animate__animated animate__fadeInUp">
                <div class="col-md-3">
                    <input type="text" class="form-control form-control-sm" name="variantes[${variantIndex}][nombre]" placeholder="Nombre (Ej: Matrimonial)" required>
                </div>
                <div class="col-md-2">
                    <select class="form-select form-select-sm" name="variantes[${variantIndex}][tipo]">
                        <option value="Talla">Talla</option>
                        <option value="Color">Color</option>
                        <option value="Material">Material</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm" name="variantes[${variantIndex}][valor]" placeholder="Valor (Ej: XL)" required>
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" class="form-control form-control-sm" name="variantes[${variantIndex}][precio_extra]" placeholder="+ Precio">
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control form-control-sm" name="variantes[${variantIndex}][stock]" placeholder="Stock">
                </div>
                <div class="col-md-1 text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger border-0 remove-variant"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        variantIndex++;
    });

    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-variant')) {
            e.target.closest('.variant-row').remove();
            if (container.querySelectorAll('.variant-row').length === 0 && emptyMsg) {
                emptyMsg.style.display = 'block';
            }
        }
    });
});
</script>

<style>
.variant-row { transition: all 0.2s; }
.variant-row:hover { border-color: var(--primary-color) !important; }
.animate__animated { animation-duration: 0.3s; }
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate__fadeInUp { animation-name: fadeInUp; }
</style>
