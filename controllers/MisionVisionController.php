<?php
require_once __DIR__ . '/../models/MisionVision.php';

class MisionVisionController {
    private $misionVision;

    public function __construct() {
        $this->misionVision = new MisionVision();
    }

    public function obtener() {
        return $this->misionVision->obtener();
    }

    public function actualizar() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $mision = $_POST['mision'] ?? '';
            $vision = $_POST['vision'] ?? '';
            $valores = $_POST['valores'] ?? '';

            if(empty($mision) || empty($vision)) {
                return ['error' => 'La misión y visión son requeridas'];
            }

            if($this->misionVision->actualizar($mision, $vision, $valores)) {
                return ['success' => 'Misión y Visión actualizadas exitosamente'];
            } else {
                return ['error' => 'Error al actualizar Misión y Visión'];
            }
        }
        return null;
    }
}
?>



