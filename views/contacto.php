<?php
require_once __DIR__ . '/../controllers/ContactoController.php';

$page_title = "Contáctanos";
include __DIR__ . '/../includes/header.php';

$contactoController = new ContactoController();
$resultado = $contactoController->guardar();
?>

<main class="main-content">
    <div class="container">
        <div class="contact-container">
            <h1 class="page-title">Contáctanos</h1>
            
            <?php if($resultado && isset($resultado['error'])): ?>
                <div class="alert alert-error"><?php echo $resultado['error']; ?></div>
            <?php endif; ?>
            
            <?php if($resultado && isset($resultado['success'])): ?>
                <div class="alert alert-success"><?php echo $resultado['success']; ?></div>
            <?php endif; ?>

            <div class="contact-box">
                <form method="POST" action="" class="contact-form">
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="titulo">Título del Mensaje</label>
                        <input type="text" id="titulo" name="titulo" required>
                    </div>
                    <div class="form-group">
                        <label for="mensaje">Mensaje</label>
                        <textarea id="mensaje" name="mensaje" rows="6" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Enviar Mensaje</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>



