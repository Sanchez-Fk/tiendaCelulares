-- Script de migración para agregar tabla de misión y visión
-- Ejecuta este script si ya tienes la base de datos creada

USE tienda_moviles;

-- Crear tabla de misión y visión si no existe
CREATE TABLE IF NOT EXISTS mision_vision (
    id INT PRIMARY KEY DEFAULT 1,
    mision TEXT NOT NULL,
    vision TEXT NOT NULL,
    valores TEXT,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insertar valores por defecto si la tabla está vacía
INSERT INTO mision_vision (id, mision, vision, valores)
SELECT 1,
    'Nuestra misión es ofrecer a nuestros clientes los mejores dispositivos móviles del mercado, proporcionando productos de alta calidad, tecnología de vanguardia y un servicio excepcional. Nos comprometemos a mantenernos a la vanguardia de la innovación tecnológica y a brindar una experiencia de compra única que supere las expectativas de nuestros clientes.',
    'Ser la tienda líder en dispositivos móviles, reconocida por nuestra excelencia en servicio al cliente, nuestra amplia gama de productos de última generación y nuestro compromiso con la satisfacción del cliente. Aspiramos a ser el destino preferido para todos aquellos que buscan tecnología móvil de calidad, innovación y confiabilidad.',
    'Calidad: Ofrecemos solo productos de las mejores marcas y tecnología probada.
Innovación: Estamos siempre al día con las últimas tendencias tecnológicas.
Servicio: Nuestro equipo está comprometido con la satisfacción del cliente.
Confianza: Construimos relaciones duraderas basadas en la transparencia y honestidad.'
WHERE NOT EXISTS (SELECT 1 FROM mision_vision WHERE id = 1);



