<?php
require_once __DIR__ . '/../models/Usuario.php';

class CambiarContrasenaController {
    private $usuario;

    public function __construct() {
        $this->usuario = new Usuario();
    }

    public function cambiar() {
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if(!isset($_SESSION['usuario_id'])) {
            return ['error' => 'No autorizado'];
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $contrasena_actual = $_POST['contrasena_actual'] ?? '';
            $contrasena_nueva = $_POST['contrasena_nueva'] ?? '';
            $confirmar_contrasena = $_POST['confirmar_contrasena'] ?? '';

            // Validaciones
            if(empty($contrasena_actual) || empty($contrasena_nueva) || empty($confirmar_contrasena)) {
                return ['error' => 'Todos los campos son requeridos'];
            }

            if(strlen($contrasena_nueva) < 6) {
                return ['error' => 'La contraseña debe tener al menos 6 caracteres'];
            }

            if($contrasena_nueva !== $confirmar_contrasena) {
                return ['error' => 'Las nuevas contraseñas no coinciden'];
            }

            if($contrasena_actual === $contrasena_nueva) {
                return ['error' => 'La nueva contraseña debe ser diferente a la actual'];
            }

            // Cambiar contraseña
            if($this->usuario->cambiarContrasena($_SESSION['usuario_id'], $contrasena_actual, $contrasena_nueva)) {
                return ['success' => 'Contraseña actualizada correctamente'];
            } else {
                return ['error' => 'La contraseña actual es incorrecta'];
            }
        }
        return null;
    }
}
?>
