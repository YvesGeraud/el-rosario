<?php

namespace App\Controllers;

use App\Core\Database;

class InstallController {
    public function index() {
        try {
            $db = Database::getInstance();
            
            // 1. Leer el esquema
            $schemaPath = __DIR__ . '/../../database/schema.sql';
            if (!file_exists($schemaPath)) {
                throw new \Exception("No se encontró el archivo schema.sql");
            }
            
            $sql = file_get_contents($schemaPath);
            
            // Limpiar el SQL: Quitar CREATE DATABASE y USE si existen
            $sql = preg_replace('/CREATE DATABASE IF NOT EXISTS.*;/i', '', $sql);
            $sql = preg_replace('/USE .*;/i', '', $sql);
            
            // Dividir por punto y coma para ejecutar una por una
            $queries = explode(';', $sql);
            foreach ($queries as $query) {
                $query = trim($query);
                if (!empty($query)) {
                    $db->exec($query);
                }
            }
            echo "<h3 style='color:green'>✅ Tablas creadas correctamente.</h3>";
            
            // 2. Leer el seeder (opcional pero recomendado)
            $seedPath = __DIR__ . '/../../database/seeds/seed.sql';
            if (file_exists($seedPath)) {
                $seedSql = file_get_contents($seedPath);
                $seedSql = preg_replace('/USE .*;/i', '', $seedSql);
                
                $seedQueries = explode(';', $seedSql);
                foreach ($seedQueries as $query) {
                    $query = trim($query);
                    if (!empty($query)) {
                        $db->exec($query);
                    }
                }
                echo "<h3 style='color:green'>✅ Datos de prueba insertados correctamente.</h3>";
            }

            echo "<p>🚀 ¡Todo listo! Ya puedes ir al <a href='" . URL_BASE . "/'>Inicio</a> o al <a href='" . URL_BASE . "/admin/login'>Panel Admin</a>.</p>";
            echo "<p style='color:red'>⚠️ <strong>IMPORTANTE:</strong> Por seguridad, borra esta ruta o este archivo después de usarlo.</p>";

        } catch (\PDOException $e) {
            echo "<h3 style='color:red'>❌ Error de Base de Datos:</h3>";
            echo "<pre>" . $e->getMessage() . "</pre>";
        } catch (\Exception $e) {
            echo "<h3 style='color:red'>❌ Error General:</h3>";
            echo "<pre>" . $e->getMessage() . "</pre>";
        }
    }
}
