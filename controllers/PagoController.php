<?php
require_once __DIR__ . '/../models/Pago.php';
require_once __DIR__ . '/../models/Carrito.php';
require_once __DIR__ . '/../config/database.php';

class PagoController {
    private $pago;
    private $carrito;

    public function __construct() {
        $this->pago = new Pago();
        $this->carrito = new Carrito();
        $this->verificarTabla();
    }

    private function verificarTabla() {
        try {
            $database = new Database();
            $conn = $database->getConnection();
            
            $stmt = $conn->query("SHOW TABLES LIKE 'pagos'");
            if ($stmt->rowCount() == 0) {
                // Crear tabla si no existe (con campos de envío)
                $sql = "CREATE TABLE IF NOT EXISTS pagos (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    usuario_id INT NOT NULL,
                    total DECIMAL(10, 2) NOT NULL,
                    metodo_pago VARCHAR(50) NOT NULL,
                    numero_tarjeta VARCHAR(20) NOT NULL,
                    nombre_titular VARCHAR(100) NOT NULL,
                    fecha_expiracion VARCHAR(5) NOT NULL,
                    cvv VARCHAR(10) NOT NULL,
                    estado VARCHAR(20) DEFAULT 'completado',
                    ciudad VARCHAR(100),
                    departamento VARCHAR(100),
                    direccion VARCHAR(255),
                    municipio VARCHAR(100),
                    descripcion_lugar TEXT,
                    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
                )";
                $conn->exec($sql);
            } else {
                // Si la tabla existe, asegurarse de que tenga las columnas de envío
                $cols = ['ciudad','departamento','direccion','municipio','descripcion_lugar'];
                foreach($cols as $col) {
                    $check = $conn->query("SHOW COLUMNS FROM pagos LIKE '". $col ."'");
                    if($check->rowCount() == 0) {
                        // Agregar columna según el tipo
                        switch($col) {
                            case 'descripcion_lugar':
                                $conn->exec("ALTER TABLE pagos ADD COLUMN descripcion_lugar TEXT NULL");
                                break;
                            case 'direccion':
                                $conn->exec("ALTER TABLE pagos ADD COLUMN direccion VARCHAR(255) NULL");
                                break;
                            default:
                                $conn->exec("ALTER TABLE pagos ADD COLUMN " . $col . " VARCHAR(100) NULL");
                                break;
                        }
                    }
                }
            }
        } catch(Exception $e) {
            // Silenciosamente fallar si no se puede verificar
        }
    }

    public function procesar() {
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if(!isset($_SESSION['usuario_id'])) {
            return ['error' => 'No autorizado'];
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $metodo_pago = $_POST['metodo_pago'] ?? '';
            $numero_tarjeta = $_POST['numero_tarjeta'] ?? '';
            $nombre_titular = $_POST['nombre_titular'] ?? '';
            $fecha_expiracion = $_POST['fecha_expiracion'] ?? '';
            $cvv = $_POST['cvv'] ?? '';
            $total = $_POST['total'] ?? 0;

            // Validaciones básicas
            if(empty($metodo_pago) || empty($numero_tarjeta) || empty($nombre_titular) || empty($fecha_expiracion) || empty($cvv)) {
                return ['error' => 'Todos los campos son requeridos'];
            }

            if(!preg_match('/^\d{13,19}$/', str_replace(' ', '', $numero_tarjeta))) {
                return ['error' => 'Número de tarjeta inválido'];
            }

            if(!preg_match('/^\d{3,4}$/', $cvv)) {
                return ['error' => 'CVV inválido'];
            }

            if($total <= 0) {
                return ['error' => 'El total debe ser mayor a 0'];
            }

            // Procesar el pago
            $this->pago->usuario_id = $_SESSION['usuario_id'];
            $this->pago->total = $total;
            $this->pago->metodo_pago = $metodo_pago;
            // Enmascarar el número de tarjeta (guardar solo los últimos 4 dígitos)
            $ultimos4 = substr(str_replace(' ', '', $numero_tarjeta), -4);
            $this->pago->numero_tarjeta = "****-****-****-" . $ultimos4;
            $this->pago->nombre_titular = $nombre_titular;
            $this->pago->fecha_expiracion = $fecha_expiracion;
            $this->pago->cvv = "***"; // No guardar el CVV real
            // Agregar datos de envío desde la sesión si existen
            $envio = $_SESSION['envio'] ?? [];
            $this->pago->ciudad = $envio['ciudad'] ?? null;
            $this->pago->departamento = $envio['departamento'] ?? null;
            $this->pago->direccion = $envio['direccion'] ?? null;
            $this->pago->municipio = $envio['municipio'] ?? null;
            $this->pago->descripcion_lugar = $envio['descripcion'] ?? null;

            $pago_id = $this->pago->crear();

            if($pago_id) {
                // Limpiar el carrito
                $this->carrito->limpiar($_SESSION['usuario_id']);
                // Redirigir a la página de pago no disponible
                header("Location: /proyectoFinalManolo/views/pago_no_disponible.php");
                exit();
            } else {
                return ['error' => 'Error al procesar el pago'];
            }
        }
        return null;
    }

    public function obtenerHistorial($usuario_id) {
        return $this->pago->obtenerPorUsuario($usuario_id);
    }
}
?>
