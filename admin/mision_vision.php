<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'admin') {
    header("Location: /proyectoFinalManolo/views/login.php");
    exit();
}

require_once __DIR__ . '/../controllers/MisionVisionController.php';

$page_title = "Editar Misión y Visión";
include __DIR__ . '/../includes/header.php';

$controller = new MisionVisionController();
$datos = $controller->obtener();
$resultado = $controller->actualizar();
?>

<main class="main-content">
    <div class="container">
        <h1 class="page-title">Editar Misión y Visión</h1>
        
        <div class="admin-actions">
            <a href="/proyectoFinalManolo/admin/index.php" class="btn btn-secondary">← Volver al Panel</a>
        </div>

        <?php if($resultado && isset($resultado['error'])): ?>
            <div class="alert alert-error"><?php echo $resultado['error']; ?></div>
        <?php endif; ?>
        
        <?php if($resultado && isset($resultado['success'])): ?>
            <div class="alert alert-success"><?php echo $resultado['success']; ?></div>
        <?php endif; ?>

        <div class="form-container">
            <form method="POST" action="" class="mision-vision-form">
                <div class="form-group">
                    <label for="mision">Misión</label>
                    <textarea id="mision" name="mision" rows="6" required><?php echo htmlspecialchars($datos['mision'] ?? ''); ?></textarea>
                    <small>Describe la misión de la empresa</small>
                </div>

                <div class="form-group">
                    <label for="vision">Visión</label>
                    <textarea id="vision" name="vision" rows="6" required><?php echo htmlspecialchars($datos['vision'] ?? ''); ?></textarea>
                    <small>Describe la visión de la empresa</small>
                </div>

                <div class="form-group">
                    <label for="valores">Valores (Opcional)</label>
                    <textarea id="valores" name="valores" rows="8"><?php echo htmlspecialchars($datos['valores'] ?? ''); ?></textarea>
                    <small>Lista los valores de la empresa (uno por línea o separados por saltos de línea)</small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <a href="/proyectoFinalManolo/admin/index.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>



