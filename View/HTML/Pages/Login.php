<?php
session_start(); // Start the session

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../../PHP/Controllers/UserController.php';
    
    $username = trim($_POST['usuario'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = "Por favor, rellena todos los campos.";
    } else {
        $controller = new UserController();
        $ok = $controller->login($username, $password);

        if ($ok) {
            header('Location: Dashboard.php');
            exit;
        } else {
            $error = "Usuario o contraseña incorrectos.";
        }
    }
}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../../CSS/Styles/Login.css" />
    <link rel="stylesheet" href="../../CSS/Global/global.css" />
    <title>Iniciar sesión</title>
  </head>
  <body>
    <div class="header">
      <div class="logo_web">
        <img src="../../Assets/Logo1.png"/>
      </div>
      <div class="boton_header">
        <h4>
          <a href="../../HTML/Pages/SingUp.html"
            ><input type="button" value="Registrarse"
          /></a>
        </h4>
      </div>
    </div>

    <div class="fondo">
      <div class="recuadro_inicio_sesion">
        <div class="titulo_inicio_sesion">
          <h2>Iniciar sesión</h2>
        </div>
        
        <div class="form">
          <form action="../../HTML/Pages/Login.php" method="post">

            <label for="usuario">Usuario</label>
            <input type="text" name="usuario" id="usuario"/>

            <br /><br />

            <label for="contraseña">Contraseña</label>
            <input type="text" name="contraseña" id="contraseña"/>

            <br /><br />

            <div class="enter_button">
             <input type="submit" value="Entrar"/>
            </div>
            
            <br><br>
            
            <div class="ask_forgotten_password">
              ¿Has olvidado tu contaseña?
            </div>

            <div class="forgot_password_button">
              <a href="../Pages/ForgottenPassword.html"><input type="button" value="Cambiar Contraseña"></a>
            </div>

          </form>
        </div>
      </div>
    </div>
  </body>
</html>
