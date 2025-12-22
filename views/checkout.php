<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../controllers/CarritoController.php';

if(!isset($_SESSION['usuario_id'])) {
    header("Location: /proyectoFinalManolo/views/login.php");
    exit();
}

$carritoController = new CarritoController();
$carrito = $carritoController->obtener();

if(empty($carrito)) {
    header("Location: /proyectoFinalManolo/views/carrito.php");
    exit();
}

// Guardar datos de envío si vienen por POST
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['envio'] = [
        'ciudad' => trim($_POST['ciudad'] ?? ''),
        'departamento' => trim($_POST['departamento'] ?? ''),
        'direccion' => trim($_POST['direccion'] ?? ''),
        'municipio' => trim($_POST['municipio'] ?? ''),
        'descripcion' => trim($_POST['descripcion'] ?? ''),
    ];

    header("Location: /proyectoFinalManolo/views/pago.php");
    exit();
}

$page_title = "Dirección de Envío";
include __DIR__ . '/../includes/header.php';
?>

<main class="main-content">
    <div class="container">
        <h1 class="page-title">Dirección de Envío</h1>

        <form method="POST" action="" class="checkout-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="ciudad">Ciudad:</label>
                    <input type="text" id="ciudad" name="ciudad" required value="<?php echo htmlspecialchars($_SESSION['envio']['ciudad'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="departamento">Departamento / Estado:</label>
                    <input type="text" id="departamento" name="departamento" required value="<?php echo htmlspecialchars($_SESSION['envio']['departamento'] ?? ''); ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="municipio">Municipio:</label>
                <input type="text" id="municipio" name="municipio" required value="<?php echo htmlspecialchars($_SESSION['envio']['municipio'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="direccion">Dirección completa:</label>
                <input type="text" id="direccion" name="direccion" required value="<?php echo htmlspecialchars($_SESSION['envio']['direccion'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción del lugar (p. ej. referencia):</label>
                <textarea id="descripcion" name="descripcion" rows="3"><?php echo htmlspecialchars($_SESSION['envio']['descripcion'] ?? ''); ?></textarea>
            </div>

            <div class="form-actions">
                <a href="/proyectoFinalManolo/views/carrito.php" class="btn btn-secondary">← Volver al Carrito</a>
                <button type="submit" class="btn btn-primary">Continuar a Pago</button>
            </div>
        </form>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
