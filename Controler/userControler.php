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
    private ?PDO $conn;

    public function __construct()
    {
        $host      = "localhost";
        $usuario   = "root";
        $password  = "";
        $base_datos = "eventsportsbcn";

        try {
            $this->conn = new PDO("mysql:host=$host;dbname=$base_datos;charset=utf8mb4", $usuario, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
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

    $sql  = "SELECT id, nombre_usuario, password_hash, rol FROM usuarios WHERE nombre_usuario = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$user]);
    $userData = $stmt->fetch();

    if ($userData) {
        if ($password === $userData['password_hash']) {
            session_regenerate_id(true);
            $_SESSION['user_id']  = $userData['id'];
            $_SESSION['username'] = $userData['nombre_usuario'];
            $_SESSION['rol']      = $userData['rol'];
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

    public function logout() {

        session_unset();  
        session_destroy();  
        header('Location: ../View/HTML/Pages/Login.php');
        exit();
        
    }

    public function register() {

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

        // Campos vacíos
        if (empty($name) || empty($surname) || empty($user) || empty($email) || empty($passwd) || empty($repasswd)) {
            $_SESSION['register_error'] = "Por favor, completa todos los campos.";
            
            if ($rol === 'manager') {
                header('Location: ../View/HTML/Pages/SingUpManager.php');
            } else {
                header('Location: ../View/HTML/Pages/SingUp.php');
            }
            exit();
        }

        //Contraseñas no coinciden
        if ($passwd !== $repasswd) {
            $_SESSION['register_error'] = "Las contraseñas no coinciden.";

            if ($rol === 'manager') {
                header('Location: ../View/HTML/Pages/SingUpManager.php');
            } else {
                header('Location: ../View/HTML/Pages/SingUp.php');
            }
            exit();
        }

        // Subida de imagen solo manager
        $foto_perfil = null;
        if ($rol === 'manager' && !empty($_FILES['foto_perfil']['tmp_name'])) {
            $filename = $_FILES['foto_perfil']['name'];
            move_uploaded_file($_FILES['foto_perfil']['tmp_name'], "profileImages/" . $filename);
            $foto_perfil = $filename;
        }

        // Guardar en base de datos
        $sql  = "INSERT INTO usuarios (nombre, apellidos, nombre_usuario, email, password_hash, rol, entidad, telefono, foto_perfil) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$name, $surname, $user, $email, $passwd, $rol, $entidad, $telefono, $foto_perfil]);


        $_SESSION['user_id']  = $this->conn->lastInsertId();
        $_SESSION['username'] = $user;
        $_SESSION['rol']      = $rol;

        if ($rol === 'manager') {
            header('Location: ../View/HTML/Pages/HomeMenuManager.php');
        } else {
            header('Location: ../View/HTML/Pages/HomeMenu.php');
        }
        exit();
        }
    

    public function __destruct()
    {
        $this->conn = null;
    }

}