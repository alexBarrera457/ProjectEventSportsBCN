<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: Login.php');
    exit();
}
if ($_SESSION['rol'] !== 'manager') {
    header('Location: HomeMenu.php');
    exit();
}

require_once '../../../Controler/EventController.php';
$controller = new EventController();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$ev = $controller->getEventById($id);

if (!$ev || (int)$ev['manager_id'] !== (int)$_SESSION['user_id']) {
    header('Location: MyEvents.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/Styles/CreateEventAndEdit.css">
    <link rel="stylesheet" href="../../CSS/Global/global.css">
    <link rel="stylesheet" href="../../CSS/Styles/HeaderFooter.css">
    <title>Editar evento</title>
</head>
<body>

<div class="header_web">
    <div class="logo_web">
        <?php if ($_SESSION['rol'] === 'manager'): ?>
            <a href="HomeMenuManager.php"><img src="../../Assets/Logo1.png" alt="Logo"/></a>
        <?php else: ?>
            <a href="HomeMenu.php"><img src="../../Assets/Logo1.png" alt="Logo"/></a>
        <?php endif; ?>
    </div>

    <div class="nav_header">
        <a href="../Pages/SignedEvents.php"><button type="button">Eventos apuntados</button></a>
        <a href="../Pages/SavedEvents.php"><button type="button">Eventos guardados</button></a>
        <a href="../Pages/FollowedSports.php"><button type="button">Deportes seguidos</button></a>
        <?php if ($_SESSION['rol'] === 'manager'): ?>
            <a href="../Pages/MyEvents.php"><button type="button">Mis eventos</button></a>
        <?php endif; ?>
    </div>

    <div class="boton_header">
        <?php if (isset($_SESSION['user_id'])): ?>
            <form method="POST" action="../../../Controler/UserController.php">
                <button type="submit" name="logout">Cerrar sesión</button>
            </form>
        <?php endif; ?>
        <a href="../Pages/Profile.php"><input type="button" value="Mi cuenta"></a>
    </div>
</div>

<div class="fondo">
    <div class="recuadro">
        <div class="event_edit_title">
            <h1>Editar evento</h1>
        </div>

        <?php if (!empty($_SESSION['event_error'])): ?>
            <p class="msg_error"><?= htmlspecialchars($_SESSION['event_error']) ?></p>
            <?php unset($_SESSION['event_error']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['event_success'])): ?>
            <p class="msg_success"><?= htmlspecialchars($_SESSION['event_success']) ?></p>
            <?php unset($_SESSION['event_success']); ?>
        <?php endif; ?>

        <form method="POST" action="../../../Controler/EventController.php" enctype="multipart/form-data">
            <input type="hidden" name="id_evento" value="<?= (int)$ev['id'] ?>">

            <div class="event_details">

                <label>Título del evento</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($ev['nombre']) ?>" required>
                <br>

                <label>Deporte</label>
                <select name="deporte" required>
                    <option value="" disabled>Selecciona un deporte</option>
                    <?php
                    $deportes = ['Fútbol', 'Baloncesto', 'Tenis', 'Pádel', 'Golf'];
                    foreach ($deportes as $d):
                    ?>
                        <option value="<?= $d ?>" <?= $ev['deporte'] === $d ? 'selected' : '' ?>>
                            <?= $d ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <br>

                <label>Fecha del evento</label>
                <input type="date" name="fecha" value="<?= htmlspecialchars($ev['fecha']) ?>" required>
                <br>

                <label>Hora del evento</label>
                <input type="time" name="hora" value="<?= htmlspecialchars(substr($ev['hora'], 0, 5)) ?>" required>
                <br>

                <label>Num. participantes</label>
                <input type="number" name="plazas_totales" min="1" value="<?= (int)$ev['plazas_totales'] ?>" required>
                <br>

                <label>Calle</label>
                <input type="text" name="calle" value="<?= htmlspecialchars($ev['calle']) ?>" required>
                <br>

                <label>Número</label>
                <input type="text" name="numero" value="<?= htmlspecialchars($ev['numero']) ?>" required>
                <br>

                <label>Código postal</label>
                <input type="text" name="cp" maxlength="5" value="<?= htmlspecialchars($ev['cp']) ?>" required>
                <br>

                <label>Enlace Google Maps</label>
                <input type="url" name="google_maps" value="<?= htmlspecialchars($ev['google_maps'] ?? '') ?>">
                <br>

                <label>Foto de portada</label>
                <div class="foto_actual">
                    <img id="preview_foto"
                         src="/HTML/View/Assets/eventImages/<?= htmlspecialchars($ev['foto']) ?>"
                         alt="Foto actual"
                         onerror="this.src='/HTML/View/Assets/SportsIcon.png'">
                    <small>Foto actual. Sube una nueva para reemplazarla.</small>
                </div>
                <input type="file" name="foto" id="foto" accept="image/*">
                <br>

            </div>

            <div class="event_description">
                <label>Descripción</label>
                <input type="text" name="descripcion" value="<?= htmlspecialchars($ev['descripcion'] ?? '') ?>">
            </div>

            <div class="publish_button">
                <a href="MyEvents.php"><button type="button" class="btn_cancelar_edit">Cancelar</button></a>
                <input type="submit" name="update_event" value="Guardar cambios">
            </div>

        </form>
    </div>
</div>

<footer class="foot">
    <div class="about_us">
        <strong>Nosotros</strong><br>
        <a href="">Contacto</a><br>
        <a href="">Centro de Ayuda</a><br>
        <a href="../Pages/AboutUs.php">Sobre nosotros</a>
    </div>
    <div class="legal">
        <strong>Legal</strong><br>
        <a href="">Aviso legal</a><br>
        <a href="">Política de privacidad</a><br>
        <a href="">Términos y condiciones</a>
    </div>
    <div class="follow">
        <strong>Síguenos</strong><br>
        Facebook<br>Instagram<br>X<br>
    </div>
</footer>

<script>
document.getElementById('foto').addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
        document.getElementById('preview_foto').src = URL.createObjectURL(file);
    }
});
</script>

</body>
</html>