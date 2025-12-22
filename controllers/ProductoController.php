<?php
require_once __DIR__ . '/../models/Producto.php';

class ProductoController {
    private $producto;

    public function __construct() {
        $this->producto = new Producto();
    }

    public function listar() {
        return $this->producto->obtenerTodos();
    }

    public function obtener($id) {
        return $this->producto->obtenerPorId($id);
    }

    public function crear() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->producto->nombre = $_POST['nombre'] ?? '';
            $this->producto->descripcion = $_POST['descripcion'] ?? '';
            $this->producto->precio = $_POST['precio'] ?? 0;
            $this->producto->especificaciones = $_POST['especificaciones'] ?? '';
            $this->producto->stock = $_POST['stock'] ?? 0;

            // Manejo de imagen
            if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
                $upload_dir = __DIR__ . '/../assets/img/';
                if(!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $file_extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                $file_name = uniqid() . '.' . $file_extension;
                $file_path = $upload_dir . $file_name;
                
                if(move_uploaded_file($_FILES['imagen']['tmp_name'], $file_path)) {
                    $this->producto->imagen = 'assets/img/' . $file_name;
                }
            }

            if($this->producto->crear()) {
                return ['success' => 'Producto creado exitosamente'];
            } else {
                return ['error' => 'Error al crear producto'];
            }
        }
        return null;
    }

    public function actualizar() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->producto->id = $_POST['id'] ?? 0;
            $this->producto->nombre = $_POST['nombre'] ?? '';
            $this->producto->descripcion = $_POST['descripcion'] ?? '';
            $this->producto->precio = $_POST['precio'] ?? 0;
            $this->producto->especificaciones = $_POST['especificaciones'] ?? '';
            $this->producto->stock = $_POST['stock'] ?? 0;

            // Obtener imagen actual
            $producto_actual = $this->producto->obtenerPorId($this->producto->id);
            $this->producto->imagen = $producto_actual['imagen'] ?? '';

            // Manejo de nueva imagen
            if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
                $upload_dir = __DIR__ . '/../assets/img/';
                if(!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $file_extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                $file_name = uniqid() . '.' . $file_extension;
                $file_path = $upload_dir . $file_name;
                
                if(move_uploaded_file($_FILES['imagen']['tmp_name'], $file_path)) {
                    // Eliminar imagen anterior si existe
                    if(!empty($this->producto->imagen)) {
                        $old_path = __DIR__ . '/../' . $this->producto->imagen;
                        if(file_exists($old_path)) {
                            unlink($old_path);
                        }
                    }
                    $this->producto->imagen = 'assets/img/' . $file_name;
                }
            }

            if($this->producto->actualizar()) {
                return ['success' => 'Producto actualizado exitosamente'];
            } else {
                return ['error' => 'Error al actualizar producto'];
            }
        }
        return null;
    }

    public function eliminar($id) {
        $producto = $this->producto->obtenerPorId($id);
        if($producto && $this->producto->eliminar($id)) {
            // Eliminar imagen
            if(!empty($producto['imagen'])) {
                $image_path = __DIR__ . '/../' . $producto['imagen'];
                if(file_exists($image_path)) {
                    unlink($image_path);
                }
            }
            return ['success' => 'Producto eliminado exitosamente'];
        }
        return ['error' => 'Error al eliminar producto'];
    }

    public function buscar($termino) {
        if(empty(trim($termino))) {
            return [];
        }
        return $this->producto->buscar($termino);
    }
}
?>

