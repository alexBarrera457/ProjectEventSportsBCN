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
    if (isset($_POST["update_user"])) {
        $user->updateUser();
    }
    if (isset($_POST["update_password"])) {
        $user->updatePassword();
    }
    if (isset($_POST["update_manager"])) {
        $user->updateManager();
    }
    if (isset($_POST["delete_user"])) {
        $user->deleteUser();
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

        if (empty($user) || empty($password)) {
            $_SESSION['login_error'] = "Por favor, completa todos los campos.";
            header('Location: ../View/HTML/Pages/Login.php');
            exit();
        }

        $sql  = "SELECT id, nombre_usuario, password_hash, rol FROM usuarios WHERE nombre_usuario = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData) {
            if (password_verify($password, $userData['password_hash'])) {
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


    public function logout()
    {

        session_unset();
        session_destroy();
        header('Location: ../View/HTML/Pages/Login.php');
        exit();
    }

    public function register()
    {

        $name     = $_POST["Name"];
        $surname  = $_POST["Surname"];
        $user     = $_POST["User"];
        $email    = $_POST["Email"];
        $passwd   = $_POST["Passwd"];
        $repasswd = $_POST["Repasswd"];
        $rol      = $_POST["rol"];

        $entidad  = $_POST["Entidad"] ?? null;
        $telefono = $_POST["Telefono"] ?? null;

        if (empty($name) || empty($surname) || empty($user) || empty($email) || empty($passwd) || empty($repasswd)) {
            $_SESSION['register_error'] = "Por favor, completa todos los campos.";
            header('Location: ' . ($rol === 'manager' ? '../View/HTML/Pages/SingUpManager.php' : '../View/HTML/Pages/SingUp.php'));
            exit();
        }

        if ($passwd !== $repasswd) {
            $_SESSION['register_error'] = "Las contraseñas no coinciden.";
            header('Location: ' . ($rol === 'manager' ? '../View/HTML/Pages/SingUpManager.php' : '../View/HTML/Pages/SingUp.php'));
            exit();
        }

        $foto_perfil = null;
        if ($rol === 'manager' && !empty($_FILES['foto_perfil']['tmp_name'])) {
            $filename = $_FILES['foto_perfil']['name'];
            move_uploaded_file($_FILES['foto_perfil']['tmp_name'], "profileImages/" . $filename);
            $foto_perfil = $filename;
        }

        $hash = password_hash($passwd, PASSWORD_DEFAULT);

        $sql  = "INSERT INTO usuarios (nombre, apellidos, nombre_usuario, email, password_hash, rol, entidad, telefono, foto_perfil)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        if ($stmt->execute([$name, $surname, $user, $email, $hash, $rol, $entidad, $telefono, $foto_perfil])) {
            $_SESSION['user_id']  = $this->conn->lastInsertId();
            $_SESSION['username'] = $user;
            $_SESSION['rol']      = $rol;
            header('Location: ' . ($rol === 'manager' ? '../View/HTML/Pages/HomeMenuManager.php' : '../View/HTML/Pages/HomeMenu.php'));
            exit();
        } else {
            $_SESSION['register_error'] = "Error al registrar.";
            header('Location: ' . ($rol === 'manager' ? '../View/HTML/Pages/SingUpManager.php' : '../View/HTML/Pages/SingUp.php'));
            exit();
        }
    }


    public function updateUser()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../View/HTML/Pages/Login.php');
            exit();
        }

        $nombre         = trim($_POST["nombre"]         ?? '');
        $apellidos      = trim($_POST["apellidos"]      ?? '');
        $nombre_usuario = trim($_POST["nombre_usuario"] ?? '');
        $email          = trim($_POST["email"]          ?? '');

        if (empty($nombre) || empty($apellidos) || empty($nombre_usuario) || empty($email)) {
            $_SESSION['profile_error'] = "Por favor, completa todos los campos.";
            header('Location: ../View/HTML/Pages/Profile.php');
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['profile_error'] = "El formato del email no es válido.";
            header('Location: ../View/HTML/Pages/Profile.php');
            exit();
        }

        $stmtCheck = $this->conn->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $stmtCheck->execute([$email, $_SESSION['user_id']]);
        if ($stmtCheck->fetch()) {
            $_SESSION['profile_error'] = "Ese email ya está en uso por otra cuenta.";
            header('Location: ../View/HTML/Pages/Profile.php');
            exit();
        }

        $stmtCheck2 = $this->conn->prepare("SELECT id FROM usuarios WHERE nombre_usuario = ? AND id != ?");
        $stmtCheck2->execute([$nombre_usuario, $_SESSION['user_id']]);
        if ($stmtCheck2->fetch()) {
            $_SESSION['profile_error'] = "Ese nombre de usuario ya está en uso.";
            header('Location: ../View/HTML/Pages/Profile.php');
            exit();
        }

        $sql  = "UPDATE usuarios SET nombre = ?, apellidos = ?, nombre_usuario = ?, email = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$nombre, $apellidos, $nombre_usuario, $email, $_SESSION['user_id']]);

        $_SESSION['username'] = $nombre_usuario;

        $_SESSION['profile_success'] = "Datos actualizados correctamente.";
        header('Location: ../View/HTML/Pages/Profile.php');
        exit();
    }

    public function updatePassword()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../View/HTML/Pages/Login.php');
            exit();
        }

        $current  = $_POST["current_password"]  ?? '';
        $new      = $_POST["new_password"]       ?? '';
        $confirm  = $_POST["confirm_password"]   ?? '';

        if (empty($current) || empty($new) || empty($confirm)) {
            $_SESSION['password_error'] = "Por favor, completa todos los campos.";
            header('Location: ../View/HTML/Pages/Profile.php');
            exit();
        }

        if ($new !== $confirm) {
            $_SESSION['password_error'] = "Las contraseñas nuevas no coinciden.";
            header('Location: ../View/HTML/Pages/Profile.php');
            exit();
        }

        $stmt = $this->conn->prepare("SELECT password_hash FROM usuarios WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $userData = $stmt->fetch();

        if (!$userData || !password_verify($current, $userData['password_hash'])) {
            $_SESSION['password_error'] = "La contraseña actual es incorrecta.";
            header('Location: ../View/HTML/Pages/Profile.php');
            exit();
        }

        $sqlUpdate = "UPDATE usuarios SET password_hash = ? WHERE id = ?";
        $stmtUp    = $this->conn->prepare($sqlUpdate);
        $stmtUp->execute([password_hash($new, PASSWORD_DEFAULT), $_SESSION['user_id']]);

        $_SESSION['password_success'] = "Contraseña actualizada correctamente.";
        header('Location: ../View/HTML/Pages/Profile.php');
        exit();
    }

    public function updateManager()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'manager') {
            header('Location: ../View/HTML/Pages/Login.php');
            exit();
        }

        $nombre         = trim($_POST["nombre"]         ?? '');
        $apellidos      = trim($_POST["apellidos"]      ?? '');
        $nombre_usuario = trim($_POST["nombre_usuario"] ?? '');
        $email          = trim($_POST["email"]          ?? '');
        $entidad        = trim($_POST["entidad"]        ?? '');
        $telefono       = trim($_POST["telefono"]       ?? '');

        if (empty($nombre) || empty($apellidos) || empty($nombre_usuario) || empty($email)) {
            $_SESSION['profile_error'] = "Por favor, completa los campos obligatorios.";
            header('Location: ../View/HTML/Pages/Profile.php');
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['profile_error'] = "El formato del email no es válido.";
            header('Location: ../View/HTML/Pages/Profile.php');
            exit();
        }

        $stmtCheck = $this->conn->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $stmtCheck->execute([$email, $_SESSION['user_id']]);
        if ($stmtCheck->fetch()) {
            $_SESSION['profile_error'] = "Ese email ya está en uso por otra cuenta.";
            header('Location: ../View/HTML/Pages/Profile.php');
            exit();
        }

        $stmtCheck2 = $this->conn->prepare("SELECT id FROM usuarios WHERE nombre_usuario = ? AND id != ?");
        $stmtCheck2->execute([$nombre_usuario, $_SESSION['user_id']]);
        if ($stmtCheck2->fetch()) {
            $_SESSION['profile_error'] = "Ese nombre de usuario ya está en uso.";
            header('Location: ../View/HTML/Pages/Profile.php');
            exit();
        }

        $foto_perfil = null;
        if (!empty($_FILES['foto_perfil']['tmp_name'])) {
            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $mime    = mime_content_type($_FILES['foto_perfil']['tmp_name']);

            if (!in_array($mime, $allowed)) {
                $_SESSION['profile_error'] = "La foto debe ser una imagen (JPG, PNG, GIF o WEBP).";
                header('Location: ../View/HTML/Pages/Profile.php');
                exit();
            }

            $ext      = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
            $filename = 'profile_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['foto_perfil']['tmp_name'], "../../../Controler/profileImages/" . $filename);
            $foto_perfil = $filename;
        }

        if ($foto_perfil) {
            $sql  = "UPDATE usuarios SET nombre = ?, apellidos = ?, nombre_usuario = ?, email = ?, entidad = ?, telefono = ?, foto_perfil = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$nombre, $apellidos, $nombre_usuario, $email, $entidad, $telefono, $foto_perfil, $_SESSION['user_id']]);
        } else {
            $sql  = "UPDATE usuarios SET nombre = ?, apellidos = ?, nombre_usuario = ?, email = ?, entidad = ?, telefono = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$nombre, $apellidos, $nombre_usuario, $email, $entidad, $telefono, $_SESSION['user_id']]);
        }

        $_SESSION['username'] = $nombre_usuario;

        $_SESSION['profile_success'] = "Datos actualizados correctamente.";
        header('Location: ../View/HTML/Pages/Profile.php');
        exit();
    }

    public function deleteUser() {
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../View/HTML/Pages/Login.php');
            exit();
        }

        $password = $_POST['password'];

        if(empty($password)) {
            $_SESSION['profile_error'] = "Debes introducir una contraseña.";
            header('Location: ../View/HTML/Pages/Profile.php');
            exit();
        }

        $stmt = $this->conn->prepare("SELECT password_hash FROM usuarios WHERE ID = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $userData = $stmt->fetch();

        if (!$userData || $password !== $userData['password_hash']) {
            $_SESSION['profile_error'] = "Contraseña incorrecta.";
            header('Location: ../View/HTML/Pages/Profile.php');
            exit();
        }

        $stmt = $this->conn->prepare("DELETE FROM usuarios WHERE ID = ?");
        $stmt->execute([$_SESSION['user_id']]);

        session_unset();  
        session_destroy();  
        header('Location: ../View/HTML/Pages/Login.php');
        exit();

    }

    public function __destruct()
    {
        $this->conn = null;
    }
}
