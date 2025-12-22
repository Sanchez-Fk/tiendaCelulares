<?php
require_once __DIR__ . '/../config/database.php';

class MisionVision {
    private $conn;
    private $table = "mision_vision";

    public $id;
    public $mision;
    public $vision;
    public $valores;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function obtener() {
        $query = "SELECT * FROM " . $this->table . " WHERE id = 1 LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Si no existe, crear registro por defecto y devolverlo
        if(!$result) {
            $this->crearDefault();
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        return $result ? $result : [
            'id' => 1,
            'mision' => 'Misión no definida',
            'vision' => 'Visión no definida',
            'valores' => ''
        ];
    }

    public function actualizar($mision, $vision, $valores = '') {
        // Verificar si existe
        $existente = $this->obtener();
        
        if($existente) {
            $query = "UPDATE " . $this->table . " 
                      SET mision = :mision, vision = :vision, valores = :valores 
                      WHERE id = 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":mision", $mision);
            $stmt->bindParam(":vision", $vision);
            $stmt->bindParam(":valores", $valores);
            return $stmt->execute();
        } else {
            $query = "INSERT INTO " . $this->table . " (id, mision, vision, valores) 
                      VALUES (1, :mision, :vision, :valores)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":mision", $mision);
            $stmt->bindParam(":vision", $vision);
            $stmt->bindParam(":valores", $valores);
            return $stmt->execute();
        }
    }

    private function crearDefault() {
        $mision_default = "Nuestra misión es ofrecer a nuestros clientes los mejores dispositivos móviles del mercado, proporcionando productos de alta calidad, tecnología de vanguardia y un servicio excepcional. Nos comprometemos a mantenernos a la vanguardia de la innovación tecnológica y a brindar una experiencia de compra única que supere las expectativas de nuestros clientes.";
        
        $vision_default = "Ser la tienda líder en dispositivos móviles, reconocida por nuestra excelencia en servicio al cliente, nuestra amplia gama de productos de última generación y nuestro compromiso con la satisfacción del cliente. Aspiramos a ser el destino preferido para todos aquellos que buscan tecnología móvil de calidad, innovación y confiabilidad.";
        
        $valores_default = "Calidad: Ofrecemos solo productos de las mejores marcas y tecnología probada.\nInnovación: Estamos siempre al día con las últimas tendencias tecnológicas.\nServicio: Nuestro equipo está comprometido con la satisfacción del cliente.\nConfianza: Construimos relaciones duraderas basadas en la transparencia y honestidad.";
        
        $query = "INSERT INTO " . $this->table . " (id, mision, vision, valores) 
                  VALUES (1, :mision, :vision, :valores)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":mision", $mision_default);
        $stmt->bindParam(":vision", $vision_default);
        $stmt->bindParam(":valores", $valores_default);
        $stmt->execute();
    }
}
?>

