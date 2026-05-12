<?php
session_start(); 
if (!isset($_SESSION['user_id'])) {     
  header('Location: Login.php');     
  exit();
}
?>

<?php
if ($_SESSION['rol'] !== 'manager') {
    header('Location: HomeMenu.php');
    exit();
}
?> 

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../../CSS/Styles/EditEvent.css" />
    <link rel="stylesheet" href="../../CSS/Global/global.css" />
    <title>Publicar evento</title>
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
                    <form method="POST" action="../../../Controler/userControler.php">
                        <button type="submit" name="logout">Cerrar sesión</button>
                    </form>
                <?php endif; ?>
                <a href="../Pages/Profile.php"><input type="button" value="Mi cuenta"></a>
            </div>
        </div>        

    <div class="fondo">
    <div class="recuadro">
    <div class="event_edit_title">
      <h1>Editor de eventos</h1>
    </div>

    <div class="event_details">
      <label for="title_event">Título del evento</label>
      <input type="text" name="Titulo" />
      <br />
      <label for="datetime_event">Fecha y hora del evento</label>
      <input type="datetime" name="FechaHora" />
      <br />
      <label for="num_part_event">Num. participantes del evento</label>
      <input type="number" name="Participantes" />
      <br />
      <label for="location_event">Ubicacion</label>
      <input type="text" name="Ubicacion" />
      <br />
    </div>

    <div class="event_description">
      <label for="description">Descripción</label>
      <input type="text" name="Descripcion" />
    </div>

    <div class="publish_button">
      <input type="submit" value="Publicar" />
    </div>
    </div>
    </div>

    <footer class="foot">
      <div class="about_us">
        <strong>Nosotros</strong><br />
        <a href="">Contacto</a><br />
        <a href="">Centro de Ayuda</a><br />
        <a href="../Pages/AboutUs.php">Sobre nosotros</a>
      </div>

      <div class="legal">
        <strong>Legal</strong><br />
        <a href="">Aviso legal</a><br />
        <a href="">Política de privacidad</a><br />
        <a href="">Términos y condiciones</a>
      </div>

      <div class="follow">
        <strong>Síguenos</strong><br />
        Facebook <br />
        Instagram<br />
        X<br />
      </div>
    </footer>
  </body>
</html>
