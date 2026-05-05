<?php 
    $configModel = new \App\Models\Configuracion();
    $cfg = $configModel->getAll();
?>

<div class="container py-5">
<div class="row mb-5">
    <div class="col-lg-12 text-center mb-4">
        <h1 class="display-5 fw-bold text-dark">Contáctanos</h1>
        <p class="lead text-muted">Estamos para servirte. Envíanos tus dudas o cotizaciones.</p>
    </div>
</div>

<div class="row g-5">
    <!-- Información de contacto -->
    <div class="col-md-5">
        <div class="card border-0 shadow-sm rounded-4 bg-primary text-white p-4 h-100">
            <h3 class="fw-bold mb-4">Información de Contacto</h3>
            <p class="opacity-75 mb-5">Si prefieres atención inmediata, puedes contactarnos por estos medios:</p>
            
            <div class="d-flex align-items-center mb-4">
                <div class="bg-white text-primary rounded-circle p-3 me-3">
                    <i class="bi bi-geo-alt-fill fs-4"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Ubicación</h6>
                    <p class="mb-0 opacity-75"><?= nl2br(htmlspecialchars($cfg['contacto_direccion'] ?? '')) ?></p>
                </div>
            </div>

            <div class="d-flex align-items-center mb-4">
                <div class="bg-white text-primary rounded-circle p-3 me-3">
                    <i class="bi bi-telephone-fill fs-4"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Teléfono</h6>
                    <p class="mb-0 opacity-75"><?= htmlspecialchars($cfg['contacto_telefono'] ?? '') ?></p>
                </div>
            </div>

            <div class="d-flex align-items-center mb-4">
                <div class="bg-white text-primary rounded-circle p-3 me-3">
                    <i class="bi bi-envelope-fill fs-4"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Correo Electrónico</h6>
                    <p class="mb-0 opacity-75"><?= htmlspecialchars($cfg['contacto_email'] ?? '') ?></p>
                </div>
            </div>

            <div class="mt-auto">
                <h6 class="fw-bold mb-3">Síguenos en redes sociales</h6>
                <div class="d-flex gap-3">
                    <?php if (!empty($cfg['redes_facebook'])): ?>
                        <a href="<?= $cfg['redes_facebook'] ?>" target="_blank" class="btn btn-outline-light rounded-circle"><i class="bi bi-facebook"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($cfg['redes_instagram'])): ?>
                        <a href="<?= $cfg['redes_instagram'] ?>" target="_blank" class="btn btn-outline-light rounded-circle"><i class="bi bi-instagram"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($cfg['contacto_whatsapp'])): ?>
                        <a href="https://wa.me/<?= $cfg['contacto_whatsapp'] ?>" target="_blank" class="btn btn-outline-light rounded-circle"><i class="bi bi-whatsapp"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="col-md-7">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="card-body">
                <?php if (isset($_SESSION['contact_success'])): ?>
                    <div class="alert alert-success border-0 shadow-sm mb-4">
                        <i class="bi bi-check-circle-fill me-2"></i> <?= $_SESSION['contact_success'] ?>
                    </div>
                    <?php unset($_SESSION['contact_success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['contact_error'])): ?>
                    <div class="alert alert-danger border-0 shadow-sm mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $_SESSION['contact_error'] ?>
                    </div>
                    <?php unset($_SESSION['contact_error']); ?>
                <?php endif; ?>

                <form action="<?= URL_BASE ?>/contacto" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label fw-bold small text-uppercase">Nombre Completo</label>
                            <input type="text" class="form-control bg-light border-0 py-3" id="nombre" name="nombre" placeholder="Tu nombre..." required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-bold small text-uppercase">Correo Electrónico</label>
                            <input type="email" class="form-control bg-light border-0 py-3" id="email" name="email" placeholder="correo@ejemplo.com" required>
                        </div>
                        <div class="col-md-12">
                            <label for="telefono" class="form-label fw-bold small text-uppercase">Teléfono</label>
                            <input type="tel" class="form-control bg-light border-0 py-3" id="telefono" name="telefono" placeholder="10 dígitos">
                        </div>
                        <div class="col-md-12">
                            <label for="id_ct_producto" class="form-label fw-bold small text-uppercase">Me interesa el producto (Opcional)</label>
                            <select class="form-select bg-light border-0 py-3" id="id_ct_producto" name="id_ct_producto">
                                <option value="">Información general</option>
                                <?php 
                                    $pModel = new \App\Models\Producto();
                                    $productos = $pModel->getAllActive(50);
                                    $pId = \App\Core\Request::get('producto');
                                    foreach ($productos as $p): 
                                ?>
                                    <option value="<?= $p['id_ct_producto'] ?>" <?= $pId == $p['id_ct_producto'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($p['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="mensaje" class="form-label fw-bold small text-uppercase">Mensaje</label>
                            <textarea class="form-control bg-light border-0 py-3" id="mensaje" name="mensaje" rows="5" placeholder="¿En qué podemos ayudarte?" required></textarea>
                        </div>
                        <div class="col-md-12 text-end mt-4">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-sm">
                                Enviar Mensaje <i class="bi bi-send ms-2"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
