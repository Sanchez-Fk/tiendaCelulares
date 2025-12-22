<?php
/**
 * Script unificado para crear la base de datos y todas las tablas necesarias.
 * Ejecuta en el navegador: http://localhost/proyectoFinalManolo/create_all_tables.php
 * Después de ejecutar, por seguridad elimina o restringe el acceso a este archivo.
 */

$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'tienda_moviles';

echo "<!DOCTYPE html><html lang='es'><head><meta charset='utf-8'><meta name='viewport' content='width=device-width,initial-scale=1'>";
echo "<title>Crear todas las tablas - Tienda Móviles</title>";
echo "<style>body{font-family:Arial,Helvetica,sans-serif;max-width:900px;margin:30px auto;background:#0a0a0a;color:#fff;padding:20px}h1{color:#d4af37}pre{background:#111;padding:10px;border-radius:6px;overflow:auto}</style>";
echo "</head><body><h1>Crear Base de Datos y Tablas</h1>";

try {
    $pdo = new PDO("mysql:host=$host", $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "<p style='color:#6bff8f'>✓ Conexión a MySQL establecida</p>";

    // Crear base de datos
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "<p style='color:#6bff8f'>✓ Base de datos '$db_name' creada o ya existe</p>";

    // Usar la base de datos
    $pdo->exec("USE `$db_name`");

    // Tabla usuarios
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        tipo ENUM('usuario','admin') DEFAULT 'usuario',
        fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "<p style='color:#6bff8f'>✓ Tabla 'usuarios' creada</p>";

    // Tabla productos
    $pdo->exec("CREATE TABLE IF NOT EXISTS productos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(200) NOT NULL,
        descripcion TEXT,
        precio DECIMAL(10,2) NOT NULL,
        imagen VARCHAR(255),
        especificaciones TEXT,
        stock INT DEFAULT 0,
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "<p style='color:#6bff8f'>✓ Tabla 'productos' creada</p>";

    // Tabla carrito
    $pdo->exec("CREATE TABLE IF NOT EXISTS carrito (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario_id INT NOT NULL,
        producto_id INT NOT NULL,
        cantidad INT DEFAULT 1,
        fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
        FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE,
        UNIQUE KEY unique_carrito (usuario_id, producto_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "<p style='color:#6bff8f'>✓ Tabla 'carrito' creada</p>";

    // Tabla mision_vision
    $pdo->exec("CREATE TABLE IF NOT EXISTS mision_vision (
        id INT PRIMARY KEY DEFAULT 1,
        mision TEXT NOT NULL,
        vision TEXT NOT NULL,
        valores TEXT,
        fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "<p style='color:#6bff8f'>✓ Tabla 'mision_vision' creada</p>";

    // Tabla pagos
    $pdo->exec("CREATE TABLE IF NOT EXISTS pagos (
        id INT PRIMARY KEY AUTO_INCREMENT,
        usuario_id INT NOT NULL,
        total DECIMAL(10,2) NOT NULL,
        metodo_pago VARCHAR(50) NOT NULL,
        numero_tarjeta VARCHAR(50) NOT NULL,
        nombre_titular VARCHAR(100) NOT NULL,
        fecha_expiracion VARCHAR(10) NOT NULL,
        cvv VARCHAR(10) NOT NULL,
        estado VARCHAR(20) DEFAULT 'completado',
        ciudad VARCHAR(100),
        departamento VARCHAR(100),
        direccion VARCHAR(255),
        municipio VARCHAR(100),
        descripcion_lugar TEXT,
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "<p style='color:#6bff8f'>✓ Tabla 'pagos' creada</p>";

    // Crear admin por defecto si no existe
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
    $stmt->execute(['admin@admin.com']);
    $exists = $stmt->fetchColumn();
    if(!$exists) {
        $pass = password_hash('admin123', PASSWORD_DEFAULT);
        $ins = $pdo->prepare("INSERT INTO usuarios (nombre,email,password,tipo) VALUES (?,?,?, 'admin')");
        $ins->execute(['Administrador','admin@admin.com',$pass]);
        echo "<p style='color:#6bff8f'>✓ Usuario administrador creado (admin@admin.com / admin123)</p>";
    } else {
        echo "<p style='color:#d4af37'>ℹ Usuario administrador ya existe</p>";
    }

    // Insertar productos de ejemplo si no existen
    $stmt = $pdo->query("SELECT COUNT(*) FROM productos");
    $count = $stmt->fetchColumn();
    if($count == 0) {
        $productos = [
            ['iPhone 15 Pro','El último iPhone con chip A17 Pro y cámara de 48MP',1299.99,'assets/img/placeholder.svg','Pantalla: 6.1", Procesador: A17 Pro, RAM: 8GB, Almacenamiento: 256GB',10],
            ['Samsung Galaxy S24 Ultra','Smartphone premium con S Pen y cámara de 200MP',1199.99,'assets/img/placeholder.svg','Pantalla: 6.8", Procesador: Snapdragon 8 Gen 3, RAM: 12GB, Almacenamiento: 512GB',8],
            ['Xiaomi 14 Pro','Potente smartphone con pantalla AMOLED y carga rápida',899.99,'assets/img/placeholder.svg','Pantalla: 6.73", Procesador: Snapdragon 8 Gen 3, RAM: 12GB, Almacenamiento: 256GB',15],
            ['Google Pixel 8 Pro','Excelente cámara con inteligencia artificial',999.99,'assets/img/placeholder.svg','Pantalla: 6.7", Procesador: Tensor G3, RAM: 12GB, Almacenamiento: 128GB',12]
        ];
        $insP = $pdo->prepare("INSERT INTO productos (nombre,descripcion,precio,imagen,especificaciones,stock) VALUES (?,?,?,?,?,?)");
        foreach($productos as $p) $insP->execute($p);
        echo "<p style='color:#6bff8f'>✓ Productos de ejemplo insertados</p>";
    } else {
        echo "<p style='color:#d4af37'>ℹ Ya existen $count productos</p>";
    }

    // Insertar misión y visión por defecto si no existe
    $stmt = $pdo->query("SELECT COUNT(*) FROM mision_vision WHERE id = 1");
    $m = $stmt->fetchColumn();
    if(!$m) {
        $mision = "Nuestra misión es ofrecer a nuestros clientes los mejores dispositivos móviles del mercado, proporcionando productos de alta calidad y un servicio excepcional.";
        $vision = "Ser la tienda líder en dispositivos móviles, reconocida por nuestra excelencia en servicio al cliente y calidad.";
        $valores = "Calidad\nInnovación\nServicio\nConfianza";
        $insM = $pdo->prepare("INSERT INTO mision_vision (id,mision,vision,valores) VALUES (1,?,?,?)");
        $insM->execute([$mision,$vision,$valores]);
        echo "<p style='color:#6bff8f'>✓ Misión y visión insertadas</p>";
    } else {
        echo "<p style='color:#d4af37'>ℹ Misión y visión ya existen</p>";
    }

    echo "<hr><p style='color:#6bff8f'><strong>Instalación completada.</strong></p>";
    echo "<p>Por seguridad elimina este archivo o muévelo fuera del directorio público.</p>";

} catch (PDOException $e) {
    echo "<p style='color:#ff6b7a'><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</body></html>";

?>
