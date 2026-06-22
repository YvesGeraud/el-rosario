<div class="container py-5">
    <div class="row">
        <!-- Filtros (Lateral) -->
        <div class="col-md-3 mb-4">
            <h4 class="mb-3">Líneas de productos</h4>
            <div class="list-group shadow-sm">
                <a href="<?= URL_BASE ?>/productos"
                    class="list-group-item list-group-item-action <?= !$categoriaActual ? 'active' : '' ?>">
                    Todas las líneas
                </a>
                <?php foreach ($categorias as $cat): ?>
                    <a href="<?= URL_BASE ?>/productos?categoria=<?= $cat['slug'] ?>"
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
                            No hay productos en esta línea por el momento.
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($productos as $producto): ?>
                        <div class="col-sm-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm border-0 product-card">
                                <div class="position-relative overflow-hidden">
                                    <img src="<?= $producto['imagen_principal'] ?? 'https://via.placeholder.com/300x200?text=Sin+Imagen' ?>"
                                        class="card-img-top" alt="<?= htmlspecialchars($producto['nombre']) ?>"
                                        style="height: 200px; object-fit: cover; transition: transform 0.3s;">
                                </div>
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title fw-bold" style="font-size: 1.1rem;">
                                            <?= htmlspecialchars($producto['nombre']) ?></h5>
                                        <p class="card-text text-muted small flex-grow-1">
                                            <?= substr(htmlspecialchars($producto['descripcion']), 0, 60) ?>...
                                        </p>
                                        <div class="mt-3">
                                            <span class="h5 mb-0 text-primary fw-bold d-block mb-2">
                                                $<?= number_format($producto['precio_base'], 2) ?> MXN
                                            </span>
                                            <div class="d-flex gap-2">
                                                <a href="<?= URL_BASE ?>/producto/<?= $producto['slug'] ?>"
                                                   class="btn btn-outline-primary btn-sm rounded-pill flex-grow-1">Ver más</a>
                                                <button type="button"
                                                        class="btn btn-primary btn-sm rounded-pill"
                                                        title="Agregar al carrito"
                                                        onclick="addToCartQuick(<?= $producto['id_ct_producto'] ?>, this)">
                                                    <i class="bi bi-bag-plus"></i>
                                                </button>
                                            </div>
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

<script>
function addToCartQuick(id, btn) {
    const icon = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

    fetch('<?= URL_BASE ?>/carrito/agregar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ id_producto: id, cantidad: 1 }).toString(),
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        if (data.success) {
            btn.innerHTML = '<i class="bi bi-check-lg"></i>';
            const badge = document.getElementById('nav-cart-badge');
            if (badge) {
                badge.textContent = data.cart_count;
                badge.classList.remove('d-none');
            }
            setTimeout(() => { btn.innerHTML = icon; }, 2000);
        } else {
            btn.innerHTML = icon;
        }
    })
    .catch(() => { btn.disabled = false; btn.innerHTML = icon; });
}
</script>