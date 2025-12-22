<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
$usuario_logueado = isset($_SESSION['usuario_id']);
$es_admin = isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] == 'admin';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Tienda de Dispositivos Móviles'; ?></title>
    <link rel="stylesheet" href="/proyectoFinalManolo/assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <?php
                // Buscar logo personalizado en uploads/
                $logoUrl = null;
                $uploadsRel = '/proyectoFinalManolo/uploads/';
                $uploadsDir = __DIR__ . '/../uploads/';
                foreach(['logo.png','logo.jpg','logo.jpeg','logo.svg'] as $lf) {
                    if(file_exists($uploadsDir . $lf)) {
                        $logoUrl = $uploadsRel . $lf;
                        break;
                    }
                }
                if($logoUrl): ?>
                    <a href="/proyectoFinalManolo/index.php"><img src="<?php echo $logoUrl; ?>" alt="Logo" class="site-logo"></a>
                <?php else: ?>
                    <a href="/proyectoFinalManolo/index.php">📱 MobileStore</a>
                <?php endif; ?>
            </div>
            <ul class="nav-menu">
                <li><a href="/proyectoFinalManolo/index.php">Inicio</a></li>
                <li><a href="/proyectoFinalManolo/views/quienes_somos.php">Quiénes Somos</a></li>
                <li><a href="/proyectoFinalManolo/views/contacto.php">Contáctanos</a></li>
                <?php if($usuario_logueado): ?>
                    <li><a href="/proyectoFinalManolo/views/carrito.php">🛒 Carrito</a></li>
                    <li><a href="/proyectoFinalManolo/views/perfil.php"><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></a></li>
                    <?php if($es_admin): ?>
                        <li><a href="/proyectoFinalManolo/admin/index.php">⚙️ Admin</a></li>
                    <?php endif; ?>
                    <li><a href="/proyectoFinalManolo/controllers/logout.php">Cerrar Sesión</a></li>
                <?php else: ?>
                    <li><a href="/proyectoFinalManolo/views/login.php">Iniciar Sesión</a></li>
                    <li><a href="/proyectoFinalManolo/views/register.php">Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

