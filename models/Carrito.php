<?php
require_once __DIR__ . '/../config/database.php';

class Carrito {
    private $conn;
    private $table = "carrito";

    public $id;
    public $usuario_id;
    public $producto_id;
    public $cantidad;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function agregarProducto($usuario_id, $producto_id, $cantidad = 1) {
        // Verificar si el producto ya está en el carrito
        $query = "SELECT id, cantidad FROM " . $this->table . " 
                  WHERE usuario_id = :usuario_id AND producto_id = :producto_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":usuario_id", $usuario_id);
        $stmt->bindParam(":producto_id", $producto_id);
        $stmt->execute();
        $existe = $stmt->fetch(PDO::FETCH_ASSOC);

        if($existe) {
            // Actualizar cantidad
            $nueva_cantidad = $existe['cantidad'] + $cantidad;
            $query = "UPDATE " . $this->table . " SET cantidad = :cantidad WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":cantidad", $nueva_cantidad);
            $stmt->bindParam(":id", $existe['id']);
            return $stmt->execute();
        } else {
            // Insertar nuevo
            $query = "INSERT INTO " . $this->table . " (usuario_id, producto_id, cantidad) 
                      VALUES (:usuario_id, :producto_id, :cantidad)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":usuario_id", $usuario_id);
            $stmt->bindParam(":producto_id", $producto_id);
            $stmt->bindParam(":cantidad", $cantidad);
            return $stmt->execute();
        }
    }

    public function obtenerCarritoUsuario($usuario_id) {
        $query = "SELECT c.*, p.nombre, p.precio, p.imagen, p.descripcion 
                  FROM " . $this->table . " c 
                  INNER JOIN productos p ON c.producto_id = p.id 
                  WHERE c.usuario_id = :usuario_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":usuario_id", $usuario_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminarProducto($carrito_id, $usuario_id) {
        $query = "DELETE FROM " . $this->table . " 
                  WHERE id = :id AND usuario_id = :usuario_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $carrito_id);
        $stmt->bindParam(":usuario_id", $usuario_id);
        return $stmt->execute();
    }

    public function actualizarCantidad($carrito_id, $usuario_id, $cantidad) {
        $query = "UPDATE " . $this->table . " SET cantidad = :cantidad 
                  WHERE id = :id AND usuario_id = :usuario_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":cantidad", $cantidad);
        $stmt->bindParam(":id", $carrito_id);
        $stmt->bindParam(":usuario_id", $usuario_id);
        return $stmt->execute();
    }

    public function limpiar($usuario_id) {
        $query = "DELETE FROM " . $this->table . " WHERE usuario_id = :usuario_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":usuario_id", $usuario_id);
        return $stmt->execute();
    }
}
?>



