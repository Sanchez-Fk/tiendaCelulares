<?php
require_once __DIR__ . '/CarritoController.php';

$carritoController = new CarritoController();
$action = $_POST['action'] ?? '';

switch($action) {
    case 'agregar':
        $resultado = $carritoController->agregar();
        break;
    case 'eliminar':
        $resultado = $carritoController->eliminar();
        break;
    case 'actualizar':
        $resultado = $carritoController->actualizarCantidad();
        break;
    default:
        $resultado = ['error' => 'Acción no válida'];
}

// Redirigir según la acción
if($action == 'agregar') {
    $producto_id = $_POST['producto_id'] ?? 0;
    header("Location: /proyectoFinalManolo/views/producto.php?id=" . $producto_id . ($resultado && isset($resultado['success']) ? '&success=1' : ''));
} else {
    header("Location: /proyectoFinalManolo/views/carrito.php" . ($resultado && isset($resultado['success']) ? '?success=1' : ''));
}
exit();
?>



