<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'admin') {
    header("Location: /proyectoFinalManolo/views/login.php");
    exit();
}

require_once __DIR__ . '/../controllers/ProductoController.php';

$page_title = "Panel de Administración";
include __DIR__ . '/../includes/header.php';

$productoController = new ProductoController();

// Manejar búsqueda
$busqueda = $_GET['buscar'] ?? '';
$productos = !empty($busqueda) ? $productoController->buscar($busqueda) : $productoController->listar();
?>

<main class="main-content">
    <div class="container">
        <h1 class="page-title">Panel de Administración</h1>
        <div class="admin-actions">
            <a href="/proyectoFinalManolo/admin/producto_form.php" class="btn btn-primary">➕ Añadir Nuevo Producto</a>
            <a href="/proyectoFinalManolo/admin/mision_vision.php" class="btn btn-primary">📝 Editar Misión y Visión</a>
            <a href="/proyectoFinalManolo/admin/logo.php" class="btn btn-primary">🖼️ Gestionar Logo</a>
            <a href="/proyectoFinalManolo/admin/cambiar_contrasena.php" class="btn btn-primary">🔐 Cambiar Contraseña</a>
            <a href="/proyectoFinalManolo/admin/pedidos.php" class="btn btn-primary">📦 Ver Pedidos</a>
        </div>

        <div class="search-section">
            <form method="GET" action="" class="search-form">
                <div class="search-input-group">
                    <input type="text" name="buscar" 
                           placeholder="Buscar por nombre o ID del producto..." 
                           value="<?php echo htmlspecialchars($busqueda); ?>"
                           class="search-input">
                    <button type="submit" class="btn btn-primary">🔍 Buscar</button>
                    <?php if(!empty($busqueda)): ?>
                        <a href="/proyectoFinalManolo/admin/index.php" class="btn btn-secondary">Limpiar</a>
                    <?php endif; ?>
                </div>
            </form>
            <?php if(!empty($busqueda)): ?>
                <p class="search-results-info">
                    Resultados de búsqueda para: <strong>"<?php echo htmlspecialchars($busqueda); ?>"</strong> 
                    (<?php echo count($productos); ?> resultado(s))
                </p>
            <?php endif; ?>
        </div>

        <div class="admin-products">
            <h2><?php echo !empty($busqueda) ? 'Resultados de Búsqueda' : 'Productos Existentes'; ?></h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($productos)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem;">
                                <?php if(!empty($busqueda)): ?>
                                    No se encontraron productos que coincidan con "<?php echo htmlspecialchars($busqueda); ?>"
                                <?php else: ?>
                                    No hay productos registrados aún.
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($productos as $producto): ?>
                            <tr>
                                <td><?php echo $producto['id']; ?></td>
                                <td>
                                    <img src="/proyectoFinalManolo/<?php echo htmlspecialchars($producto['imagen'] ?? 'assets/img/placeholder.svg'); ?>" 
                                         alt="<?php echo htmlspecialchars($producto['nombre']); ?>" class="admin-thumb">
                                </td>
                                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                                <td><?php echo $producto['stock']; ?></td>
                                <td class="action-buttons">
                                    <a href="/proyectoFinalManolo/admin/producto_form.php?id=<?php echo $producto['id']; ?>" class="btn btn-secondary btn-sm">Editar</a>
                                    <a href="/proyectoFinalManolo/admin/producto_delete.php?id=<?php echo $producto['id']; ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('¿Estás seguro de eliminar este producto?')">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>

