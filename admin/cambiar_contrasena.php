<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'admin') {
    header("Location: /proyectoFinalManolo/views/login.php");
    exit();
}

require_once __DIR__ . '/../controllers/CambiarContrasenaController.php';

$page_title = "Cambiar Contraseña";
include __DIR__ . '/../includes/header.php';

$cambiarContrasenaController = new CambiarContrasenaController();
$resultado = $cambiarContrasenaController->cambiar();
?>

<main class="main-content">
    <div class="container">
        <div class="auth-container">
            <h1 class="page-title">Cambiar Contraseña</h1>

            <?php if($resultado && isset($resultado['error'])): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($resultado['error']); ?></div>
            <?php endif; ?>

            <?php if($resultado && isset($resultado['success'])): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($resultado['success']); ?></div>
            <?php endif; ?>

            <form method="POST" action="" class="auth-form">
                <div class="form-group">
                    <label for="contrasena_actual">Contraseña Actual:</label>
                    <input type="password" id="contrasena_actual" name="contrasena_actual" required>
                </div>

                <div class="form-group">
                    <label for="contrasena_nueva">Nueva Contraseña:</label>
                    <input type="password" id="contrasena_nueva" name="contrasena_nueva" required>
                    <small>Mínimo 6 caracteres</small>
                </div>

                <div class="form-group">
                    <label for="confirmar_contrasena">Confirmar Nueva Contraseña:</label>
                    <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" required>
                </div>

                <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
            </form>

            <div class="back-link">
                <a href="/proyectoFinalManolo/admin/index.php">← Volver al Panel</a>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
