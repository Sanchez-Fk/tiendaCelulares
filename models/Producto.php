<?php
require_once __DIR__ . '/../config/database.php';

class Producto {
    private $conn;
    private $table = "productos";

    public $id;
    public $nombre;
    public $descripcion;
    public $precio;
    public $imagen;
    public $especificaciones;
    public $stock;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function obtenerTodos() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY fecha_creacion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear() {
        $query = "INSERT INTO " . $this->table . " 
                  (nombre, descripcion, precio, imagen, especificaciones, stock) 
                  VALUES (:nombre, :descripcion, :precio, :imagen, :especificaciones, :stock)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":precio", $this->precio);
        $stmt->bindParam(":imagen", $this->imagen);
        $stmt->bindParam(":especificaciones", $this->especificaciones);
        $stmt->bindParam(":stock", $this->stock);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function actualizar() {
        $query = "UPDATE " . $this->table . " 
                  SET nombre = :nombre, descripcion = :descripcion, precio = :precio, 
                      imagen = :imagen, especificaciones = :especificaciones, stock = :stock 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":precio", $this->precio);
        $stmt->bindParam(":imagen", $this->imagen);
        $stmt->bindParam(":especificaciones", $this->especificaciones);
        $stmt->bindParam(":stock", $this->stock);
        
        return $stmt->execute();
    }

    public function eliminar($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function buscar($termino) {
        $termino_like = "%" . $termino . "%";
        
        // Si el término es numérico, buscar también por ID
        if(is_numeric($termino)) {
            $query = "SELECT * FROM " . $this->table . " 
                      WHERE nombre LIKE :termino 
                      OR id = :id 
                      OR descripcion LIKE :termino
                      ORDER BY fecha_creacion DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":termino", $termino_like);
            $id = (int)$termino;
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        } else {
            // Si no es numérico, solo buscar por nombre y descripción
            $query = "SELECT * FROM " . $this->table . " 
                      WHERE nombre LIKE :termino 
                      OR descripcion LIKE :termino
                      ORDER BY fecha_creacion DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":termino", $termino_like);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

