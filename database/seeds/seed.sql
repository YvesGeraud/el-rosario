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
(1, 'https://resources.sears.com.mx/medios-plazavip/mkt/64483ff6cd20b_10400pse-siljpg.jpg?auto=format&fit=crop&q=80&w=300', 1),
(2, 'https://m.media-amazon.com/images/I/71uVKmY3i8L.jpg?auto=format&fit=crop&q=80&w=300', 1),
(3, 'https://m.media-amazon.com/images/I/51WGh2PadML.jpg?auto=format&fit=crop&q=80&w=300', 1),
(4, 'https://m.media-amazon.com/images/I/81x5wc4TcFL.jpg?auto=format&fit=crop&q=80&w=300?auto=format&fit=crop&q=80&w=300', 1);
