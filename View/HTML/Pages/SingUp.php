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
    <title>Registro Usuario</title>
</head>
<body>
 
    <header>
        <div class="header_web">
            <div class="logo">
                <img src="../../Assets/Logo1.png" alt="Logo">
            </div>
            <div class="login_but">
                <a href="../../HTML/Pages/Login.php"><input type="button" value="Iniciar sesión"></a>
            </div>
        </div>        
    </header>
    
    <div class = "fondo">
        <div class="recuadro_registro">
            <div class="titulo_registro">
                <h1>Registro Usuario</h1>
            </div>

                <?php if (!empty($error)): ?>
                <div class="error_msg">
                    <p><?= htmlspecialchars($error) ?></p>
                </div>
            <?php endif; ?>

            <div class="botones_form">
                <form action="/../../Controler/userControler.php" method="post">
                    Nombre <br><input type="text" name="Name" required><br>
                    Apellidos <br><input type="text" name="Surname" required><br>
                    Nombre de usuario <br><input type="text" name="User" required><br>
                    Correo electrónico <br><input type="email" name="Email" required><br>
                    Contraseña <br><input type="text" name="Passwd" required><br>
                    Repetir contaseña <br><input type="text" name="Reppasswd" required><br>
                    <input type="hidden" name="rol" value="usuario">
                    <br><input type="submit" value="Registrarse" name="register">
                </form>    
            </div>                          
        </div>
     </div>

</body>
</html>
 