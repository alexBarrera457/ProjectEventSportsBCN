<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: Login.php');
    exit();
}

require_once '../../../Controler/EventController.php';
$controller = new EventController();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$ev = $controller->getEventById($id);

if (!$ev) {
    echo "Evento no encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/Styles/SportsPagesGlobal.css" />
    <link rel="stylesheet" href="../../CSS/Global/global.css" />
    <link rel="stylesheet" href="../../CSS/Styles/HeaderFooter.css" />
    <title><?= htmlspecialchars($ev['nombre']) ?></title>
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
                    <form method="POST" action="../../../Controler/userControler.php">
                        <button type="submit" name="logout">Cerrar sesión</button>
                    </form>
                <?php endif; ?>
                <a href="../Pages/Profile.php"><input type="button" value="Mi cuenta"></a>
            </div>
        </div>
<div class="fondo">
<div class="event_detail_wrapper">
    <a href="javascript:history.back()" class="btn_volver">← Volver</a>

    <div class="event_detail_card">
        <img src="/HTML/Controler/eventImages/<?= htmlspecialchars($ev['foto']) ?>"
             alt="<?= htmlspecialchars($ev['nombre']) ?>"
             onerror="this.src='/HTML/Assets/SportsIcon.png'">

        <div class="event_detail_info">
            <h1><?= htmlspecialchars($ev['nombre']) ?></h1>

            <p><strong>Deporte:</strong> <?= htmlspecialchars($ev['deporte']) ?></p>
            <p><strong>Fecha:</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($ev['fecha']))) ?></p>
            <p><strong>Hora:</strong> <?= htmlspecialchars(substr($ev['hora'], 0, 5)) ?>h</p>
            <p><strong>Ubicación:</strong>
                <?= htmlspecialchars($ev['calle']) ?> <?= htmlspecialchars($ev['numero']) ?>,
                CP <?= htmlspecialchars($ev['cp']) ?>
            </p>
            <p><strong>Plazas disponibles:</strong> <?= (int)$ev['plazas_totales'] ?></p>

            <?php if (!empty($ev['descripcion'])): ?>
                <p><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($ev['descripcion'])) ?></p>
            <?php endif; ?>

            <?php if (!empty($ev['google_maps'])): ?>
                <p>
                    <strong>Google Maps:</strong>
                    <a href="<?= htmlspecialchars($ev['google_maps']) ?>" target="_blank">Ver en el mapa</a>
                </p>
            <?php endif; ?>
            <div class="event_actions">
                <button type="button" class="btn_action btn_saved">☆ Guardar evento</button>
                <button type="button" class="btn_action btn_register">+ Inscribirme</button>
            </div>
        </div>
    </div>
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
            Facebook <br>
            Instagram<br>
            X<br>
        </div>
    </footer>
</body>
</html>