<?php
session_start(); 
if (!isset($_SESSION['user_id'])) {     
  header('Location: Login.php');     
  exit();
}
?>

<?php
session_start();

$error = $_SESSION['register_error'] ?? '';
unset($_SESSION['register_error']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/Styles/SingUp.css" />
    <link rel="stylesheet" href="../../CSS/Global/global.css">
    <title>Registro Manager</title>
</head>
<body>
 
    <header>
        <div class="header_web">
            <div class="logo">
                <img src="../../Assets/Logo1.png">
            </div>
            <div class="login_but">
                <a href="../../HTML/Pages/Login.php"><input type="button" value="Iniciar sesión"></a>
            </div>
        </div>        
    </header>
    
    <div class = "fondo">
        <div class="recuadro_registro">
        <div class="titulo_registro">
            <h1>Registrarse como manager</h1>
        </div>
        <div class="botones_form">
            <form action="../../../Controler/userControler.php" method="post" enctype="multipart/form-data">
                Nombre <br><input type="text" name="Name"><br>
                Apellidos <br><input type="text" name="Surname"><br>
                Nombre de usuario <br><input type="text" name="User"><br>
                Correo electrónico <br><input type="email" name="Email"><br>
                Entidad <br><input type="text" name="Entidad"><br>
                Teléfono <br><input type="text" name="Telefono"><br>
                Foto de perfil <br><input type="file" name="foto_perfil" accept="image/jpeg,image/png"><br>
                Contraseña <br><input type="password" name="Passwd"><br>
                Repetir contaseña <br><input type="password" name="Repasswd"><br>
                <input type="hidden" name="rol" value="manager">

                <?php if (!empty($error)): ?>
                <div style="
                    color: white;
                    background-color: #c0392b;
                    border: 1px solid #96281b;
                    border-radius: 6px;
                    padding: 10px 14px;
                    margin-bottom: 12px;
                    font-size: 0.9rem;
                    text-align: center;
                ">
                    <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>

                <br><input type="submit" value="Registrarse" name="register">
             
            </form>
        </div>
     </div>

</body>
</html>
 