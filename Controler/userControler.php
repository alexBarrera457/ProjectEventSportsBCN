<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user = new UserController();
    //vemos que boton es el que presionar el usuario
    if (isset($_POST["login"])) {
        echo "<p>login button clicked.</p>";
        $user->login();
    }
    if (isset($_POST["logout"])) {
        echo "<p>logout button clicked.</p>";
        $user->logout();
    }
    if (isset($_POST["register"])) {
        echo "<p>register button clicked.</p>";
        $user->register();
    }
}

class UserController
{

    private $conn;

    public function __construct()
    {
        $host = "localhost";
        $usuario = "root";
        $password = "";
        $base_datos = "eventsportsbcn";

        $this->conn = new mysqli($host, $usuario, $password, $base_datos);

        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }

        $this->conn->set_charset("utf8mb4");
    }

    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $user = $_POST["user"];
        $password = $_POST["password"];

        $sql = "SELECT id, nombre_usuario, password_hash FROM usuarios WHERE nombre_usuario = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            $_SESSION['login_error'] = "Error interno del servidor. Inténtalo más tarde.";
            header('Location: ../View/HTML/Pages/Login.php');
            exit();
        }

        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            var_dump($user);
            
            if ($password == $user['password_hash']) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['nombre_usuario'];
                
                header('Location: ../View/HTML/Pages/Profile.php');
            }
        }else{
            // error in session

            // header login.php
            header('Location: ../View/HTML/Pages/Login.php');
        }

        return false;
    }

    public function logout() {}

    public function register() {

    
    }

    public function __destruct()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
