<?php
require_once __DIR__ . '/../controllers/CarritoController.php';
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Carrito de Compras";
include __DIR__ . '/../includes/header.php';

if(!isset($_SESSION['usuario_id'])) {
    header("Location: /proyectoFinalManolo/views/login.php");
    exit();
}

$carritoController = new CarritoController();
$carrito = $carritoController->obtener();
$total = 0;
?>

<main class="main-content">
    <div class="container">
        <h1 class="page-title">Carrito de Compras</h1>
        
        <?php if(empty($carrito)): ?>
            <div class="empty-cart">
                <p>Tu carrito está vacío</p>
                <a href="/proyectoFinalManolo/index.php" class="btn btn-primary">Ver Productos</a>
            </div>
        <?php else: ?>
            <div class="cart-items">
                <?php foreach($carrito as $item): ?>
                    <?php 
                    $subtotal = $item['precio'] * $item['cantidad'];
                    $total += $subtotal;
                    ?>
                    <div class="cart-item">
                        <img src="/proyectoFinalManolo/<?php echo htmlspecialchars($item['imagen'] ?? 'assets/img/placeholder.svg'); ?>" 
                             alt="<?php echo htmlspecialchars($item['nombre']); ?>">
                        <div class="cart-item-info">
                            <h3><?php echo htmlspecialchars($item['nombre']); ?></h3>
                            <p class="cart-item-price">$<?php echo number_format($item['precio'], 2); ?></p>
                        </div>
                        <div class="cart-item-actions">
                            <form method="POST" action="/proyectoFinalManolo/controllers/carrito_action.php" class="update-quantity-form">
                                <input type="hidden" name="action" value="actualizar">
                                <input type="hidden" name="carrito_id" value="<?php echo $item['id']; ?>">
                                <label>Cantidad:</label>
                                <input type="number" name="cantidad" value="<?php echo $item['cantidad']; ?>" min="1" 
                                       onchange="this.form.submit()">
                            </form>
                            <p class="cart-item-subtotal">Subtotal: $<?php echo number_format($subtotal, 2); ?></p>
                            <form method="POST" action="/proyectoFinalManolo/controllers/carrito_action.php" class="remove-item-form">
                                <input type="hidden" name="action" value="eliminar">
                                <input type="hidden" name="carrito_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="cart-summary">
                <h2>Total: $<?php echo number_format($total, 2); ?></h2>
                <a href="/proyectoFinalManolo/views/checkout.php" class="btn btn-primary btn-large">Proceder al Pago</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>

