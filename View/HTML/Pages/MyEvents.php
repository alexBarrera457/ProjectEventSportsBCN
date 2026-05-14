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
?>

<?php
require_once '../../../Controler/EventController.php';
$controller = new EventController();
$eventos    = $controller->getMyEvents($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/Styles/MyEvents.css">
    <link rel="stylesheet" href="../../CSS/Global/global.css">
    <title>Mis eventos</title>
</head>
<body>

<div class="header_web">
    <div class="logo_web">
        <?php if ($_SESSION['rol'] === 'manager'): ?>
            <a href="HomeMenuManager.php"><img src="../../Assets/Logo1.png" alt="Logo"></a>
        <?php else: ?>
            <a href="HomeMenu.php"><img src="../../Assets/Logo1.png" alt="Logo"></a>
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

<div class="menu_sport_events">
    <div class="titleMyEvents">
        <h1>Eventos creados</h1>
    </div>

    <div class="buscador">
        <input type="text"  id="inputBuscar" placeholder="Buscar evento...">
        <input type="date"  id="inputFecha">
        <select id="selectOrden">
            <option value="default">Ordenar</option>
            <option value="az">A → Z</option>
            <option value="za">Z → A</option>
        </select>
        <span id="contador"></span>
    </div>

    <div class="crear_evento_btn">
        <a href="../Pages/CreateEvent.php">
            <button type="button" class="btn_crear">+ Crear nuevo evento</button>
        </a>
    </div>

    <div class="events_grid" id="eventsGrid">
        <?php if (empty($eventos)): ?>
            <p class="no_events">No tienes eventos creados todavía.</p>
        <?php else: ?>
            <?php foreach ($eventos as $ev): ?>
                <div class="event_card"
                    data-titulo="<?= htmlspecialchars(strtolower($ev['titulo'])) ?>"
                    data-fecha="<?= htmlspecialchars($ev['fecha']) ?>">

                    <a href="../Pages/EventDetail.php?id=<?= (int)$ev['id_evento'] ?>">
                    <img src="/HTML/Controler/eventImages/<?= htmlspecialchars($ev['foto']) ?>"
                    alt="<?= htmlspecialchars($ev['titulo']) ?>"
                    onerror="this.src='/HTML/Assets/SportsIcon.png'">

                <div class="event_card_info">
                    <h4><?= htmlspecialchars($ev['titulo']) ?></h4>
                    <p class="event_meta">
                        <span><?= htmlspecialchars($ev['deporte']) ?></span>
                            &nbsp;·&nbsp;
                        <span><?= htmlspecialchars(date('d/m/Y', strtotime($ev['fecha']))) ?></span>
                            &nbsp;·&nbsp;
                        <span><?= htmlspecialchars(substr($ev['hora'], 0, 5)) ?>h</span>
                </p>
                    <p class="event_ubicacion"><?= htmlspecialchars($ev['ubicacion']) ?></p>
                    <p class="event_plazas">
                        Plazas: <?= (int)$ev['plazas_disponibles'] ?> / <?= (int)$ev['plazas_totales'] ?>
                </p>
                </div>
                </a>

                    <form method="POST" action="../../../Controler/EventController.php"
                          onsubmit="return confirm('¿Eliminar este evento?')">
                        <input type="hidden" name="id_evento" value="<?= (int)$ev['id_evento'] ?>">
                        <button type="submit" name="delete_event" class="btn_eliminar">Eliminar</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
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
</body>
</html>