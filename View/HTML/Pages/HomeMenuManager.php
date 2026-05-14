<?php
session_start(); 
if (!isset($_SESSION['user_id'])) {     
  header('Location: Login.php');     
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/Styles/HomeMenu.css" />
    <link rel="stylesheet" href="../../CSS/Global/global.css">
    <title>Menú principal</title>
</head>
<body>
 
    <div class="header">
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
        <a href="../../HTML/Pages/Profile.php"><input type="button" value="Mi cuenta"></a>
      </div>
    </div>
 
    <div class="lab1">
        <div class="title1">
            <h1>Publica tu evento</h1>
        </div>
        <div class="buttons_lab1">
            <a href="CreateEvent.php"><input type="button" value="Crear evento"></a>
            <a href="MyEvents.php"><input type="button" value="Mis eventos"></a>
 
            <div class="img1">
                <img src="../../Assets/foto_home.jpeg">
            </div>
        </div>
    </div>
 
    <div class="lab2">
        <div class="title2">
            <h3>Accede a todos nuestros deportes</h3>
        </div>
        <div class="buttons_lab2">
            <a href="../../HTML/Pages/ListSport.php"><input type="button" value="Todos los deportes"></a>
        </div>
        <div class="title3">
            <h3>Deportes destacados</h3>
        </div>
 
        <div class="football_label">
            <a href="../../HTML/Pages/Football.php"><h4>Fútbol</h4></a>
            <img src="../../Assets/Futbol.jpeg">          
        </div>
 
        <div class="basketball_label">
            <a href="Basketball.php"><h4>Baloncesto</h4></a>
            <img src="../../Assets/Baloncesto.jpeg">
        </div>
 
    </div>
 
    <footer class="foot">
        <div class="about_us">
            <strong>Nosotros</strong><br>
            <a href="">Contacto</a><br>
            <a href="">Centro de Ayuda</a><br>
            <a href="../../HTML/Pages/AboutUs.php">Sobre nosotros</a>           
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