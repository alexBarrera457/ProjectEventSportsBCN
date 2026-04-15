<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user = new UserController();

    if (isset($_POST["login"])) {
        $user->login();
    }
    if (isset($_POST["logout"])) {
        $user->logout();
    }
    if (isset($_POST["register"])) {
        $user->register();
    }
}

class UserController
{
    private $conn;

    public function __construct()
    {
        $host      = "localhost";
        $usuario   = "root";
        $password  = "";
        $base_datos = "eventsportsbcn";

        $this->conn = new mysqli($host, $usuario, $password, $base_datos);

        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }

        $this->conn->set_charset("utf8mb4");
    }

    public function login()
    {
        $user     = $_POST["user"];
        $password = $_POST["password"];

        // Campos vacíos
        if (empty($user) || empty($password)) {
            $_SESSION['login_error'] = "Por favor, completa todos los campos.";
            header('Location: ../View/HTML/Pages/Login.php');
            exit();
        }

        $sql  = "SELECT id, nombre_usuario, password_hash FROM usuarios WHERE nombre_usuario = ?";
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
            $userData = $result->fetch_assoc();

            if ($password === $userData['password_hash']) {
                session_regenerate_id(true);
                $_SESSION['user_id']  = $userData['id'];
                $_SESSION['username'] = $userData['nombre_usuario'];
                header('Location: ../View/HTML/Pages/Profile.php');
                exit();
            } else {
                $_SESSION['login_error'] = "Contraseña incorrecta.";
                header('Location: ../View/HTML/Pages/Login.php');
                exit();
            }

        } else {
            $_SESSION['login_error'] = "El usuario no existe.";
            header('Location: ../View/HTML/Pages/Login.php');
            exit();
        }
    }

    public function logout() {}

    public function register() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $name = $_POST["Name"];
        $surname = $_POST["Surname"];
        $user = $_POST["User"];
        $email = $_POST["Email"];
        $passwd = $_POST["Passwd"];
        $repasswd = $_POST["Repasswd"];
        $rol = $_POST["rol"];

        //campos extra manager
        $entidad = $_POST["Entidad"] ?? null;
        $telefono = $_POST["Telefono"] ?? null;

        //Contraseñas no coinciden
        if ($passwd !== $repasswd) {
            $_SESSION['register_error'] = "Las contraseñas no coinciden.";
            header('Location: ../View/HTML/Pages/SingUp.php');
            exit();
        }

        // Guardar en la base de datos con todos los campos
        $sql  = "INSERT INTO usuarios (nombre, apellidos, nombre_usuario, email, password_hash, rol, entidad, telefono) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssssss", $name, $surname, $user, $email, $passwd, $rol, $entidad, $telefono);

        if ($stmt->execute()) {
            header('Location: ../View/HTML/Pages/Profile.php');
            exit();
        } else {
            $_SESSION['register_error'] = "Error al registrar. Inténtalo de nuevo.";
            header('Location: ../View/HTML/Pages/SignUp.php');
            exit();
        }
    
    }

    public function __destruct()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}