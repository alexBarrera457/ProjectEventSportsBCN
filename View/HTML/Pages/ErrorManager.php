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
    <link rel="stylesheet" href="../../CSS/Styles/ErrorManager.css">
    <title>Error Manager</title>
</head>
<body>
    <header>
      <div class="header_web">
        <div class="logo">
          <a href="../Pages/HomeMenu.php"><img src="../../Assets/Logo1.png" /></a>
        </div>
      </div>
    </header>

    <div class="fondo">
    <div class="recuadro">
      <div class="title1">
        <h1>No eres manager</h1>
      </div>
      <div class="text">
        Lo sentimos, pero no tienes los permisos necesarios para acceder a esta página. 
        
        Vuelve a la página de inicio y asegúrate de iniciar sesión con una cuenta de manager para acceder a esta sección.
    </div>
    </div>
    </div>
</body>
</html>