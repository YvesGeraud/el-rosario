<?php

/**
 * DatabaseSeeder.php
 * Ejecuta el schema y los datos de prueba usando PDO directamente.
 * Corre automáticamente desde el entrypoint de Docker.
 */

require_once __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

$host    = $_ENV['DB_HOST'];
$port    = $_ENV['DB_PORT'];
$dbname  = $_ENV['DB_NAME'];
$user    = $_ENV['DB_USER'];
$pass    = $_ENV['DB_PASS'];

// ── Helpers ──────────────────────────────────────────────────────────────────
function log_msg(string $msg): void {
    echo "[Seeder] " . $msg . PHP_EOL;
}

function waitForDb(string $host, int $port, int $maxAttempts = 30): void {
    $attempts = 0;
    log_msg("Esperando conexión a la base de datos...");
    while ($attempts < $maxAttempts) {
        if (@fsockopen($host, $port)) {
            log_msg("Base de datos disponible.");
            return;
        }
        $attempts++;
        log_msg("Intento $attempts/$maxAttempts...");
        sleep(2);
    }
    log_msg("ERROR: No se pudo conectar a la base de datos después de $maxAttempts intentos.");
    exit(1);
}

// ── Esperar a que MariaDB esté lista ─────────────────────────────────────────
waitForDb($host, (int) $port);

// ── Conexión PDO (sin seleccionar DB todavía) ─────────────────────────────────
try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'",
        ]
    );
} catch (PDOException $e) {
    log_msg("ERROR de conexión: " . $e->getMessage());
    exit(1);
}

// ── Crear la base de datos si no existe ──────────────────────────────────────
$pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$pdo->exec("USE `$dbname`");
log_msg("Base de datos '$dbname' lista.");

// ── Verificar si ya hay tablas (evitar correr el schema dos veces) ─────────────
$result = $pdo->query("SHOW TABLES LIKE 'ct_categoria'")->fetch();
if ($result) {
    log_msg("Las tablas ya existen. Saltando schema.");
} else {
    log_msg("Creando tablas desde schema.sql...");
    $schema = file_get_contents(__DIR__ . '/../schema.sql');

    // Dividir por ';' y procesar cada sentencia
    foreach (explode(';', $schema) as $statement) {
        // Eliminar líneas de comentario (--) y líneas vacías
        $lines = array_filter(
            explode("\n", $statement),
            fn($line) => !str_starts_with(trim($line), '--') && trim($line) !== ''
        );
        $clean = trim(implode("\n", $lines));

        if (!empty($clean)) {
            $pdo->exec($clean);
        }
    }
    log_msg("Schema creado correctamente.");
}

// ── Verificar si ya hay datos (evitar duplicados) ──────────────────────────
$count = $pdo->query("SELECT COUNT(*) FROM ct_categoria")->fetchColumn();
if ($count > 0) {
    log_msg("Los datos de prueba ya existen ($count categorías). Saltando seed.");
    exit(0);
}

// ── Insertar datos de prueba ──────────────────────────────────────────────────
log_msg("Insertando datos de prueba...");

// Categorías
$pdo->exec("
    INSERT INTO ct_categoria (nombre, slug, descripcion) VALUES
    ('Sábanas',   'sabanas',   'Sábanas de algodón y microfibra para todos los tamaños.'),
    ('Almohadas', 'almohadas', 'Almohadas ortopédicas, de pluma y sintéticas.'),
    ('Edredones', 'edredones', 'Edredones ligeros y térmicos con diseños modernos.')
");
log_msg("Categorías insertadas.");

// Productos
$pdo->exec("
    INSERT INTO ct_producto (id_ct_categoria, nombre, slug, descripcion, precio_base, destacado) VALUES
    (1, 'Juego de Sábanas Algodón 200 hilos', 'sabanas-algodon-200-hilos', 'Juego completo de sábanas 100% algodón, frescas y duraderas.', 850.00, 1),
    (1, 'Sábanas de Microfibra Soft',         'sabanas-microfibra-soft',   'Sábanas ultra suaves que no se arrugan.',                       450.00, 0),
    (2, 'Almohada Memory Foam',               'almohada-memory-foam',      'Almohada que se adapta a la forma de tu cuello.',               600.00, 1),
    (3, 'Edredón Reversible Minimalist',      'edredon-reversible-minimalist', 'Edredón de dos vistas con colores neutros.',               1200.00, 1)
");
log_msg("Productos insertados.");

// Imágenes
$pdo->exec("
    INSERT INTO dt_imagen_producto (id_ct_producto, ruta, es_principal) VALUES
    (1, 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&q=80&w=300', 1),
    (2, 'https://images.unsplash.com/photo-1584100936595-c0654b55a2e2?auto=format&fit=crop&q=80&w=300', 1),
    (3, 'https://images.unsplash.com/photo-1616627561950-9f746e330171?auto=format&fit=crop&q=80&w=300', 1),
    (4, 'https://images.unsplash.com/photo-1505693419173-42b9256a0e6d?auto=format&fit=crop&q=80&w=300', 1)
");
log_msg("Imágenes insertadas.");

// Usuario admin (Asegurar que sea admin123)
$adminEmail = 'admin@blancoselrosario.com';
$adminPass = password_hash('admin123', PASSWORD_BCRYPT);

$checkUser = $pdo->prepare("SELECT id_ct_usuario FROM ct_usuarios WHERE email = :email");
$checkUser->execute(['email' => $adminEmail]);

if ($checkUser->fetch()) {
    $pdo->prepare("UPDATE ct_usuarios SET password = :pass WHERE email = :email")
        ->execute(['pass' => $adminPass, 'email' => $adminEmail]);
    log_msg("Usuario admin actualizado con contraseña 'admin123'.");
} else {
    $pdo->prepare("INSERT INTO ct_usuarios (nombre, email, password, rol) VALUES ('Administrador', :email, :pass, 'admin')")
        ->execute(['email' => $adminEmail, 'pass' => $adminPass]);
    log_msg("Usuario admin creado con contraseña 'admin123'.");
}
log_msg("Usuario admin insertado.");

// ── Configuración Inicial ─────────────────────────────────────────────────────
$configuraciones = [
    ['clave' => 'contacto_email', 'valor' => 'contacto@blancoselrosario.com', 'desc' => 'Email público de contacto'],
    ['clave' => 'contacto_telefono', 'valor' => '+52 123 456 7890', 'desc' => 'Teléfono principal'],
    ['clave' => 'contacto_whatsapp', 'valor' => '521234567890', 'desc' => 'Número de WhatsApp (solo números)'],
    ['clave' => 'contacto_direccion', 'valor' => 'Calle Principal #123, Colonia Centro. Rosario, Sinaloa.', 'desc' => 'Dirección física'],
    ['clave' => 'redes_facebook', 'valor' => 'https://facebook.com/blancoselrosario', 'desc' => 'URL de Facebook'],
    ['clave' => 'redes_instagram', 'valor' => 'https://instagram.com/blancoselrosario', 'desc' => 'URL de Instagram']
];

foreach ($configuraciones as $conf) {
    $pdo->prepare("INSERT IGNORE INTO ct_configuracion (clave, valor, descripcion) VALUES (:clave, :valor, :desc)")
        ->execute($conf);
}
log_msg("Configuración inicial insertada.");

log_msg("✅ Seed completado exitosamente.");
