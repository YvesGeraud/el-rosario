-- ============================================================
-- Migración 002: Tablas E-Commerce
-- Blancos El Rosario
-- ============================================================

USE blancos_el_rosario;

-- ── Clientes (compradores registrados) ──────────────────────
CREATE TABLE IF NOT EXISTS `ct_cliente` (
    `id_ct_cliente`     INT(11)      NOT NULL AUTO_INCREMENT,
    `nombre`            VARCHAR(100) NOT NULL COLLATE 'utf8mb4_unicode_ci',
    `email`             VARCHAR(150) NOT NULL COLLATE 'utf8mb4_unicode_ci',
    `password`          VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
    `telefono`          VARCHAR(20)  NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
    `direccion_defecto` TEXT         NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
    `estado`            TINYINT(1)   NULL DEFAULT '1',
    `fecha_in`          TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `fecha_mod`         TIMESTAMP    NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_ct_cliente`) USING BTREE,
    UNIQUE INDEX `email` (`email`) USING BTREE,
    INDEX `estado` (`estado`) USING BTREE
) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB;


-- ── Pedidos (cabecera) ───────────────────────────────────────
CREATE TABLE IF NOT EXISTS `ct_pedido` (
    `id_ct_pedido`      INT(11)       NOT NULL AUTO_INCREMENT,
    `folio`             VARCHAR(20)   NOT NULL COLLATE 'utf8mb4_unicode_ci',
    `id_ct_cliente`     INT(11)       NULL DEFAULT NULL,
    `nombre_cliente`    VARCHAR(100)  NOT NULL COLLATE 'utf8mb4_unicode_ci',
    `email_cliente`     VARCHAR(150)  NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
    `telefono_cliente`  VARCHAR(20)   NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
    `direccion_entrega` TEXT          NOT NULL COLLATE 'utf8mb4_unicode_ci',
    `municipio`         VARCHAR(100)  NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
    `estado_entrega`    VARCHAR(100)  NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
    `cp`                VARCHAR(10)   NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
    `metodo_pago`       ENUM('transferencia','contra_entrega','tarjeta_stripe') NOT NULL DEFAULT 'contra_entrega',
    `estado`            ENUM('pendiente','confirmado','enviado','entregado','cancelado') NOT NULL DEFAULT 'pendiente',
    `stripe_intent_id`  VARCHAR(100)  NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
    `subtotal`          DECIMAL(10,2) NULL DEFAULT '0.00',
    `costo_envio`       DECIMAL(10,2) NULL DEFAULT '0.00',
    `total`             DECIMAL(10,2) NULL DEFAULT '0.00',
    `notas`             TEXT          NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
    `fecha_in`          TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `fecha_mod`         TIMESTAMP     NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_ct_pedido`) USING BTREE,
    UNIQUE INDEX `folio` (`folio`) USING BTREE,
    INDEX `cliente_id` (`id_ct_cliente`) USING BTREE,
    INDEX `estado` (`estado`) USING BTREE,
    CONSTRAINT `FK_ct_pedido_ct_cliente`
        FOREIGN KEY (`id_ct_cliente`) REFERENCES `ct_cliente` (`id_ct_cliente`)
        ON UPDATE NO ACTION ON DELETE SET NULL
) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB;


-- ── Detalle de pedido ────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `dt_pedido_producto` (
    `id_dt_pedido_producto` INT(11)       NOT NULL AUTO_INCREMENT,
    `id_ct_pedido`          INT(11)       NOT NULL,
    `id_ct_producto`        INT(11)       NOT NULL,
    `nombre_producto`       VARCHAR(200)  NOT NULL COLLATE 'utf8mb4_unicode_ci',
    `variante_descripcion`  VARCHAR(200)  NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
    `precio_unitario`       DECIMAL(10,2) NOT NULL DEFAULT '0.00',
    `cantidad`              INT(11)       NOT NULL DEFAULT '1',
    `subtotal`              DECIMAL(10,2) NOT NULL DEFAULT '0.00',
    PRIMARY KEY (`id_dt_pedido_producto`) USING BTREE,
    INDEX `pedido_id` (`id_ct_pedido`) USING BTREE,
    INDEX `producto_id` (`id_ct_producto`) USING BTREE,
    CONSTRAINT `FK_dt_pedido_ct_pedido`
        FOREIGN KEY (`id_ct_pedido`) REFERENCES `ct_pedido` (`id_ct_pedido`)
        ON UPDATE NO ACTION ON DELETE CASCADE,
    CONSTRAINT `FK_dt_pedido_ct_producto`
        FOREIGN KEY (`id_ct_producto`) REFERENCES `ct_producto` (`id_ct_producto`)
        ON UPDATE NO ACTION ON DELETE NO ACTION
) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB;


-- ── Configuraciones nuevas para e-commerce ──────────────────
INSERT IGNORE INTO `ct_configuracion` (`clave`, `valor`, `descripcion`) VALUES
('envio_costo_nacional',  '150',  'Costo de envío nacional en MXN'),
('envio_gratis_desde',    '1500', 'Monto mínimo para envío gratis (MXN). 0 = desactivado'),
('envio_local_activo',    '1',    'Ofrece entrega local gratuita (1=sí, 0=no)'),
('banco_nombre',          '',     'Nombre del banco para transferencia'),
('banco_cuenta',          '',     'Número de cuenta bancaria'),
('banco_clabe',           '',     'CLABE interbancaria'),
('banco_titular',         'Blancos El Rosario', 'Nombre del titular de la cuenta');


-- ── Para bases de datos YA EXISTENTES (ejecutar si la tabla ya fue creada) ──
-- Si acabas de correr este script por primera vez, ignora este bloque.
-- Si ya tenías ct_pedido creada sin stripe, ejecuta esto:
ALTER TABLE `ct_pedido`
    MODIFY COLUMN `metodo_pago` ENUM('transferencia','contra_entrega','tarjeta_stripe') NOT NULL DEFAULT 'contra_entrega',
    ADD COLUMN IF NOT EXISTS `stripe_intent_id` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci' AFTER `notas`;

