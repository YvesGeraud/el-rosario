<!-- Hero Carrusel -->
<div id="homeCarousel" class="carousel slide hero-carousel mb-0" data-bs-ride="carousel" data-bs-interval="5000">
    <div class="carousel-indicators">
        <?php foreach ($destacados as $i => $p): ?>
            <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="<?= $i ?>"
                    class="<?= $i === 0 ? 'active' : '' ?>"></button>
        <?php endforeach; ?>
    </div>

    <div class="carousel-inner">
        <?php if (empty($destacados)): ?>
            <div class="carousel-item active">
                <div class="hero-fallback">
                    <h1 class="display-4 fw-bold mb-3">Blancos El Rosario</h1>
                    <p class="lead mb-4">Calidad y confort para tu hogar desde el primer día.</p>
                    <a href="/blancos/public/productos" class="btn btn-primary btn-lg px-5">Explorar Catálogo</a>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($destacados as $i => $prod): ?>
                <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                    <div class="hero-slide">
                        <img src="<?= $prod['imagen_principal'] ?>"
                             alt="<?= htmlspecialchars($prod['nombre']) ?>">
                        <div class="hero-overlay"></div>
                        <div class="hero-caption">
                            <span class="badge-cat"><?= htmlspecialchars($prod['categoria_nombre'] ?? 'Destacado') ?></span>
                            <h2><?= htmlspecialchars($prod['nombre']) ?></h2>
                            <p>Descubre la calidad y el confort que solo Blancos El Rosario puede ofrecerte.</p>
                            <div class="d-flex gap-3">
                                <a href="/blancos/public/producto/<?= $prod['slug'] ?>"
                                   class="btn btn-primary px-4">
                                    Ver Detalles
                                </a>
                                <a href="/blancos/public/productos" class="btn btn-outline-light px-4">
                                    Ver Catálogo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if (count($destacados) > 1): ?>
        <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    <?php endif; ?>
</div>

<!-- Feature Cards -->
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Lo que nos hace diferentes</h2>
        <p class="text-muted">Comprometidos con la calidad de tu descanso</p>
    </div>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="feature-card h-100">
                <div class="feature-icon"><i class="bi bi-stars"></i></div>
                <h5 class="fw-bold mb-2">Calidad Premium</h5>
                <p class="text-muted small mb-0">Materiales seleccionados: algodón, microfibra y pluma natural para el máximo confort.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card h-100">
                <div class="feature-icon"><i class="bi bi-palette2"></i></div>
                <h5 class="fw-bold mb-2">Diseños Exclusivos</h5>
                <p class="text-muted small mb-0">Colecciones modernas y atemporales que se adaptan a cualquier decoración del hogar.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card h-100">
                <div class="feature-icon"><i class="bi bi-truck"></i></div>
                <h5 class="fw-bold mb-2">Entrega Directa</h5>
                <p class="text-muted small mb-0">Cotiza por WhatsApp y recibe tu pedido directamente en la puerta de tu casa.</p>
            </div>
        </div>
    </div>
</div>
