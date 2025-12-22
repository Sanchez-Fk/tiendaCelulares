<?php
require_once __DIR__ . '/../models/Carrito.php';

class CarritoController {
    private $carrito;

    public function __construct() {
        $this->carrito = new Carrito();
    }

    public function agregar() {
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if(!isset($_SESSION['usuario_id'])) {
            return ['error' => 'Debes iniciar sesión para agregar productos al carrito'];
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario_id = $_SESSION['usuario_id'];
            $producto_id = $_POST['producto_id'] ?? 0;
            $cantidad = $_POST['cantidad'] ?? 1;

            if($this->carrito->agregarProducto($usuario_id, $producto_id, $cantidad)) {
                return ['success' => 'Producto agregado al carrito'];
            } else {
                return ['error' => 'Error al agregar producto al carrito'];
            }
        }
        return null;
    }

    public function obtener() {
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if(!isset($_SESSION['usuario_id'])) {
            return [];
        }
        return $this->carrito->obtenerCarritoUsuario($_SESSION['usuario_id']);
    }

    public function eliminar() {
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if(!isset($_SESSION['usuario_id'])) {
            return ['error' => 'Debes iniciar sesión'];
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $carrito_id = $_POST['carrito_id'] ?? 0;
            $usuario_id = $_SESSION['usuario_id'];

            if($this->carrito->eliminarProducto($carrito_id, $usuario_id)) {
                return ['success' => 'Producto eliminado del carrito'];
            } else {
                return ['error' => 'Error al eliminar producto'];
            }
        }
        return null;
    }

    public function actualizarCantidad() {
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if(!isset($_SESSION['usuario_id'])) {
            return ['error' => 'Debes iniciar sesión'];
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $carrito_id = $_POST['carrito_id'] ?? 0;
            $cantidad = $_POST['cantidad'] ?? 1;
            $usuario_id = $_SESSION['usuario_id'];

            if($this->carrito->actualizarCantidad($carrito_id, $usuario_id, $cantidad)) {
                return ['success' => 'Cantidad actualizada'];
            } else {
                return ['error' => 'Error al actualizar cantidad'];
            }
        }
        return null;
    }
}
?>

