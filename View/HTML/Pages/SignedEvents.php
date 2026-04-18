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
    <link rel="stylesheet" href="../../CSS/Styles/SignedEvents.css" />
    <link rel="stylesheet" href="../../CSS/Global/global.css">
    <title>Eventos Apuntados</title>
</head>
<body>
    <header>
        <div class="header_web">
            <div class="logo">

            <?php if ($_SESSION['rol'] === 'manager'): ?>
                <a href="HomeMenuManager.php"><img src="../../Assets/Logo1.png" alt="Logo"/></a>
            <?php else: ?>
                <a href="HomeMenu.php"><img src="../../Assets/Logo1.png" alt="Logo"/></a>
            <?php endif; ?>
            
            </div>
            <div class="profile_but">

                <?php if (isset($_SESSION['user_id'])): ?>
                    <form method="POST" action="../../../Controler/userControler.php">
                        <button type="submit" name="logout">Cerrar sesión</button>
                    </form>
                <?php endif; ?>
                
                <a href="../../HTML/Pages/Profile.php"><input type="button" value="Mi cuenta"></a>
            </div>
        </div>        
    </header>

    <div class="menu_events">
        <div class="titleSignedEvents">
            <h1>Eventos Aputados</h1>
        </div>
   
        <div class="event1">
            <img src="../../Assets/SportsIcon.png">
            <h4>Evento 1</h4></a>
        </div>
   
        <div class="event2">
            <img src="../../Assets/SportsIcon.png">
            <h4>Evento 2</h4></a>
        </div>
   
        <div class="event3">
            <img src="../../Assets/SportsIcon.png">
            <h4>Evento 3</h4></a>
        </div>
   
        <div class="event4">
            <img src="../../Assets/SportsIcon.png">
            <h4>Evento 4</h4></a>          
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