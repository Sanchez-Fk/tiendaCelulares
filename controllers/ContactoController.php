<?php
class ContactoController {
    private $contactos_file = __DIR__ . '/../data/contactos.txt';

    public function __construct() {
        $dir = dirname($this->contactos_file);
        if(!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    public function guardar() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'] ?? '';
            $titulo = $_POST['titulo'] ?? '';
            $mensaje = $_POST['mensaje'] ?? '';

            if(empty($email) || empty($titulo) || empty($mensaje)) {
                return ['error' => 'Todos los campos son requeridos'];
            }

            $fecha = date('Y-m-d H:i:s');
            $contenido = "========================================\n";
            $contenido .= "Fecha: $fecha\n";
            $contenido .= "Email: $email\n";
            $contenido .= "Título: $titulo\n";
            $contenido .= "Mensaje: $mensaje\n";
            $contenido .= "========================================\n\n";

            if(file_put_contents($this->contactos_file, $contenido, FILE_APPEND)) {
                return ['success' => 'Mensaje enviado exitosamente'];
            } else {
                return ['error' => 'Error al guardar el mensaje'];
            }
        }
        return null;
    }
}
?>



