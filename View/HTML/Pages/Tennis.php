<?php
session_start(); 
if (!isset($_SESSION['user_id'])) {     
  header('Location: Login.php');     
  exit();
}

require_once '../../../Controler/EventController.php';
$controller = new EventController();
$eventos = $controller->getEventsByDeporte('Tenis');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/Styles/Tennis.css" />
    <link rel="stylesheet" href="../../CSS/Global/global.css">
    <title>Eventos Tenis</title>
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
 
    <div class="menu_events">
        <div class="titleEventF">
            <h1>Tenis</h1>
    </div>

    <div class="buscador">
        <input type="text" id="inputBuscar" placeholder="Buscar evento...">
        <input type="date" id="inputFecha">
        <select id="selectOrden">
            <option value="default">Ordenar</option>
            <option value="az">A → Z</option>
            <option value="za">Z → A</option>
        </select>
        <span id="contador">4 eventos</span>
    </div>
   
    <?php if (empty($eventos)): ?>
        <p class="no_events" style="grid-column:1/-1">No hay eventos de tenis disponibles.</p>
        <?php else: ?>
            <?php foreach ($eventos as $ev): ?>
                <div class="event_card_sport"
                    data-titulo="<?= htmlspecialchars(strtolower($ev['titulo'])) ?>"
                    data-fecha="<?= htmlspecialchars($ev['fecha']) ?>">
                    <a href="../Pages/EventDetail.php?id=<?= (int)$ev['id_evento'] ?>">
                        <img src="/HTML/Controler/eventImages/<?= htmlspecialchars($ev['foto']) ?>"
                            alt="<?= htmlspecialchars($ev['titulo']) ?>"
                            onerror="this.src='/HTML/Assets/Tenis.jpeg'">
                        <h4><?= htmlspecialchars($ev['titulo']) ?></h4>
                        <p class="event_meta_sport">
                            <?= htmlspecialchars(date('d/m/Y', strtotime($ev['fecha']))) ?>
                            · <?= htmlspecialchars(substr($ev['hora'], 0, 5)) ?>h
                        </p>
                        <p class="event_meta_sport"><?= htmlspecialchars($ev['ubicacion']) ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
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