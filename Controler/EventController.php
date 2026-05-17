<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user = new EventController();

    if (isset($_POST["create_event"])) {
        $user->createEvent();
    }
    if (isset($_POST["update_event"])) {
        $user->updateEvent();
    }
    /*if (isset($_POST["delete_event"])) {
        $user->deleteEvent();
    }*/
}

class EventController {

    private ?PDO $conn;
     public function __construct() {
        $servername = "localhost";
        $username   = "root";
        $password   = "";
        $base_datos = "eventsportsbcn";

        try {
            $this->conn = new PDO(
                "mysql:host=$servername;dbname=$base_datos",
                $username,
                $password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            exit();
        }
    }

    public function createEvent() {

        $nombre      = $_POST["nombre"];
        $deporte     = $_POST["deporte"];
        $descripcion = $_POST["descripcion"];
        $fecha       = $_POST["fecha"];
        $hora        = $_POST["hora"];
        $plazas_totales = $_POST["plazas_totales"];
        $calle       = $_POST["calle"];
        $numero      = $_POST["numero"];
        $cp          = $_POST["cp"];
        $google_maps = $_POST["google_maps"];

        // Campos vacíos
        if (empty($nombre) || empty($deporte) || empty($fecha) || empty($hora) || empty($plazas_totales) || empty($calle) || empty($numero) || empty($cp)) {
            $_SESSION['event_error'] = "Por favor, completa todos los campos.";
            header('Location: ../View/HTML/Pages/CreateEvent.php');
            exit();
        }

        // Subida de foto portada
        $foto = null;
        if (!empty($_FILES['foto']['tmp_name'])) {

        
            $uploadDir = "../View/Assets/eventImages/";
                 
            if (!is_dir($uploadDir)) {         
                mkdir($uploadDir, 0755, true);    
            }

            $filename = $_FILES['foto']['name'];
            move_uploaded_file($_FILES['foto']['tmp_name'], "../View/Assets/eventImages/" . $filename);
            $foto = $filename;
        } else {
            $_SESSION['event_error'] = "La foto de portada es obligatoria.";
            header('Location: ../View/HTML/Pages/CreateEvent.php');
            exit();
        }

        // Guardar en base de datos
        $sql  = "INSERT INTO eventos (nombre, deporte, descripcion, fecha, hora, plazas_totales, calle, numero, cp, google_maps, foto, manager_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$nombre, $deporte, $descripcion, $fecha, $hora, $plazas_totales, $calle, $numero, $cp, $google_maps, $foto, $_SESSION['user_id']]);

        header('Location: ../View/HTML/Pages/HomeMenuManager.php');
        exit();
    }

    public function getMyEvents(int $manager_id) {
    $sql  = "SELECT id AS id_evento, nombre AS titulo, deporte, fecha, hora, 
                    CONCAT(calle, ' ', numero) AS ubicacion,
                    plazas_totales, plazas_totales AS plazas_disponibles, foto
             FROM eventos
             WHERE manager_id = ?
             ORDER BY fecha DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$manager_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEventById(int $id) {
        $sql  = "SELECT * FROM eventos WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getEventsByDeporte(string $deporte) {
    $sql  = "SELECT id AS id_evento, nombre AS titulo, deporte, fecha, hora,
                    CONCAT(calle, ' ', numero) AS ubicacion,
                    plazas_totales, plazas_totales AS plazas_disponibles, foto
             FROM eventos
             WHERE deporte = ?
             ORDER BY fecha DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$deporte]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function updateEvent() {
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'manager') {
        header('Location: ../View/HTML/Pages/Login.php');
        exit();
    }

    $id_evento   = (int)($_POST['id_evento'] ?? 0);
    $nombre      = trim($_POST['nombre']         ?? '');
    $deporte     = trim($_POST['deporte']        ?? '');
    $descripcion = trim($_POST['descripcion']    ?? '');
    $fecha       = trim($_POST['fecha']          ?? '');
    $hora        = trim($_POST['hora']           ?? '');
    $plazas      = (int)($_POST['plazas_totales'] ?? 0);
    $calle       = trim($_POST['calle']          ?? '');
    $numero      = trim($_POST['numero']         ?? '');
    $cp          = trim($_POST['cp']             ?? '');
    $google_maps = trim($_POST['google_maps']    ?? '');

    // Verificar que el evento pertenece al manager
    $stmtCheck = $this->conn->prepare("SELECT id FROM eventos WHERE id = ? AND manager_id = ?");
    $stmtCheck->execute([$id_evento, $_SESSION['user_id']]);
    if (!$stmtCheck->fetch()) {
        $_SESSION['event_error'] = "No tienes permiso para editar este evento.";
        header('Location: ../View/HTML/Pages/MyEvents.php');
        exit();
    }

    // Campos obligatorios
    if (empty($nombre) || empty($deporte) || empty($fecha) || empty($hora) || empty($plazas) || empty($calle) || empty($numero) || empty($cp)) {
        $_SESSION['event_error'] = "Por favor, completa todos los campos obligatorios.";
        header("Location: ../View/HTML/Pages/EditEvent.php?id=$id_evento");
        exit();
    }

    // Subida de nueva foto
    $foto = null;
    if (!empty($_FILES['foto']['tmp_name'])) {
        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $mime    = mime_content_type($_FILES['foto']['tmp_name']);

        if (!in_array($mime, $allowed)) {
            $_SESSION['event_error'] = "La foto debe ser una imagen (JPG, PNG, GIF o WEBP).";
            header("Location: ../View/HTML/Pages/EditEvent.php?id=$id_evento");
            exit();
        }

        $ext      = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $filename = 'event_' . $id_evento . '_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], "../View/Assets/eventImages/" . $filename);
        $foto = $filename;
    }

    // UPDATE con o sin foto
    if ($foto) {
        $sql  = "UPDATE eventos SET nombre=?, deporte=?, descripcion=?, fecha=?, hora=?, plazas_totales=?, calle=?, numero=?, cp=?, google_maps=?, foto=? WHERE id=? AND manager_id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$nombre, $deporte, $descripcion, $fecha, $hora, $plazas, $calle, $numero, $cp, $google_maps, $foto, $id_evento, $_SESSION['user_id']]);
    } else {
        $sql  = "UPDATE eventos SET nombre=?, deporte=?, descripcion=?, fecha=?, hora=?, plazas_totales=?, calle=?, numero=?, cp=?, google_maps=? WHERE id=? AND manager_id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$nombre, $deporte, $descripcion, $fecha, $hora, $plazas, $calle, $numero, $cp, $google_maps, $id_evento, $_SESSION['user_id']]);
    }

    $_SESSION['event_success'] = "Evento actualizado correctamente.";
    header('Location: ../View/HTML/Pages/MyEvents.php');
    exit();
        
    }

    /*public function deleteEvent() {
        
    }*/
}