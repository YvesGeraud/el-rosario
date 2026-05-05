<div class="container py-5">
<div class="row">
    <!-- Filtros (Lateral) -->
    <div class="col-md-3 mb-4">
        <h4 class="mb-3">Categorías</h4>
        <div class="list-group shadow-sm">
            <a href="/blancos/public/productos" 
               class="list-group-item list-group-item-action <?= !$categoriaActual ? 'active' : '' ?>">
                Todos los productos
            </a>
            <?php foreach ($categorias as $cat): ?>
                <a href="/blancos/public/productos?categoria=<?= $cat['slug'] ?>" 
                   class="list-group-item list-group-item-action <?= ($categoriaActual && $categoriaActual['id_ct_categoria'] == $cat['id_ct_categoria']) ? 'active' : '' ?>">
                    <?= htmlspecialchars($cat['nombre']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Lista de Productos -->
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><?= $categoriaActual ? htmlspecialchars($categoriaActual['nombre']) : 'Nuestro Catálogo' ?></h1>
            <span class="text-muted"><?= count($productos) ?> productos encontrados</span>
        </div>

        <?php if ($categoriaActual && !empty($categoriaActual['descripcion'])): ?>
            <p class="text-muted mb-4"><?= htmlspecialchars($categoriaActual['descripcion']) ?></p>
        <?php endif; ?>

        <div class="row">
            <?php if (empty($productos)): ?>
                <div class="col-12">
                    <div class="alert alert-info shadow-sm">
                        No hay productos en esta categoría por el momento.
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($productos as $producto): ?>
                    <div class="col-sm-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm border-0 product-card">
                            <div class="position-relative overflow-hidden">
                                <img src="<?= $producto['imagen_principal'] ?? 'https://via.placeholder.com/300x200?text=Sin+Imagen' ?>" 
                                     class="card-img-top" 
                                     alt="<?= htmlspecialchars($producto['nombre']) ?>"
                                     style="height: 200px; object-fit: cover; transition: transform 0.3s;">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold" style="font-size: 1.1rem;"><?= htmlspecialchars($producto['nombre']) ?></h5>
                                <p class="card-text text-muted small flex-grow-1">
                                    <?= substr(htmlspecialchars($producto['descripcion']), 0, 60) ?>...
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="h5 mb-0 text-primary fw-bold">$<?= number_format($producto['precio_base'], 2) ?></span>
                                    <a href="/blancos/public/producto/<?= $producto['slug'] ?>" class="btn btn-primary btn-sm rounded-pill px-3">Ver más</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>
