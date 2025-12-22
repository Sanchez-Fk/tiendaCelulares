<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['usuario_id'])) {
    header("Location: /proyectoFinalManolo/views/login.php");
    exit();
}

$page_title = "Pago No Disponible";
include __DIR__ . '/../includes/header.php';
?>

<main class="main-content">
    <div class="container">
        <div class="unavailable-payment-container">
            <div class="unavailable-payment-box">
                <div class="unavailable-icon">🔒</div>
                <h1>Pago No Disponible</h1>
                <p class="unavailable-message">Lo sentimos, en este momento el sistema de pagos no está disponible.</p>
                
                <div class="unavailable-details">
                    <p>Este es un sitio de demostración y el sistema de pago está desactivado.</p>
                    <p>Para pruebas, puedes usar datos ficticios en el formulario anterior.</p>
                </div>

                <div class="unavailable-actions">
                    <a href="/proyectoFinalManolo/views/carrito.php" class="btn btn-primary">Volver al Carrito</a>
                    <a href="/proyectoFinalManolo/index.php" class="btn btn-secondary">Ir al Inicio</a>
                </div>

                <div class="unavailable-footer">
                    <p><strong>Código:</strong> PAYMENT_UNAVAILABLE_001</p>
                    <p><strong>Estado:</strong> Sistema en Mantenimiento</p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
