<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: Login.php');
    exit();
}
 
// Cargar datos actuales del usuario desde la BD
$host       = "localhost";
$usuario    = "root";
$password   = "";
$base_datos = "eventsportsbcn";
 
try {
    $conn = new PDO("mysql:host=$host;dbname=$base_datos;charset=utf8mb4", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT nombre, apellidos, nombre_usuario, email, entidad, telefono, foto_perfil FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $userData = ['nombre' => '', 'apellidos' => '', 'nombre_usuario' => '', 'email' => '', 'entidad' => '', 'telefono' => '', 'foto_perfil' => null];
}
?>
 
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../../CSS/Styles/Profile.css" />
    <link rel="stylesheet" href="../../CSS/Global/global.css" />
    <title>Perfil</title>
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
          <form method="POST" action="../../../Controler/userControler.php">
            <button type="submit" name="logout">Cerrar sesión</button>
          </form>
        <?php endif; ?>
      </div>
    </div>
 
    <div class="fondo">
      <div class="recuadro">
        <div class="titulo_mi_cuenta">
          <h3>Mi cuenta</h3>
        </div>
 
        <div class="logo_usuario">
          <?php
            $foto = $userData['foto_perfil'] ?? null;
            $fotoSrc = ($foto && $_SESSION['rol'] === 'manager')
              ? "../../../Controler/profileImages/" . htmlspecialchars($foto)
              : "../../Assets/logo_usuario.png";
          ?>
          <img src="<?= $fotoSrc ?>" alt="Foto perfil" />
        </div>
 
        <!-- ── Mensajes de feedback ── -->
        <?php if (!empty($_SESSION['profile_error'])): ?>
          <p class="msg msg--error"><?= htmlspecialchars($_SESSION['profile_error']) ?></p>
          <?php unset($_SESSION['profile_error']); ?>
        <?php endif; ?>
 
        <?php if (!empty($_SESSION['profile_success'])): ?>
          <p class="msg msg--success"><?= htmlspecialchars($_SESSION['profile_success']) ?></p>
          <?php unset($_SESSION['profile_success']); ?>
        <?php endif; ?>
 
        <?php if (!empty($_SESSION['password_error'])): ?>
          <p class="msg msg--error"><?= htmlspecialchars($_SESSION['password_error']) ?></p>
          <?php unset($_SESSION['password_error']); ?>
        <?php endif; ?>
 
        <?php if (!empty($_SESSION['password_success'])): ?>
          <p class="msg msg--success"><?= htmlspecialchars($_SESSION['password_success']) ?></p>
          <?php unset($_SESSION['password_success']); ?>
        <?php endif; ?>
 
        <!-- ── Formulario datos personales ── -->
        <div class="titulo_mis_datos">
          <h4>Mis datos</h4>
        </div>
 
        <?php
          $formAction   = "../../../Controler/userControler.php";
          $submitName   = ($_SESSION['rol'] === 'manager') ? 'update_manager' : 'update_user';
          $enctype      = ($_SESSION['rol'] === 'manager') ? 'enctype="multipart/form-data"' : '';
        ?>
 
        <form action="<?= $formAction ?>" method="POST" <?= $enctype ?>>
          <label for="nombre">Nombre</label>
          <input
            type="text"
            name="nombre"
            id="nombre"
            maxlength="50"
            value="<?= htmlspecialchars($userData['nombre'] ?? '') ?>"
            required
          />
 
          <label for="apellidos">Apellidos</label>
          <input
            type="text"
            name="apellidos"
            id="apellidos"
            maxlength="100"
            value="<?= htmlspecialchars($userData['apellidos'] ?? '') ?>"
            required
          />
 
          <label for="nombre_usuario">Nombre de usuario</label>
          <input
            type="text"
            name="nombre_usuario"
            id="nombre_usuario"
            maxlength="50"
            value="<?= htmlspecialchars($userData['nombre_usuario'] ?? '') ?>"
            required
          />
 
          <label for="email">Email</label>
          <input
            type="email"
            name="email"
            id="email"
            maxlength="100"
            value="<?= htmlspecialchars($userData['email'] ?? '') ?>"
            required
          />
 
          <?php if ($_SESSION['rol'] === 'manager'): ?>
 
            <label for="entidad">Entidad</label>
            <input
              type="text"
              name="entidad"
              id="entidad"
              maxlength="100"
              value="<?= htmlspecialchars($userData['entidad'] ?? '') ?>"
            />
 
            <label for="telefono">Teléfono</label>
            <input
              type="text"
              name="telefono"
              id="telefono"
              maxlength="20"
              value="<?= htmlspecialchars($userData['telefono'] ?? '') ?>"
            />
 
            <label for="foto_perfil">Foto de perfil</label>
            <input
              type="file"
              name="foto_perfil"
              id="foto_perfil"
              accept="image/jpeg,image/png,image/gif,image/webp"
              class="input-file"
            /> 
          <?php endif; ?>
 
          <button type="submit" name="<?= $submitName ?>" class="btn-guardar">Guardar cambios</button>
        </form>
 
  
        <div class="titulo_mis_datos titulo_password">
          <h4>Cambiar contraseña</h4>
        </div>
 
        <form action="../../../Controler/userControler.php" method="POST">
          <label for="current_password">Contraseña actual</label>
          <input
            type="password"
            name="current_password"
            id="current_password"
            required
          />
 
          <label for="new_password">Nueva contraseña</label>
          <input
            type="password"
            name="new_password"
            id="new_password"
            required
          />
 
          <label for="confirm_password">Confirmar nueva contraseña</label>
          <input 
            type="password"
            name="confirm_password"
            id="confirm_password"
            required
          />
 
          <button type="submit" name="update_password" class="btn-guardar">Actualizar contraseña</button>
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
        Facebook<br />
        Instagram<br />
        X<br />
      </div>
    </footer>
  </body>
</html>