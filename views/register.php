<?php
require_once __DIR__ . '/../controllers/AuthController.php';

$page_title = "Registrarse";
include __DIR__ . '/../includes/header.php';

$authController = new AuthController();
$resultado = $authController->registro();
?>

<main class="main-content">
    <div class="container">
        <div class="auth-container">
            <div class="auth-box">
                <h2>Crear Cuenta</h2>
                
                <?php if($resultado && isset($resultado['error'])): ?>
                    <div class="alert alert-error"><?php echo $resultado['error']; ?></div>
                <?php endif; ?>
                
                <?php if($resultado && isset($resultado['success'])): ?>
                    <div class="alert alert-success"><?php echo $resultado['success']; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="nombre">Nombre Completo</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirmar Contraseña</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
                </form>
                <p class="auth-link">¿Ya tienes cuenta? <a href="/proyectoFinalManolo/views/login.php">Inicia sesión aquí</a></p>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>



