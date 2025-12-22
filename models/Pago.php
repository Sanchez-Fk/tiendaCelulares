<?php
require_once __DIR__ . '/../config/database.php';

class Pago {
    private $conn;
    private $table = "pagos";

    public $id;
    public $usuario_id;
    public $total;
    public $metodo_pago;
    public $estado;
    public $numero_tarjeta;
    public $nombre_titular;
    public $fecha_expiracion;
    public $cvv;
    // Campos de envío
    public $ciudad;
    public $departamento;
    public $direccion;
    public $municipio;
    public $descripcion_lugar;
    public $fecha_creacion;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function crear() {
        $query = "INSERT INTO " . $this->table . " (usuario_id, total, metodo_pago, numero_tarjeta, nombre_titular, fecha_expiracion, cvv, estado, ciudad, departamento, direccion, municipio, descripcion_lugar, fecha_creacion)
                  VALUES (:usuario_id, :total, :metodo_pago, :numero_tarjeta, :nombre_titular, :fecha_expiracion, :cvv, 'completado', :ciudad, :departamento, :direccion, :municipio, :descripcion_lugar, NOW())";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":usuario_id", $this->usuario_id);
        $stmt->bindParam(":total", $this->total);
        $stmt->bindParam(":metodo_pago", $this->metodo_pago);
        $stmt->bindParam(":numero_tarjeta", $this->numero_tarjeta);
        $stmt->bindParam(":nombre_titular", $this->nombre_titular);
        $stmt->bindParam(":fecha_expiracion", $this->fecha_expiracion);
        $stmt->bindParam(":cvv", $this->cvv);
        $stmt->bindParam(":ciudad", $this->ciudad);
        $stmt->bindParam(":departamento", $this->departamento);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":municipio", $this->municipio);
        $stmt->bindParam(":descripcion_lugar", $this->descripcion_lugar);
        
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function obtenerPorUsuario($usuario_id) {
        if($usuario_id === null) {
            $query = "SELECT * FROM " . $this->table . " ORDER BY fecha_creacion DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $query = "SELECT * FROM " . $this->table . " WHERE usuario_id = :usuario_id ORDER BY fecha_creacion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":usuario_id", $usuario_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
