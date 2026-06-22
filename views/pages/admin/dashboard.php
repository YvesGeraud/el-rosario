<?php
    $pedidoModel = new \App\Models\Pedido();
    $pendientes  = $pedidoModel->countByEstado('pendiente');
    $totalMes    = $pedidoModel->totalMes();
    $totalPedidos= $pedidoModel->countByEstado();
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 p-5 mb-4 text-white"
             style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="fw-bold mb-2">¡Bienvenido, <?= htmlspecialchars($user) ?>!</h1>
                    <p class="mb-0 opacity-75 fs-5">Panel de Control Administrativo | Blancos El Rosario</p>
                </div>
                <div class="col-md-4 text-end d-none d-md-block">
                    <i class="bi bi-shield-check" style="font-size: 4rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Métricas de pedidos -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4 d-flex align-items-center gap-3">
                <div style="width:52px;height:52px;border-radius:14px;background:rgba(255,193,7,0.12);display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-hourglass-split text-warning fs-4"></i>
                </div>
                <div>
                    <div class="fs-2 fw-bold"><?= $pendientes ?></div>
                    <div class="text-muted small">Pedidos pendientes</div>
                </div>
                <?php if ($pendientes > 0): ?>
                    <a href="<?= URL_BASE ?>/admin/pedidos?estado=pendiente" class="btn btn-sm btn-warning rounded-pill ms-auto">Ver</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4 d-flex align-items-center gap-3">
                <div style="width:52px;height:52px;border-radius:14px;background:rgba(25,135,84,0.1);display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-currency-dollar text-success fs-4"></i>
                </div>
                <div>
                    <div class="fs-2 fw-bold">$<?= number_format($totalMes, 0) ?></div>
                    <div class="text-muted small">Ingresos este mes</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4 d-flex align-items-center gap-3">
                <div style="width:52px;height:52px;border-radius:14px;background:rgba(13,110,253,0.1);display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-bag-check text-primary fs-4"></i>
                </div>
                <div>
                    <div class="fs-2 fw-bold"><?= $totalPedidos ?></div>
                    <div class="text-muted small">Pedidos totales</div>
                </div>
                <a href="<?= URL_BASE ?>/admin/pedidos" class="btn btn-sm btn-outline-primary rounded-pill ms-auto">Ver todos</a>
            </div>
        </div>
    </div>
</div>

<!-- Accesos rápidos -->
<div class="row g-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 rounded-4">
            <div class="card-body p-4 text-center">
                <div class="display-4 text-primary mb-2"><i class="bi bi-bag-check-fill"></i></div>
                <h5 class="fw-bold">Pedidos</h5>
                <p class="text-muted small">Gestiona los pedidos de tus clientes.</p>
                <a href="<?= URL_BASE ?>/admin/pedidos" class="btn btn-outline-primary rounded-pill px-4">Ver Pedidos</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 rounded-4">
            <div class="card-body p-4 text-center">
                <div class="display-4 text-primary mb-2"><i class="bi bi-box-seam"></i></div>
                <h5 class="fw-bold">Productos</h5>
                <p class="text-muted small">Crea, edita o elimina productos.</p>
                <a href="<?= URL_BASE ?>/admin/productos" class="btn btn-outline-primary rounded-pill px-4">Administrar</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 rounded-4">
            <div class="card-body p-4 text-center">
                <div class="display-4 text-primary mb-2"><i class="bi bi-tags"></i></div>
                <h5 class="fw-bold">Líneas</h5>
                <p class="text-muted small">Organiza por tipo de producto.</p>
                <a href="<?= URL_BASE ?>/admin/categorias" class="btn btn-outline-primary rounded-pill px-4">Administrar</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 rounded-4">
            <div class="card-body p-4 text-center">
                <div class="display-4 text-primary mb-2"><i class="bi bi-chat-dots"></i></div>
                <h5 class="fw-bold">Mensajes</h5>
                <p class="text-muted small">Revisa los mensajes de clientes.</p>
                <a href="<?= URL_BASE ?>/admin/mensajes" class="btn btn-outline-primary rounded-pill px-4">Ver Mensajes</a>
            </div>
        </div>
    </div>
</div>

