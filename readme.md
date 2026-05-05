# 🏠 Blancos El Rosario — Sistema de Catálogo Web

> Sistema de gestión de catálogo de productos para tienda de blancos y ropa de cama, desarrollado con **PHP Vanilla** (sin frameworks) bajo una arquitectura **MVC-Lite** y entorno **Dockerizado**.

---

## 📸 Descripción del Proyecto

**Blancos El Rosario** es un sistema web completo que incluye:

- Un **catálogo público** para que los clientes exploren productos por categoría, vean detalles y soliciten cotizaciones por WhatsApp.
- Un **panel administrativo privado** para gestionar el inventario, imágenes, variantes, mensajes de clientes y la configuración general del sitio.

Desarrollado como solución a medida, sin frameworks ni dependencias innecesarias, para demostrar dominio del lenguaje PHP en su forma más pura.

---

## 🛠️ Stack Tecnológico

| Capa             | Tecnología                          |
|-----------------|--------------------------------------|
| Backend         | PHP 8.3 (Vanilla, sin framework)     |
| Base de Datos   | MariaDB 11.4                         |
| Frontend        | Bootstrap 5.3 + CSS Variables        |
| Iconografía     | Bootstrap Icons 1.11                 |
| Tipografía      | Google Fonts — Inter                 |
| Servidor Web    | Apache 2.4 (mod_rewrite)             |
| Contenedores    | Docker + Docker Compose              |
| Dependencias    | Composer (`vlucas/phpdotenv`)        |

---

## 🏗️ Arquitectura — MVC-Lite

El proyecto sigue el patrón **Modelo-Vista-Controlador** implementado desde cero, sin ningún framework:

```
blancos_el_rosario/
├── config/
│   └── routes.php               # Registro de todas las rutas
├── database/
│   ├── schema.sql               # Definición de tablas
│   └── seeds/
│       └── DatabaseSeeder.php   # Seeder automático (schema + datos)
├── docker/
│   └── php/
│       ├── Dockerfile
│       └── docker-entrypoint.sh # Automatización: Composer + Seeder
├── public/                      # Document Root (único punto de entrada)
│   ├── index.php                # Front Controller
│   ├── css/app.css              # Sistema de diseño
│   └── uploads/                 # Imágenes subidas por el admin
├── src/
│   ├── Core/
│   │   ├── Database.php         # Singleton PDO
│   │   ├── Router.php           # Enrutador con soporte de parámetros dinámicos
│   │   ├── View.php             # Motor de plantillas
│   │   └── Request.php          # Abstracción de $_GET, $_POST, $_FILES
│   ├── Controllers/             # Lógica de negocio
│   ├── Models/                  # Capa de acceso a datos
│   └── Middleware/
│       └── AuthMiddleware.php   # Protección de rutas privadas
└── views/
    ├── layouts/
    │   ├── public.php           # Layout del sitio público
    │   └── admin.php            # Layout del panel administrativo
    └── pages/                   # Vistas por módulo
```

### Componentes Core desarrollados desde cero

| Componente | Descripción |
|-----------|-------------|
| `Router.php` | Soporta rutas GET/POST con **parámetros dinámicos** (`/producto/{slug}`) usando expresiones regulares. Los parámetros se inyectan automáticamente en los métodos del controlador. |
| `Database.php` | **Singleton PDO** con charset `utf8mb4`, manejo de excepciones y reutilización de conexión en toda la aplicación. |
| `View.php` | Motor de plantillas mínimo que soporta **layouts intercambiables** (público / admin) y extrae variables al scope de la vista. |
| `AuthMiddleware.php` | Guard de sesión que protege rutas privadas, redirigiendo al login si el usuario no está autenticado. |

---

## ✨ Funcionalidades

### 🌐 Sitio Público

- **Hero Carrusel** dinámico con efecto Ken Burns, overlay degradado y caption izquierdo
- **Catálogo de productos** con filtros por categoría (sidebar)
- **Detalle de producto**: galería de imágenes, variantes (tallas/colores/material) y botón de cotización directa por WhatsApp
- **Página de contacto** con formulario de consulta (guarda en BD)
- Footer dinámico con redes sociales y datos de contacto configurables desde el admin
- Diseño **responsive** (mobile-first con Bootstrap 5.3)

### 🔐 Panel Administrativo

- **Autenticación segura**: sesiones + `password_verify()` (Bcrypt)
- **Dashboard** con accesos rápidos a cada módulo
- **CRUD de Categorías**: crear, editar y desactivar
- **CRUD de Productos**: subida de imágenes múltiples, gestión de variantes dinámica, marcado de producto destacado
- **Bandeja de mensajes**: visualizar, marcar como leído y eliminar consultas de clientes
- **Configuración General**: editar teléfono, email, dirección y redes sociales desde el panel (sin tocar código)
- Sidebar con **detección automática de ruta activa**

---

## 🚀 Instalación y Arranque

### Requisitos previos
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) instalado y corriendo

### 1. Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/blancos-el-rosario.git
cd blancos-el-rosario
```

### 2. Configurar variables de entorno

```bash
cp .env.example .env
# Editar .env con tus credenciales si es necesario
```

### 3. Levantar los contenedores

```bash
docker compose up -d --build
```

> **🎉 Eso es todo.** El `docker-entrypoint.sh` se encarga automáticamente de:
> 1. Instalar las dependencias de Composer
> 2. Esperar a que MariaDB esté lista
> 3. Crear las tablas desde `schema.sql`
> 4. Insertar los datos de prueba (seeder)

### 4. Acceder a la aplicación

| URL | Descripción |
|-----|-------------|
| `http://localhost:8080/blancos/public/` | Sitio público |
| `http://localhost:8080/blancos/public/admin/login` | Panel administrativo |

### Credenciales por defecto del Admin

| Campo | Valor |
|-------|-------|
| Email | `admin@blancoselrosario.com` |
| Contraseña | `admin123` |

> ⚠️ **Cambiar la contraseña en producción desde el panel de administración.**

---

## 🗄️ Modelo de Base de Datos

```
ct_categoria ──< ct_producto ──< dt_imagen_producto
                     │
                     └──< dt_variantes_producto
                     └──< dt_contacto (opcional)

ct_usuarios        (autenticación independiente)
ct_configuracion   (pares clave-valor para el sitio)
```

| Tabla | Propósito |
|-------|-----------|
| `ct_categoria` | Categorías de productos con slug para URLs amigables |
| `ct_producto` | Productos con precio, estado y flag de destacado |
| `dt_imagen_producto` | Imágenes múltiples con orden y flag de principal |
| `dt_variantes_producto` | Tallas, colores y materiales con stock y precio extra |
| `dt_contacto` | Mensajes de contacto con referencia a producto |
| `ct_usuarios` | Usuarios administrativos con roles |
| `ct_configuracion` | Configuración dinámica del sitio (clave-valor) |

---

## 🔒 Seguridad

- Contraseñas almacenadas con **Bcrypt** (`PASSWORD_BCRYPT`)
- Todas las consultas usan **PDO con parámetros preparados** (protección contra SQL Injection)
- Salida HTML protegida con `htmlspecialchars()` (protección contra XSS)
- Rutas privadas protegidas por **Middleware de sesión**
- Variables de entorno mediante `vlucas/phpdotenv` (sin credenciales hardcodeadas)

---

## 📦 Variables de Entorno (`.env`)

```dotenv
DB_HOST=db
DB_PORT=3306
DB_NAME=blancos_el_rosario
DB_USER=user
DB_PASS=password
```

---

## 💻 Comandos útiles

```bash
# Levantar entorno
docker compose up -d --build

# Ver logs en tiempo real
docker compose logs -f

# Resetear la base de datos (⚠️ borra todos los datos)
docker compose down -v && docker compose up -d --build

# Acceder al contenedor PHP
docker exec -it blancos_el_rosario_app bash

# Acceder a la consola de MariaDB
docker exec -it blancos_el_rosario_db mariadb -u user -ppassword blancos_el_rosario
```

---

## 👨‍💻 Autor

**Yves Geraud Mendoza** — Desarrollador Web Full Stack

- Este proyecto forma parte de mi **portafolio personal** como evidencia de desarrollo en **PHP Vanilla** sin el apoyo de frameworks como Laravel o Symfony.
- Demuestra dominio de: arquitectura MVC, enrutamiento dinámico, ORM manual con PDO, gestión de sesiones, subida de archivos, y Dockerización de entornos PHP.

---

## 📄 Licencia

Este proyecto fue desarrollado como trabajo a medida para **Blancos El Rosario**. Su código fuente se incluye en el portafolio del desarrollador únicamente con fines demostrativos.
