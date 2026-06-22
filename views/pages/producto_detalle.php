<div class="container py-5">
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= URL_BASE ?>/productos">Catálogo</a></li>
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
                            <img src="<?= $img['ruta'] ?>" class="d-block w-100" alt="Imagen del producto"
                                 style="height: 450px; object-fit: contain;">
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

        <!-- Miniaturas -->
        <?php if (count($imagenes) > 1): ?>
            <div class="d-flex gap-2 mt-3 flex-wrap">
                <?php foreach ($imagenes as $idx => $img): ?>
                    <img src="<?= $img['ruta'] ?>" alt="Miniatura" class="product-thumbnail <?= $idx === 0 ? 'active-thumb' : '' ?>"
                         onclick="switchSlide(<?= $idx ?>, this)"
                         style="width:70px;height:70px;object-fit:cover;border-radius:10px;cursor:pointer;
                                border:2px solid <?= $idx === 0 ? 'var(--primary)' : '#dee2e6' ?>;">
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Info del Producto -->
    <div class="col-md-6">
        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 mb-2">
            <?= htmlspecialchars($producto['categoria_nombre']) ?>
        </span>
        <h1 class="display-6 fw-bold mb-2"><?= htmlspecialchars($producto['nombre']) ?></h1>

        <div class="precio-display mb-4">
            <span class="h2 fw-bold text-primary" id="precio-display">
                $<?= number_format($producto['precio_base'], 2) ?>
            </span>
            <span class="text-muted ms-2 small">MXN</span>
        </div>

        <div class="mb-4">
            <h5 class="fw-bold">Descripción</h5>
            <p class="text-secondary"><?= nl2br(htmlspecialchars($producto['descripcion'])) ?></p>
        </div>

        <?php if (!empty($variantes)): ?>
            <?php
                $variantesPorTipo = [];
                foreach ($variantes as $v) {
                    $variantesPorTipo[$v['tipo']][] = $v;
                }
            ?>
            <div class="mb-4">
                <?php foreach ($variantesPorTipo as $tipo => $opts): ?>
                    <div class="mb-3">
                        <h5 class="fw-bold mb-2"><?= htmlspecialchars($tipo) ?></h5>
                        <div class="variante-selector d-flex flex-wrap gap-2">
                            <?php foreach ($opts as $v): ?>
                                <button type="button"
                                        class="btn-variante"
                                        data-id="<?= $v['id_dt_variantes_producto'] ?>"
                                        data-precio-extra="<?= $v['precio_extra'] ?>"
                                        data-label="<?= htmlspecialchars($tipo . ': ' . $v['valor']) ?>"
                                        onclick="selectVariante(this)">
                                    <?= htmlspecialchars($v['valor']) ?>
                                    <?php if ($v['precio_extra'] > 0): ?>
                                        <small>+$<?= number_format($v['precio_extra'], 2) ?></small>
                                    <?php endif; ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Selector de cantidad -->
        <div class="mb-4">
            <h5 class="fw-bold mb-2">Cantidad</h5>
            <div class="qty-control-lg">
                <button type="button" class="qty-btn-lg" onclick="changeQty(-1)">
                    <i class="bi bi-dash"></i>
                </button>
                <span id="qty-value" class="qty-number">1</span>
                <button type="button" class="qty-btn-lg" onclick="changeQty(1)">
                    <i class="bi bi-plus"></i>
                </button>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="d-grid gap-2 mt-4">
            <button id="btn-add-cart" class="btn btn-primary btn-lg shadow-sm" onclick="addToCart()">
                <i class="bi bi-bag-plus me-2"></i>Agregar al Carrito
            </button>
            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $config['contacto_whatsapp'] ?? '521234567890') ?>?text=Hola,%20me%20interesa%20el%20producto:%20<?= urlencode($producto['nombre']) ?>"
               target="_blank"
               class="btn btn-outline-success">
                <i class="bi bi-whatsapp me-2"></i>Consultar por WhatsApp
            </a>
        </div>

        <!-- Toast de confirmación -->
        <div id="cart-toast" class="cart-toast" style="display:none;">
            <i class="bi bi-check-circle-fill text-success me-2"></i>¡Producto agregado al carrito!
            <a href="<?= URL_BASE ?>/carrito" class="ms-2 fw-bold">Ver carrito →</a>
        </div>
    </div>
</div>
</div>

<input type="hidden" id="producto-id"  value="<?= $producto['id_ct_producto'] ?>">
<input type="hidden" id="precio-base"  value="<?= $producto['precio_base'] ?>">

<script>
let precioBase     = parseFloat(document.getElementById('precio-base').value);
let precioExtra    = 0;
let cantidadActual = 1;
let varianteId     = null;

function switchSlide(idx, thumb) {
    const items = document.querySelectorAll('#productCarousel .carousel-item');
    items.forEach((el, i) => el.classList.toggle('active', i === idx));
    document.querySelectorAll('.product-thumbnail').forEach(t => t.style.borderColor = '#dee2e6');
    thumb.style.borderColor = 'var(--primary)';
}

function selectVariante(btn) {
    btn.closest('.variante-selector').querySelectorAll('.btn-variante').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    varianteId  = btn.dataset.id;
    precioExtra = parseFloat(btn.dataset.precioExtra || 0);
    actualizarPrecio();
}

function actualizarPrecio() {
    const total = precioBase + precioExtra;
    document.getElementById('precio-display').textContent =
        '$' + total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

function changeQty(delta) {
    cantidadActual = Math.max(1, cantidadActual + delta);
    document.getElementById('qty-value').textContent = cantidadActual;
}

function addToCart() {
    const btn = document.getElementById('btn-add-cart');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Agregando...';

    fetch('<?= URL_BASE ?>/carrito/agregar', {
        method  : 'POST',
        headers : { 'Content-Type': 'application/x-www-form-urlencoded' },
        body    : new URLSearchParams({
            id_producto : document.getElementById('producto-id').value,
            variante_id : varianteId || '',
            cantidad    : cantidadActual,
        }).toString(),
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-bag-plus me-2"></i>Agregar al Carrito';
        if (data.success) {
            const badge = document.getElementById('nav-cart-badge');
            if (badge) {
                badge.textContent = data.cart_count;
                badge.classList.remove('d-none');
            }
            const toast = document.getElementById('cart-toast');
            toast.style.display = 'flex';
            setTimeout(() => toast.style.display = 'none', 4000);
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-bag-plus me-2"></i>Agregar al Carrito';
    });
}
</script>
