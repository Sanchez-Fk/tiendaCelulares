<?php
class Database {
    private $host = "localhost";
    private $db_name = "tienda_moviles";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // Intentar conectar directamente a la base de datos
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
                                  $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            // Si la base de datos no existe, intentar crearla
            if(strpos($exception->getMessage(), "Unknown database") !== false) {
                try {
                    // Conectar sin especificar base de datos
                    $temp_conn = new PDO("mysql:host=" . $this->host, 
                                        $this->username, $this->password);
                    $temp_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    // Crear base de datos
                    $temp_conn->exec("CREATE DATABASE IF NOT EXISTS " . $this->db_name . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                    
                    // Ahora conectar a la base de datos creada
                    $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
                                          $this->username, $this->password);
                    $this->conn->exec("set names utf8");
                    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch(PDOException $e) {
                    $error_msg = "Error de conexión: " . $e->getMessage();
                    $error_msg .= "<br><br><strong>Solución:</strong> Ejecuta el archivo <a href='/proyectoFinalManolo/install.php' style='color: #d4af37;'>install.php</a> para crear la base de datos y las tablas automáticamente.";
                    echo $error_msg;
                }
            } else {
                $error_msg = "Error de conexión: " . $exception->getMessage();
                echo $error_msg;
            }
        }
        return $this->conn;
    }
}
?>

