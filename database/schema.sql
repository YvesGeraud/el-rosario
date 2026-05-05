-- Crear base de datos si no existe
CREATE DATABASE IF NOT EXISTS blancos_el_rosario;
USE blancos_el_rosario;

-- Tabla de Categorías
CREATE TABLE `ct_categoria` (
	`id_ct_categoria` INT(11) NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`slug` VARCHAR(120) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`descripcion` TEXT NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`estado` TINYINT(1) NULL DEFAULT '1',
	`fecha_in` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
	`fecha_mod` TIMESTAMP NULL DEFAULT NULL ON UPDATE current_timestamp(),
	PRIMARY KEY (`id_ct_categoria`) USING BTREE,
	UNIQUE INDEX `slug` (`slug`) USING BTREE,
	INDEX `estado` (`estado`) USING BTREE
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
;

-- Tabla de Productos
CREATE TABLE `ct_producto` (
	`id_ct_producto` INT(11) NOT NULL AUTO_INCREMENT,
	`id_ct_categoria` INT(11) NULL DEFAULT NULL,
	`nombre` VARCHAR(200) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`slug` VARCHAR(220) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`descripcion` TEXT NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`precio_base` DECIMAL(10,2) NULL DEFAULT '0.00',
	`estado` TINYINT(1) NULL DEFAULT '1',
	`destacado` TINYINT(1) NULL DEFAULT '0',
	`fecha_in` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
	`fecha_mod` TIMESTAMP NULL DEFAULT NULL ON UPDATE current_timestamp(),
	PRIMARY KEY (`id_ct_producto`) USING BTREE,
	UNIQUE INDEX `slug` (`slug`) USING BTREE,
	INDEX `categoria_id` (`id_ct_categoria`) USING BTREE,
	INDEX `estado` (`estado`) USING BTREE,
	CONSTRAINT `FK_ct_producto_ct_categoria` FOREIGN KEY (`id_ct_categoria`) REFERENCES `ct_categoria` (`id_ct_categoria`) ON UPDATE NO ACTION ON DELETE NO ACTION
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
;


-- Tabla de Variantes (Tallas, Colores, etc.)
CREATE TABLE `dt_variantes_producto` (
	`id_dt_variantes_producto` INT(11) NOT NULL AUTO_INCREMENT,
	`id_ct_producto` INT(11) NOT NULL,
	`nombre` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`tipo` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`valor` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`precio_extra` DECIMAL(10,2) NULL DEFAULT '0.00',
	`estado` TINYINT(1) NULL DEFAULT '1',
	`fecha_in` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
	`fecha_mod` TIMESTAMP NULL DEFAULT NULL ON UPDATE current_timestamp(),
	PRIMARY KEY (`id_dt_variantes_producto`) USING BTREE,
	INDEX `producto_id` (`id_ct_producto`) USING BTREE,
	INDEX `estado` (`estado`) USING BTREE,
	CONSTRAINT `FK_dt_variantes_producto_ct_producto` FOREIGN KEY (`id_ct_producto`) REFERENCES `ct_producto` (`id_ct_producto`) ON UPDATE NO ACTION ON DELETE NO ACTION
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
;


-- Tabla de Imágenes de Productos
CREATE TABLE `dt_imagen_producto` (
	`id_dt_imagen_producto` INT(11) NOT NULL AUTO_INCREMENT,
	`id_ct_producto` INT(11) NOT NULL,
	`ruta` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`es_principal` TINYINT(1) NULL DEFAULT '0',
	`orden` INT(11) NULL DEFAULT '0',
	`estado` TINYINT(1) NULL DEFAULT '1',
	`fecha_in` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
	`fecha_mod` TIMESTAMP NULL DEFAULT NULL ON UPDATE current_timestamp(),
	PRIMARY KEY (`id_dt_imagen_producto`) USING BTREE,
	INDEX `producto_id` (`id_ct_producto`) USING BTREE,
	INDEX `estado` (`estado`) USING BTREE,
	CONSTRAINT `FK_dt_imagen_producto_ct_producto` FOREIGN KEY (`id_ct_producto`) REFERENCES `ct_producto` (`id_ct_producto`) ON UPDATE NO ACTION ON DELETE NO ACTION
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
;


-- Tabla de Contacto / Mensajes
CREATE TABLE `dt_contacto` (
	`id_dt_contacto` INT(11) NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`email` VARCHAR(150) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`telefono` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`mensaje` TEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`id_ct_producto` INT(11) NULL DEFAULT NULL,
	`estado` TINYINT(1) NULL DEFAULT '1',
	`leido` TINYINT(1) NULL DEFAULT '0',
	`fecha_in` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
	`fecha_mod` TIMESTAMP NULL DEFAULT NULL ON UPDATE current_timestamp(),
	PRIMARY KEY (`id_dt_contacto`) USING BTREE,
	INDEX `producto_id` (`id_ct_producto`) USING BTREE,
	INDEX `estado` (`estado`) USING BTREE,
	CONSTRAINT `FK_dt_contacto_ct_producto` FOREIGN KEY (`id_ct_producto`) REFERENCES `ct_producto` (`id_ct_producto`) ON UPDATE NO ACTION ON DELETE NO ACTION
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
;


-- Tabla de Usuarios (Admin)
CREATE TABLE `ct_usuarios` (
	`id_ct_usuario` INT(11) NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`email` VARCHAR(150) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`password` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`rol` ENUM('admin','editor') NULL DEFAULT 'admin' COLLATE 'utf8mb4_unicode_ci',
	`estado` TINYINT(1) NULL DEFAULT '1',
	`fecha_in` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
	`fecha_mod` TIMESTAMP NULL DEFAULT NULL ON UPDATE current_timestamp(),
	PRIMARY KEY (`id_ct_usuario`) USING BTREE,
	UNIQUE INDEX `email` (`email`) USING BTREE,
	INDEX `estado` (`estado`) USING BTREE
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
AUTO_INCREMENT=2
;


-- Tabla de Configuración General
CREATE TABLE IF NOT EXISTS `ct_configuracion` (
  `clave` VARCHAR(50) PRIMARY KEY,
  `valor` TEXT NOT NULL,
  `descripcion` VARCHAR(255),
  `fecha_mod` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Fin del esquema
