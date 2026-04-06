<?php
session_start(); // Start the session

$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
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
        <img src="../../Assets/Logo1.png" alt="Logo"/>
      </div>
      <div class="boton_header">
        
          <a href="../../HTML/Pages/SingUp.php"><input type="button" value="Registrarse" alt="Registrarse"/> </a>
       
      </div>
    </div>

    <div class="fondo">
      <div class="recuadro_inicio_sesion">
        <div class="titulo_inicio_sesion">
          <h1>Iniciar sesión</h1>
        </div>
        
        <div class="form">
          <form action="../../../Controler/userControler.php" method="post">
            
            <label for="usuario">Usuario</label>
            <input type="text" name="user" id="usuario"/>

            <br /><br />

            <label for="contraseña">Contraseña</label>
            <input type="password" name="password" id="contraseña"/>

            

            <div class="enter_button">
             <input type="submit" value="Entrar" name="login"/>
            </div>

            <br />
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
            
            <div class="ask_forgotten_password">
              ¿Has olvidado tu contaseña?
            </div>

            <div class="forgot_password_button">
              <a href="../Pages/ForgottenPassword.php"><input type="button" value="Cambiar Contraseña"></a>
            </div>

          </form>
        </div>
      </div>
    </div>
  </body>
</html>
