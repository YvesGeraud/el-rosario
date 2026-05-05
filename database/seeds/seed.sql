USE blancos_el_rosario;

-- Insertar Categorías
INSERT INTO ct_categoria (nombre, slug, descripcion) VALUES
('Sábanas', 'sabanas', 'Sábanas de algodón y microfibra para todos los tamaños.'),
('Almohadas', 'almohadas', 'Almohadas ortopédicas, de pluma y sintéticas.'),
('Edredones', 'edredones', 'Edredones ligeros y térmicos con diseños modernos.');

-- Insertar Productos
INSERT INTO ct_producto (id_ct_categoria, nombre, slug, descripcion, precio_base, destacado) VALUES
(1, 'Juego de Sábanas Algodón 200 hilos', 'sabanas-algodon-200-hilos', 'Juego completo de sábanas 100% algodón, frescas y duraderas.', 850.00, 1),
(1, 'Sábanas de Microfibra Soft', 'sabanas-microfibra-soft', 'Sábanas ultra suaves que no se arrugan.', 450.00, 0),
(2, 'Almohada Memory Foam', 'almohada-memory-foam', 'Almohada que se adapta a la forma de tu cuello para un mejor descanso.', 600.00, 1),
(3, 'Edredón Reversible Minimalist', 'edredon-reversible-minimalist', 'Edredón de dos vistas con colores neutros.', 1200.00, 1);

-- Insertar Imágenes de prueba (URLs de marcador de posición)
INSERT INTO dt_imagen_producto (id_ct_producto, ruta, es_principal) VALUES
(1, 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&q=80&w=300', 1),
(2, 'https://images.unsplash.com/photo-1584100936595-c0654b55a2e2?auto=format&fit=crop&q=80&w=300', 1),
(3, 'https://images.unsplash.com/photo-1616627561950-9f746e330171?auto=format&fit=crop&q=80&w=300', 1),
(4, 'https://images.unsplash.com/photo-1505693419173-42b9256a0e6d?auto=format&fit=crop&q=80&w=300', 1);
