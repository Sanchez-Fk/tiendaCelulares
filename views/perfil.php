<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['usuario_id'])) {
    header("Location: /proyectoFinalManolo/views/login.php");
    exit();
}

$page_title = "Mi Perfil";
include __DIR__ . '/../includes/header.php';
?>

<main class="main-content">
    <div class="container">
        <div class="profile-container">
            <h1 class="page-title">Mi Perfil</h1>
            <div class="profile-info">
                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['usuario_email']); ?></p>
                <p><strong>Tipo de Usuario:</strong> <?php echo $_SESSION['usuario_tipo'] == 'admin' ? 'Administrador' : 'Usuario'; ?></p>
            </div>
            <div class="profile-actions">
                <a href="/proyectoFinalManolo/views/cambiar_contrasena.php" class="btn btn-primary">Cambiar Contraseña</a>
                <a href="/proyectoFinalManolo/views/historial_pagos.php" class="btn btn-primary">Historial de Pagos</a>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>

