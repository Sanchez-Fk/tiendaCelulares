<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'admin') {
    header("Location: /proyectoFinalManolo/views/login.php");
    exit();
}

require_once __DIR__ . '/../controllers/ProductoController.php';

$page_title = "Gestión de Producto";
include __DIR__ . '/../includes/header.php';

$productoController = new ProductoController();
$producto = null;
$editar = false;

if(isset($_GET['id'])) {
    $producto = $productoController->obtener($_GET['id']);
    $editar = true;
}

$resultado = null;
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if($editar) {
        $resultado = $productoController->actualizar();
    } else {
        $resultado = $productoController->crear();
    }
    
    if($resultado && isset($resultado['success'])) {
        header("Location: /proyectoFinalManolo/admin/index.php?success=1");
        exit();
    }
}
?>

<main class="main-content">
    <div class="container">
        <h1 class="page-title"><?php echo $editar ? 'Editar Producto' : 'Añadir Nuevo Producto'; ?></h1>
        
        <?php if($resultado && isset($resultado['error'])): ?>
            <div class="alert alert-error"><?php echo $resultado['error']; ?></div>
        <?php endif; ?>

        <div class="form-container">
            <form method="POST" action="" enctype="multipart/form-data" class="product-form">
                <?php if($editar): ?>
                    <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="nombre">Nombre del Producto</label>
                    <input type="text" id="nombre" name="nombre" 
                           value="<?php echo $producto ? htmlspecialchars($producto['nombre']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" rows="4" required><?php echo $producto ? htmlspecialchars($producto['descripcion']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="precio">Precio</label>
                    <input type="number" id="precio" name="precio" step="0.01" 
                           value="<?php echo $producto ? $producto['precio'] : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="especificaciones">Especificaciones</label>
                    <textarea id="especificaciones" name="especificaciones" rows="4" required><?php echo $producto ? htmlspecialchars($producto['especificaciones']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="number" id="stock" name="stock" 
                           value="<?php echo $producto ? $producto['stock'] : '0'; ?>" required>
                </div>

                <div class="form-group">
                    <label for="imagen">Imagen del Producto</label>
                    <?php if($editar && !empty($producto['imagen'])): ?>
                        <div class="current-image">
                            <p>Imagen actual:</p>
                            <img src="/proyectoFinalManolo/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="Imagen actual" class="preview-image">
                        </div>
                    <?php endif; ?>
                    <input type="file" id="imagen" name="imagen" accept="image/*" <?php echo $editar ? '' : 'required'; ?>>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><?php echo $editar ? 'Actualizar Producto' : 'Crear Producto'; ?></button>
                    <a href="/proyectoFinalManolo/admin/index.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>

