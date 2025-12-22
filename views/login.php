<?php
require_once __DIR__ . '/../controllers/AuthController.php';

$page_title = "Iniciar Sesión";
include __DIR__ . '/../includes/header.php';

$authController = new AuthController();
$resultado = $authController->login();
?>

<main class="main-content">
    <div class="container">
        <div class="auth-container">
            <div class="auth-box">
                <h2>Iniciar Sesión</h2>
                
                <?php if($resultado && isset($resultado['error'])): ?>
                    <div class="alert alert-error"><?php echo $resultado['error']; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
                </form>
                <p class="auth-link">¿No tienes cuenta? <a href="/proyectoFinalManolo/views/register.php">Regístrate aquí</a></p>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>



