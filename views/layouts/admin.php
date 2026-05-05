<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin - El Rosario' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= URL_BASE ?>/css/app.css">
    <style>
        /* ── Variables ──────────────────────────────────── */
        :root {
            --sidebar-bg: #0f2d33;
            --sidebar-hover: rgba(255,255,255,0.07);
            --sidebar-active: #2c98a9;
            --sidebar-width: 255px;
            --header-height: 64px;
            --content-bg: #f4f6f9;
        }

        /* ── Base ───────────────────────────────────────── */
        * { box-sizing: border-box; }
        body {
            background: var(--content-bg);
            font-family: 'Inter', sans-serif;
            font-size: 0.875rem;
            color: #374151;
            overflow-x: hidden;
        }

        /* ── Layout ─────────────────────────────────────── */
        .admin-wrapper { display: flex; min-height: 100vh; }

        /* ── Sidebar ────────────────────────────────────── */
        .sidebar {
            width: var(--sidebar-width);
            min-width: var(--sidebar-width);
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            scrollbar-width: none;
            transition: width 0.3s ease;
            z-index: 100;
        }
        .sidebar::-webkit-scrollbar { display: none; }

        /* Logo / Brand */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 22px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            text-decoration: none;
        }
        .sidebar-brand-icon {
            width: 38px; height: 38px;
            background: var(--sidebar-active);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; color: white; flex-shrink: 0;
        }
        .sidebar-brand-text { line-height: 1.2; }
        .sidebar-brand-text strong { color: #fff; font-size: 0.9rem; letter-spacing: 0.5px; display: block; }
        .sidebar-brand-text small { color: rgba(255,255,255,0.35); font-size: 0.7rem; }

        /* Nav Sections */
        .sidebar-section-label {
            padding: 20px 20px 8px;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.25);
        }

        /* Nav Items */
        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 16px;
            margin: 2px 10px;
            border-radius: 10px;
            color: rgba(255,255,255,0.55);
            font-weight: 500;
            font-size: 0.84rem;
            transition: all 0.18s ease;
            text-decoration: none;
        }
        .sidebar .nav-link i {
            font-size: 1.05rem;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
            transition: color 0.18s;
        }
        .sidebar .nav-link:hover {
            color: rgba(255,255,255,0.9);
            background: var(--sidebar-hover);
            transform: translateX(3px);
        }
        .sidebar .nav-link.active {
            color: #fff;
            background: var(--sidebar-active);
            box-shadow: 0 4px 12px rgba(44,152,169,0.35);
        }
        .sidebar .nav-link.active i { color: #fff; }

        /* Sidebar Footer */
        .sidebar-footer {
            margin-top: auto;
            padding: 14px 10px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }
        .sidebar-footer .nav-link {
            color: rgba(255,255,255,0.35);
            font-size: 0.82rem;
        }
        .sidebar-footer .nav-link:hover {
            color: #ff6b6b;
            background: rgba(255,107,107,0.08);
            transform: none;
        }

        /* ── Top Header ─────────────────────────────────── */
        .admin-topbar {
            height: var(--header-height);
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .topbar-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #1f2937;
        }
        .topbar-title small {
            display: block;
            font-size: 0.75rem;
            font-weight: 400;
            color: #9ca3af;
            margin-top: 1px;
        }
        .topbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 6px 14px;
            border-radius: 50px;
            background: #f9fafb;
            border: 1px solid #e9ecef;
            transition: all 0.2s;
            text-decoration: none;
        }
        .topbar-user:hover { background: #f3f4f6; border-color: #d1d5db; }
        .topbar-user-avatar {
            width: 30px; height: 30px;
            background: var(--sidebar-active);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 0.8rem; font-weight: 700;
        }
        .topbar-user-name { font-size: 0.82rem; font-weight: 600; color: #374151; }

        /* ── Main Content ───────────────────────────────── */
        .admin-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            overflow: hidden;
        }
        .admin-content {
            padding: 28px;
            flex: 1;
        }

        /* ── Cards ──────────────────────────────────────── */
        .card { border-radius: 14px !important; border: 1px solid #e9ecef !important; }
        .card.border-0 { border: none !important; }
        .table thead th { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.6px; color: #9ca3af; font-weight: 600; }
        .table td { font-size: 0.85rem; }
        .btn { font-size: 0.85rem; }
        .form-control, .form-select {
            border-radius: 10px !important;
            font-size: 0.875rem;
        }
        .form-control.bg-light, .form-select.bg-light {
            background: #f9fafb !important;
            border: 1px solid #e9ecef !important;
        }
        .form-control.bg-light:focus, .form-select.bg-light:focus {
            background: #fff !important;
            border-color: var(--sidebar-active) !important;
            box-shadow: 0 0 0 3px rgba(44,152,169,0.12) !important;
        }
    </style>
</head>
<body>
<div class="admin-wrapper">

    <!-- ── Sidebar ───────────────────────────────────────── -->
    <nav class="sidebar">
        <a class="sidebar-brand" href="<?= URL_BASE ?>/admin/dashboard">
            <div class="sidebar-brand-icon"><i class="bi bi-house-heart-fill"></i></div>
            <div class="sidebar-brand-text">
                <strong>EL ROSARIO</strong>
                <small>Panel Administrativo</small>
            </div>
        </a>

        <div class="sidebar-section-label">Menú Principal</div>
        <ul class="nav flex-column px-0">
            <li>
                <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'dashboard') ? 'active' : '' ?>"
                   href="<?= URL_BASE ?>/admin/dashboard">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
            </li>
            <li>
                <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'productos') ? 'active' : '' ?>"
                   href="<?= URL_BASE ?>/admin/productos">
                    <i class="bi bi-bag-check-fill"></i> Productos
                </a>
            </li>
            <li>
                <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'categorias') ? 'active' : '' ?>"
                   href="<?= URL_BASE ?>/admin/categorias">
                    <i class="bi bi-collection-fill"></i> Categorías
                </a>
            </li>
            <li>
                <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'mensajes') ? 'active' : '' ?>"
                   href="<?= URL_BASE ?>/admin/mensajes">
                    <i class="bi bi-envelope-paper-fill"></i> Mensajes
                </a>
            </li>
        </ul>

        <div class="sidebar-section-label">Sistema</div>
        <ul class="nav flex-column px-0">
            <li>
                <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'configuracion') ? 'active' : '' ?>"
                   href="<?= URL_BASE ?>/admin/configuracion">
                    <i class="bi bi-sliders"></i> Configuración
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <ul class="nav flex-column px-0">
                <li>
                    <a class="nav-link" href="<?= URL_BASE ?>/" target="_blank">
                        <i class="bi bi-box-arrow-up-right"></i> Ver sitio web
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="<?= URL_BASE ?>/admin/logout">
                        <i class="bi bi-power"></i> Cerrar Sesión
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- ── Main ──────────────────────────────────────────── -->
    <div class="admin-main">
        <header class="admin-topbar">
            <div class="topbar-title">
                <?= $title ?>
                <small><?php
                    $dias   = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
                    $meses  = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
                    echo $dias[date('w')] . ', ' . date('d') . ' de ' . $meses[date('n') - 1] . ' de ' . date('Y');
                ?></small>
            </div>
            <div class="dropdown">
                <a class="topbar-user dropdown-toggle" data-bs-toggle="dropdown" href="#">
                    <div class="topbar-user-avatar"><?= strtoupper(substr($_SESSION['user_name'], 0, 1)) ?></div>
                    <span class="topbar-user-name"><?= $_SESSION['user_name'] ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-1">
                    <li><a class="dropdown-item small" href="<?= URL_BASE ?>/admin/configuracion"><i class="bi bi-gear me-2"></i>Configuración</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item small text-danger" href="<?= URL_BASE ?>/admin/logout"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</a></li>
                </ul>
            </div>
        </header>

        <main class="admin-content">
            <?= $content ?>
        </main>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
