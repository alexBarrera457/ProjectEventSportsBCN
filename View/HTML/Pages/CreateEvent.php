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
    <link rel="stylesheet" href="../../CSS/Styles/CreateEventAndEdit.css" />
    <link rel="stylesheet" href="../../CSS/Global/global.css" />
    <link rel="stylesheet" href="../../CSS/Styles/HeaderFooter.css" />
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
          <h1>Editor de eventos</h1>
        </div>
 
        <form method="POST" action="../../../Controler/EventController.php" enctype="multipart/form-data">

        <div class="event_details">
          <label>Título del evento</label>
          <input type="text" name="nombre" required />
          <br />

          <label>Deporte</label>
          <select name="deporte" required>
            <option value="" disabled selected>Selecciona un deporte</option>
            <option value="Fútbol">Fútbol</option>
            <option value="Baloncesto">Baloncesto</option>
            <option value="Tenis">Tenis</option>
            <option value="Pádel">Pádel</option>
            <option value="Golf">Golf</option>
          </select>
          <br />

          <label>Fecha del evento</label>
          <input type="date" name="fecha" required />
          <br />

          <label>Hora del evento</label>
          <input type="time" name="hora" required />
          <br />

          <label>Num. participantes</label>
          <input type="number" name="plazas_totales" min="1" required />
          <br />

          <label>Calle</label>
          <input type="text" name="calle" required />
          <br />

          <label>Número</label>
          <input type="text" name="numero" required />
          <br />

          <label>Código postal</label>
          <input type="text" name="cp" maxlength="5" required />
          <br />

          <label>Enlace Google Maps</label>
          <input type="url" name="google_maps" />
          <br />

          <label>Foto de portada</label>
          <input type="file" name="foto" accept="image/*" required />
          <br />
        </div>

        <div class="event_description">
          <label>Descripción</label>
          <input type="text" name="descripcion" />
        </div>

        <div class="publish_button">
          <input type="submit" name="create_event" value="Publicar" />
        </div>

      </form>
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
