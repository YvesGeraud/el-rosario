<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Blancos El Rosario' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= URL_BASE ?>/css/app.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?= URL_BASE ?>/">
                BLANCOS <span style="color: var(--primary);">EL ROSARIO</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-1">
                    <li class="nav-item"><a class="nav-link" href="<?= URL_BASE ?>/">INICIO</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= URL_BASE ?>/productos">PRODUCTOS</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= URL_BASE ?>/contacto">CONTACTO</a></li>
                    <li class="nav-item ms-2">
                        <a href="https://wa.me/521234567890" target="_blank"
                           class="btn btn-primary btn-sm px-3 d-flex align-items-center gap-1">
                            <i class="bi bi-whatsapp"></i> Cotizar
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido principal (sin padding para el héroe full-width) -->
    <main>
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="py-5 mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <h5 class="text-white fw-bold mb-3">BLANCOS EL ROSARIO</h5>
                    <p class="small" style="color: rgba(255,255,255,0.55);">
                        Tu tienda de confianza en blancos y ropa de cama. Calidad y confort para toda la familia.
                    </p>
                    <div class="d-flex gap-2 mt-3">
                        <a href="#" class="btn btn-sm btn-outline-light rounded-circle p-1" style="width:34px;height:34px;display:grid;place-items:center;"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-light rounded-circle p-1" style="width:34px;height:34px;display:grid;place-items:center;"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-light rounded-circle p-1" style="width:34px;height:34px;display:grid;place-items:center;"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <h6 class="text-white fw-bold mb-3">Navegación</h6>
                    <ul class="list-unstyled small" style="color: rgba(255,255,255,0.55);">
                        <li class="mb-1"><a href="<?= URL_BASE ?>/">Inicio</a></li>
                        <li class="mb-1"><a href="<?= URL_BASE ?>/productos">Catálogo de Productos</a></li>
                        <li class="mb-1"><a href="<?= URL_BASE ?>/contacto">Contáctanos</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="text-white fw-bold mb-3">Contacto</h6>
                    <ul class="list-unstyled small" style="color: rgba(255,255,255,0.55);">
                        <li class="mb-2"><i class="bi bi-geo-alt me-2" style="color:var(--primary)"></i>Rosario, Sinaloa, México</li>
                        <li class="mb-2"><i class="bi bi-telephone me-2" style="color:var(--primary)"></i>+52 123 456 7890</li>
                        <li class="mb-2"><i class="bi bi-envelope me-2" style="color:var(--primary)"></i>contacto@blancoselrosario.com</li>
                    </ul>
                </div>
            </div>
            <hr style="border-color: rgba(255,255,255,0.1); margin: 2rem 0 1rem;">
            <p class="text-center small mb-0" style="color: rgba(255,255,255,0.35);">
                &copy; <?= date('Y') ?> Blancos El Rosario. Todos los derechos reservados.
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
