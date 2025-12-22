<?php
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {
    private $usuario;

    public function __construct() {
        $this->usuario = new Usuario();
    }

    public function login() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if($this->usuario->login($email, $password)) {
                if(session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['usuario_id'] = $this->usuario->id;
                $_SESSION['usuario_nombre'] = $this->usuario->nombre;
                $_SESSION['usuario_email'] = $this->usuario->email;
                $_SESSION['usuario_tipo'] = $this->usuario->tipo;
                
                if($this->usuario->tipo == 'admin') {
                    header("Location: /proyectoFinalManolo/admin/index.php");
                } else {
                    header("Location: /proyectoFinalManolo/index.php");
                }
                exit();
            } else {
                return ['error' => 'Email o contraseña incorrectos'];
            }
        }
        return null;
    }

    public function registro() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if(empty($nombre) || empty($email) || empty($password)) {
                return ['error' => 'Todos los campos son requeridos'];
            }

            if($password !== $confirm_password) {
                return ['error' => 'Las contraseñas no coinciden'];
            }

            if($this->usuario->existeEmail($email)) {
                return ['error' => 'El email ya está registrado'];
            }

            $this->usuario->nombre = $nombre;
            $this->usuario->email = $email;
            $this->usuario->password = $password;

            if($this->usuario->registrar()) {
                return ['success' => 'Registro exitoso. Por favor inicia sesión.'];
            } else {
                return ['error' => 'Error al registrar usuario'];
            }
        }
        return null;
    }

    public function logout() {
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location: /proyectoFinalManolo/index.php");
        exit();
    }
}
?>

