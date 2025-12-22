<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['usuario_id'])) {
    header("Location: /proyectoFinalManolo/views/login.php");
    exit();
}

require_once __DIR__ . '/../controllers/PagoController.php';
require_once __DIR__ . '/../controllers/CarritoController.php';

$page_title = "Pago";
include __DIR__ . '/../includes/header.php';

$pagoController = new PagoController();
$carritoController = new CarritoController();

$resultado = $pagoController->procesar();
$carrito = $carritoController->obtener();

// Calcular total
$total = 0;
foreach($carrito as $item) {
    $total += $item['precio'] * $item['cantidad'];
}
?>

<main class="main-content">
    <div class="container">
        <h1 class="page-title">Procesar Pago</h1>

        <?php if(empty($carrito)): ?>
            <div class="empty-cart">
                <p>Tu carrito está vacío</p>
                <a href="/proyectoFinalManolo/index.php" class="btn btn-primary">Ver Productos</a>
            </div>
        <?php else: ?>
            <div class="payment-container">
                <div class="payment-form-section">
                    <?php if($resultado && isset($resultado['error'])): ?>
                        <div class="alert alert-error"><?php echo htmlspecialchars($resultado['error']); ?></div>
                    <?php endif; ?>

                    <?php if($resultado && isset($resultado['success'])): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($resultado['success']); ?></div>
                        <div class="payment-confirmation">
                            <p>Tu pago ha sido procesado exitosamente.</p>
                            <p><strong>ID de Transacción:</strong> <?php echo htmlspecialchars($resultado['pago_id']); ?></p>
                            <p><strong>Monto Total:</strong> $<?php echo number_format($total, 2); ?></p>
                            <a href="/proyectoFinalManolo/index.php" class="btn btn-primary">Volver al Inicio</a>
                        </div>
                    <?php else: ?>
                        <form method="POST" action="" class="payment-form">
                            <h2>Información de Pago</h2>
                            
                            <div class="form-group">
                                <label for="nombre_titular">Nombre del Titular:</label>
                                <input type="text" id="nombre_titular" name="nombre_titular" placeholder="Nombre Completo" required>
                            </div>

                            <div class="form-group">
                                <label for="numero_tarjeta">Número de Tarjeta:</label>
                                <input type="text" id="numero_tarjeta" name="numero_tarjeta" placeholder="1234 5678 9012 3456" maxlength="19" required inputmode="numeric">
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="fecha_expiracion">Fecha de Expiración:</label>
                                    <input type="text" id="fecha_expiracion" name="fecha_expiracion" placeholder="MM/YY" maxlength="5" required inputmode="numeric">
                                </div>

                                <div class="form-group">
                                    <label for="cvv">CVV:</label>
                                    <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="4" required inputmode="numeric">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Método de Pago:</label>
                                <div class="payment-methods">
                                    <input type="radio" id="tarjeta_credito" name="metodo_pago" value="tarjeta_credito" required hidden>
                                    <label for="tarjeta_credito" class="payment-method-btn">
                                        <span class="method-icon">💳</span>
                                        <span class="method-name">Tarjeta de Crédito</span>
                                    </label>

                                    <input type="radio" id="tarjeta_debito" name="metodo_pago" value="tarjeta_debito" required hidden>
                                    <label for="tarjeta_debito" class="payment-method-btn">
                                        <span class="method-icon">🏧</span>
                                        <span class="method-name">Tarjeta de Débito</span>
                                    </label>
                                </div>
                            </div>

                            <input type="hidden" name="total" value="<?php echo $total; ?>">

                            <button type="submit" class="btn btn-primary btn-large">Pagar $<?php echo number_format($total, 2); ?></button>
                        </form>
                    <?php endif; ?>
                </div>

                <div class="payment-summary-section">
                    <h2>Resumen del Pedido</h2>
                    <div class="payment-items">
                        <?php foreach($carrito as $item): ?>
                            <div class="payment-item">
                                <span><?php echo htmlspecialchars($item['nombre']); ?> x<?php echo $item['cantidad']; ?></span>
                                <span>$<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="payment-total">
                        <strong>Total a Pagar: $<?php echo number_format($total, 2); ?></strong>
                    </div>
                    <a href="/proyectoFinalManolo/views/carrito.php" class="btn btn-secondary">← Volver al Carrito</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
