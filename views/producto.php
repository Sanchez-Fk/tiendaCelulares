<?php
require_once __DIR__ . '/../controllers/ProductoController.php';
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Detalles del Producto";
include __DIR__ . '/../includes/header.php';

$productoController = new ProductoController();
$producto_id = $_GET['id'] ?? 0;
$producto = $productoController->obtener($producto_id);

if(!$producto) {
    header("Location: /proyectoFinalManolo/index.php");
    exit();
}
?>

<main class="main-content">
    <div class="container">
        <div class="product-detail">
            <div class="product-image-large">
                <img src="/proyectoFinalManolo/<?php echo htmlspecialchars($producto['imagen'] ?? 'assets/img/placeholder.svg'); ?>" 
                     alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
            </div>
            <div class="product-details">
                <h1><?php echo htmlspecialchars($producto['nombre']); ?></h1>
                <p class="product-price-large">$<?php echo number_format($producto['precio'], 2); ?></p>
                <div class="product-description">
                    <h3>Descripción</h3>
                    <p><?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?></p>
                </div>
                <div class="product-specs">
                    <h3>Especificaciones</h3>
                    <p><?php echo nl2br(htmlspecialchars($producto['especificaciones'])); ?></p>
                </div>
                <div class="product-stock">
                    <p>Stock disponible: <strong><?php echo $producto['stock']; ?></strong></p>
                </div>
                <?php if(isset($_SESSION['usuario_id'])): ?>
                    <form method="POST" action="/proyectoFinalManolo/controllers/carrito_action.php" class="add-to-cart-form">
                        <input type="hidden" name="action" value="agregar">
                        <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                        <div class="form-group">
                            <label for="cantidad">Cantidad:</label>
                            <input type="number" id="cantidad" name="cantidad" value="1" min="1" max="<?php echo $producto['stock']; ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">🛒 Añadir al Carrito</button>
                    </form>
                <?php else: ?>
                    <p class="login-prompt">Debes <a href="/proyectoFinalManolo/views/login.php">iniciar sesión</a> para agregar productos al carrito.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>

