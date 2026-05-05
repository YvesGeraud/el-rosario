<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 p-5 mb-4 text-white" 
             style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="fw-bold mb-2">¡Bienvenido, <?= $user ?>!</h1>
                    <p class="mb-0 opacity-75 fs-5">Panel de Control Administrativo | Blancos El Rosario</p>
                </div>
                <div class="col-md-4 text-end d-none d-md-block">
                    <i class="bi bi-shield-check" style="font-size: 4rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 rounded-4">
            <div class="card-body p-4 text-center">
                <div class="display-4 text-primary mb-2"><i class="bi bi-box-seam"></i></div>
                <h5 class="fw-bold">Gestión de Productos</h5>
                <p class="text-muted small">Crea, edita o elimina los productos de tu catálogo.</p>
                <a href="<?= URL_BASE ?>/admin/productos" class="btn btn-outline-primary rounded-pill px-4">Administrar</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 rounded-4">
            <div class="card-body p-4 text-center">
                <div class="display-4 text-primary mb-2"><i class="bi bi-tags"></i></div>
                <h5 class="fw-bold">Categorías</h5>
                <p class="text-muted small">Organiza tus productos por tipo (Sábanas, Almohadas, etc).</p>
                <a href="<?= URL_BASE ?>/admin/categorias" class="btn btn-outline-primary rounded-pill px-4">Administrar</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 rounded-4">
            <div class="card-body p-4 text-center">
                <div class="display-4 text-primary mb-2"><i class="bi bi-chat-dots"></i></div>
                <h5 class="fw-bold">Mensajes</h5>
                <p class="text-muted small">Revisa los mensajes que te han enviado tus clientes.</p>
                <a href="<?= URL_BASE ?>/admin/mensajes" class="btn btn-outline-primary rounded-pill px-4">Ver Mensajes</a>
            </div>
        </div>
    </div>
</div>
