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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/Styles/CreateEvent.css">
    <link rel="stylesheet" href="../../CSS/Global/global.css">
    <title>Crear evento</title>
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

    <div class="menu_sport_events">
        <div class="titleCreateEvent">
            <h1>Selecciona que deporte del evento quieres crear</h1>
        </div>
   
        <div class="football1">
            <a href="EditEvent.php">
                <img src="../../Assets/SportsIcon.png">
                <h4>Fútbol</h4>
            </a>           
        </div>
   
        <div class="basketball2">           
            <a href="EditEvent.php">
                <img src="../../Assets/SportsIcon.png">
                <h4>Baloncesto</h4>
            </a>            
        </div>
   
        <div class="tennis3">
            <a href="EditEvent.php">
                <img src="../../Assets/SportsIcon.png">
                <h4>Tenis</h4>
            </a>           
        </div>
   
        <div class="paddle4">
            <a href="EditEvent.php">
                <img src="../../Assets/SportsIcon.png">
                <h4>Paddle</h4> 
            </a>                    
        </div>
        
        <div class="golf4">
            <a href="EditEvent.php">
                <img src="../../Assets/SportsIcon.png">
                <h4>Golf</h4> 
            </a>                    
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
            Facebook<br>
            Instagram<br>
            X<br>
        </div>
    </footer>
</body>
</html>