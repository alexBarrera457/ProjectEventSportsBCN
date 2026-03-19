<?php

class UserController {

    private $conn;

    public function __construct() {
        $host = "localhost";
        $usuario = "root";
        $password = "";
        $base_datos = "mi_base_datos";

        $this->conn = new mysqli($host, $usuario, $password, $base_datos);
        
        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }

        $this->conn->set_charset("utf8mb4");
    }

    public function login($username, $password) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $sql = "SELECT id, nombre_usuario, password_hash FROM usuarios WHERE nombre_usuario = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['nombre_usuario'];
                return true;
            }
        }
        
        return false;
    }

    public function logout() {

    }

    public function register($data) {

    }

    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}

?>