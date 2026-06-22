<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <a href="<?= URL_BASE ?>/" class="auth-logo">
                <img src="<?= URL_BASE ?>/img/logo.jpg" alt="Blancos El Rosario">
            </a>
            <h1>Crear Cuenta</h1>
            <p>Únete y disfruta de una experiencia de compra completa</p>
        </div>

        <?php if ($error): ?>
            <div class="alert-error">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="<?= URL_BASE ?>/mi-cuenta/registro" method="POST" class="auth-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="nombre">Nombre completo *</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" required
                           value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" placeholder="10 dígitos"
                           value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="email">Correo electrónico *</label>
                <input type="email" id="email" name="email" placeholder="correo@ejemplo.com" required
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="password">Contraseña *</label>
                    <input type="password" id="password" name="password" placeholder="Mínimo 6 caracteres" required>
                </div>
                <div class="form-group">
                    <label for="password2">Confirmar contraseña *</label>
                    <input type="password" id="password2" name="password2" placeholder="Repite la contraseña" required>
                </div>
            </div>
            <button type="submit" class="btn-auth-submit">
                <i class="bi bi-person-plus-fill me-2"></i>Crear Mi Cuenta
            </button>
        </form>

        <p class="auth-switch">
            ¿Ya tienes cuenta? <a href="<?= URL_BASE ?>/mi-cuenta/acceso">Inicia sesión</a>
        </p>
    </div>
</div>
