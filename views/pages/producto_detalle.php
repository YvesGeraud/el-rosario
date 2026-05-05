<div class="container py-5">
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/blancos/public/productos">Catálogo</a></li>
    <li class="breadcrumb-item active text-muted"><?= htmlspecialchars($producto['categoria_nombre']) ?></li>
    <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($producto['nombre']) ?></li>
  </ol>
</nav>

<div class="row">
    <!-- Galería de Imágenes -->
    <div class="col-md-6 mb-4">
        <div id="productCarousel" class="carousel slide shadow-sm rounded overflow-hidden" data-bs-ride="carousel">
            <div class="carousel-inner bg-light">
                <?php if (empty($imagenes)): ?>
                    <div class="carousel-item active">
                        <img src="https://via.placeholder.com/600x400?text=Sin+Imagen" class="d-block w-100" alt="Sin imagen">
                    </div>
                <?php else: ?>
                    <?php foreach ($imagenes as $index => $img): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <img src="<?= $img['ruta'] ?>" class="d-block w-100" alt="Imagen del producto" style="height: 450px; object-fit: contain;">
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?php if (count($imagenes) > 1): ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span>
                    <span class="visually-hidden">Siguiente</span>
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Info del Producto -->
    <div class="col-md-6">
        <h1 class="display-6 fw-bold mb-2"><?= htmlspecialchars($producto['nombre']) ?></h1>
        <p class="text-muted mb-4"><?= htmlspecialchars($producto['categoria_nombre']) ?></p>
        
        <h3 class="text-primary mb-4 fw-bold">$<?= number_format($producto['precio_base'], 2) ?></h3>
        
        <div class="mb-4">
            <h5 class="fw-bold">Descripción</h5>
            <p class="text-secondary"><?= nl2br(htmlspecialchars($producto['descripcion'])) ?></p>
        </div>

        <?php if (!empty($variantes)): ?>
            <div class="mb-4">
                <h5 class="fw-bold">Opciones disponibles</h5>
                <ul class="list-group list-group-flush">
                    <?php foreach ($variantes as $v): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center ps-0 border-light">
                            <span>
                                <strong><?= htmlspecialchars($v['tipo']) ?>:</strong> 
                                <?= htmlspecialchars($v['valor']) ?>
                                <?php if ($v['nombre'] !== $v['valor']): ?> 
                                    <small class="text-muted">(<?= htmlspecialchars($v['nombre']) ?>)</small>
                                <?php endif; ?>
                            </span>
                            <?php if ($v['precio_extra'] > 0): ?>
                                <span class="badge bg-success rounded-pill">+$<?= number_format($v['precio_extra'], 2) ?></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="d-grid gap-2 mt-5">
            <a href="https://wa.me/521234567890?text=Hola,%20me%20interesa%20el%20producto:%20<?= urlencode($producto['nombre']) ?>" 
               target="_blank" 
               class="btn btn-success btn-lg shadow-sm">
                <i class="bi bi-whatsapp"></i> Cotizar por WhatsApp
            </a>
            <a href="/blancos/public/contacto?producto=<?= $producto['id_ct_producto'] ?>" class="btn btn-outline-secondary">
                Enviar mensaje de contacto
            </a>
        </div>
    </div>
</div>
