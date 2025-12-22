<?php
/**
 * Script de instalación - Crea la base de datos y las tablas
 * Ejecuta este archivo una vez para configurar la base de datos
 */

$host = "localhost";
$username = "root";
$password = "";
$db_name = "tienda_moviles";    

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Instalación - Tienda Móviles</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #0a0a0a;
            color: #fff;
        }
        .success { color: #6bff8f; }
        .error { color: #ff6b7a; }
        .info { color: #d4af37; }
        pre { background: #1a1a1a; padding: 15px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>🔧 Instalación de Tienda de Dispositivos Móviles</h1>
";

try {
    // Conectar sin especificar base de datos
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p class='info'>✓ Conexión a MySQL establecida</p>";
    
    // Crear base de datos
    $conn->exec("CREATE DATABASE IF NOT EXISTS $db_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "<p class='success'>✓ Base de datos '$db_name' creada o ya existe</p>";
    
    // Seleccionar base de datos
    $conn->exec("USE $db_name");
    echo "<p class='info'>✓ Base de datos seleccionada</p>";
    
    // Crear tabla de usuarios
    $conn->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        tipo ENUM('usuario', 'admin') DEFAULT 'usuario',
        fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "<p class='success'>✓ Tabla 'usuarios' creada</p>";
    
    // Crear tabla de productos
    $conn->exec("CREATE TABLE IF NOT EXISTS productos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(200) NOT NULL,
        descripcion TEXT,
        precio DECIMAL(10, 2) NOT NULL,
        imagen VARCHAR(255),
        especificaciones TEXT,
        stock INT DEFAULT 0,
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    echo "<p class='success'>✓ Tabla 'productos' creada</p>";
    
    // Crear tabla de carrito
    $conn->exec("CREATE TABLE IF NOT EXISTS carrito (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario_id INT NOT NULL,
        producto_id INT NOT NULL,
        cantidad INT DEFAULT 1,
        fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
        FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE,
        UNIQUE KEY unique_carrito (usuario_id, producto_id)
    )");
    echo "<p class='success'>✓ Tabla 'carrito' creada</p>";
    
    // Crear tabla de misión y visión
    $conn->exec("CREATE TABLE IF NOT EXISTS mision_vision (
        id INT PRIMARY KEY DEFAULT 1,
        mision TEXT NOT NULL,
        vision TEXT NOT NULL,
        valores TEXT,
        fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    echo "<p class='success'>✓ Tabla 'mision_vision' creada</p>";
    
    // Crear tabla de pagos
    $conn->exec("CREATE TABLE IF NOT EXISTS pagos (
        id INT PRIMARY KEY AUTO_INCREMENT,
        usuario_id INT NOT NULL,
        total DECIMAL(10, 2) NOT NULL,
        metodo_pago VARCHAR(50) NOT NULL,
        numero_tarjeta VARCHAR(20) NOT NULL,
        nombre_titular VARCHAR(100) NOT NULL,
        fecha_expiracion VARCHAR(5) NOT NULL,
        cvv VARCHAR(10) NOT NULL,
        estado VARCHAR(20) DEFAULT 'completado',
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
    )");
    echo "<p class='success'>✓ Tabla 'pagos' creada</p>";
    
    // Verificar si ya existe el administrador
    $stmt = $conn->query("SELECT COUNT(*) FROM usuarios WHERE email = 'admin@admin.com'");
    $admin_exists = $stmt->fetchColumn();
    
    if($admin_exists == 0) {
        // Insertar usuario administrador
        $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, tipo) VALUES (?, ?, ?, 'admin')");
        $stmt->execute(['Administrador', 'admin@admin.com', $admin_password]);
        echo "<p class='success'>✓ Usuario administrador creado</p>";
        echo "<p class='info'>   Email: admin@admin.com</p>";
        echo "<p class='info'>   Contraseña: admin123</p>";
    } else {
        echo "<p class='info'>ℹ Usuario administrador ya existe</p>";
    }
    
    // Verificar si hay productos
    $stmt = $conn->query("SELECT COUNT(*) FROM productos");
    $product_count = $stmt->fetchColumn();
    
    if($product_count == 0) {
        // Insertar productos de ejemplo
        $productos = [
            ['iPhone 15 Pro', 'El último iPhone con chip A17 Pro y cámara de 48MP', 1299.99, 'assets/img/placeholder.svg', 'Pantalla: 6.1", Procesador: A17 Pro, RAM: 8GB, Almacenamiento: 256GB', 10],
            ['Samsung Galaxy S24 Ultra', 'Smartphone premium con S Pen y cámara de 200MP', 1199.99, 'assets/img/placeholder.svg', 'Pantalla: 6.8", Procesador: Snapdragon 8 Gen 3, RAM: 12GB, Almacenamiento: 512GB', 8],
            ['Xiaomi 14 Pro', 'Potente smartphone con pantalla AMOLED y carga rápida', 899.99, 'assets/img/placeholder.svg', 'Pantalla: 6.73", Procesador: Snapdragon 8 Gen 3, RAM: 12GB, Almacenamiento: 256GB', 15],
            ['Google Pixel 8 Pro', 'Excelente cámara con inteligencia artificial', 999.99, 'assets/img/placeholder.svg', 'Pantalla: 6.7", Procesador: Tensor G3, RAM: 12GB, Almacenamiento: 128GB', 12]
        ];
        
        $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, imagen, especificaciones, stock) VALUES (?, ?, ?, ?, ?, ?)");
        foreach($productos as $producto) {
            $stmt->execute($producto);
        }
        echo "<p class='success'>✓ Productos de ejemplo insertados</p>";
    } else {
        echo "<p class='info'>ℹ Ya existen $product_count productos en la base de datos</p>";
    }
    
    // Verificar si existe misión y visión
    $stmt = $conn->query("SELECT COUNT(*) FROM mision_vision WHERE id = 1");
    $mision_exists = $stmt->fetchColumn();
    
    if($mision_exists == 0) {
        // Insertar misión y visión por defecto
        $mision_default = "Nuestra misión es ofrecer a nuestros clientes los mejores dispositivos móviles del mercado, proporcionando productos de alta calidad, tecnología de vanguardia y un servicio excepcional. Nos comprometemos a mantenernos a la vanguardia de la innovación tecnológica y a brindar una experiencia de compra única que supere las expectativas de nuestros clientes.";
        $vision_default = "Ser la tienda líder en dispositivos móviles, reconocida por nuestra excelencia en servicio al cliente, nuestra amplia gama de productos de última generación y nuestro compromiso con la satisfacción del cliente. Aspiramos a ser el destino preferido para todos aquellos que buscan tecnología móvil de calidad, innovación y confiabilidad.";
        $valores_default = "Calidad: Ofrecemos solo productos de las mejores marcas y tecnología probada.\nInnovación: Estamos siempre al día con las últimas tendencias tecnológicas.\nServicio: Nuestro equipo está comprometido con la satisfacción del cliente.\nConfianza: Construimos relaciones duraderas basadas en la transparencia y honestidad.";
        
        $stmt = $conn->prepare("INSERT INTO mision_vision (id, mision, vision, valores) VALUES (1, ?, ?, ?)");
        $stmt->execute([$mision_default, $vision_default, $valores_default]);
        echo "<p class='success'>✓ Misión y Visión por defecto insertadas</p>";
    } else {
        echo "<p class='info'>ℹ Misión y Visión ya existen</p>";
    }
    
    echo "<hr>";
    echo "<h2 class='success'>✅ Instalación completada exitosamente</h2>";
    echo "<p><a href='index.php' style='color: #d4af37;'>Ir al sitio web →</a></p>";
    echo "<p><small>Puedes eliminar este archivo (install.php) después de la instalación por seguridad.</small></p>";
    
} catch(PDOException $e) {
    echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "</body></html>";
?>

