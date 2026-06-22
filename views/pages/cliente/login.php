<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <a href="<?= URL_BASE ?>/" class="auth-logo">
                <img src="<?= URL_BASE ?>/img/logo.jpg" alt="Blancos El Rosario">
            </a>
            <h1>Bienvenido de vuelta</h1>
            <p>Inicia sesión para ver tus pedidos y comprar más rápido</p>
        </div>

        <?php if ($error): ?>
            <div class="alert-error">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="<?= URL_BASE ?>/mi-cuenta/acceso" method="POST" class="auth-form">
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" placeholder="correo@ejemplo.com" required
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Tu contraseña" required>
            </div>
            <button type="submit" class="btn-auth-submit">
                <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
            </button>
        </form>

        <p class="auth-switch">
            ¿No tienes cuenta? <a href="<?= URL_BASE ?>/mi-cuenta/registro">Regístrate gratis</a>
        </p>
    </div>
</div>
