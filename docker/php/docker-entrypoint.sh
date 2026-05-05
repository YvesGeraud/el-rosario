#!/bin/bash
set -e

# Instalar dependencias si el archivo composer.json existe
if [ -f "composer.json" ]; then
    echo "Verificando dependencias de Composer..."
    composer install --no-interaction --optimize-autoloader
fi

# Ejecutar el seeder (crea tablas y datos de prueba si no existen)
echo "Ejecutando DatabaseSeeder..."
php database/seeds/DatabaseSeeder.php

# Ejecutar el comando original (Apache)
echo "Iniciando Apache..."
exec apache2-foreground
