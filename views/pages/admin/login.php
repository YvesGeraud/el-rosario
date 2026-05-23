<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-5">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-primary text-white p-4 text-center">
                <h3 class="mb-0 fw-bold text-white">Panel Administrativo</h3>
                <small>Blancos El Rosario</small>
            </div>
            <div class="card-body p-5">
                <?php if ($error): ?>
                    <div class="alert alert-danger mb-4 d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <form action="<?= URL_BASE ?>/admin/login" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Request::csrfToken() ?>">
                    <div class="mb-4">
                        <label for="email" class="form-label fw-bold">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control bg-light border-start-0" id="email" name="email" placeholder="admin@ejemplo.com" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label fw-bold">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control bg-light border-start-0" id="password" name="password" placeholder="••••••••" required>
                        </div>
                    </div>
                    <div class="d-grid mt-5">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold">
                            Entrar al Panel <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer bg-light p-3 text-center border-0">
                <a href="<?= URL_BASE ?>/" class="text-muted small"><i class="bi bi-house me-1"></i> Volver al sitio público</a>
            </div>
        </div>
    </div>
</div>

<?php 
// Limpiar el error después de mostrarlo una vez
if(isset($_SESSION['login_error'])) unset($_SESSION['login_error']); 
?>
