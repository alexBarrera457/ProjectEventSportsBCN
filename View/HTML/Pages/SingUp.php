<?php
session_start();

$error = "";

/*if(isset($_POST['Name'])) {
    $_SESSION['name'] = $_POST['Name'];
    $_SESSION['surname'] = $_POST['Surname'];
    $_SESSION['email'] = $_POST['Email'];
    $_SESSION['passwd'] = $_POST['Passwd'];
    $_SESSION['repasswd'] = $_POST['Repasswd'];

    register();
    header("Location: HomeMenu.php");

}
//*/
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/Styles/SingUp.css" />
    <link rel="stylesheet" href="../../CSS/Global/global.css">
    <title>Registrarse</title>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
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
                <h1>Registro</h1>
            </div>
            <div class="botones_form">
                <form action="/../../Controler/userControler.php" method="post">
                    Nombre <br><input type="text" name="Name" required><br>
                    Apellidos <br><input type="text" name="Surname" required><br>
                    Nombre de usuario <br><input type="text" name="User" required><br>
                    Correo electrónico <br><input type="email" name="Email" required><br>
                    Contraseña <br><input type="text" name="Passwd" required><br>
                    Repetir contaseña <br><input type="text" name="Reppasswd" required><br>
                    <br><input type="submit" value="Registrarse">
                </form>    
            </div>                          
        </div>
     </div>

</body>
</html>
 