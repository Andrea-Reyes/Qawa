<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="indexadmin.css">
  <link rel="icon" type="image/x-icon" href="css/imagenes/logo4.png" />
  <title>Inicio de sesión</title>
</head>
<body>
  <?php
      // Verificación de sesiones abiertas
      session_start();
      if (!empty($_SESSION['active'])) {
          // Se redirige a almuerzos.php
          header('location: almuerzos.php');
          exit();
      } else {
          // No hay ninguna sesión abierta
          if (!empty($_POST)) {
              $alert = '';

              // Verificación de que los campos no están vacíos
              if (empty($_POST['usuario']) || empty($_POST['clave'])) {
                  $alert = 'Ingrese su usuario y contraseña';
              } else {
                  // Conexión con la base de datos
                  require_once "../configuraciones/conexion.php";
                  $user = mysqli_real_escape_string($conexion, $_POST['usuario']);
                  $clave = $_POST['clave'];
                  $query = mysqli_query($conexion, "SELECT * FROM usuarios WHERE usuario = '$user'");
                  
                  if (!$query) {
                      die('Error en la consulta: ' . mysqli_error($conexion));
                  }

                  $resultado = mysqli_fetch_assoc($query);
                  mysqli_close($conexion);
                  
                  if ($resultado && $clave === $resultado['clave']) {
                      $_SESSION['active'] = true;
                      $_SESSION['id'] = $resultado['id'];
                      $_SESSION['nombre'] = $resultado['nombre'];
                      $_SESSION['user'] = $resultado['usuario'];
                      header('Location: almuerzos.php');
                      exit();
                  } else {
                      // Mensaje de error cuando los datos son incorrectos
                      $alert = 'Usuario o contraseña incorrectos';
                  }
              }
          }
      }
  ?>

  <section>
      <div class="form-box">
          <div class="form-value">
              <form method="POST" action="" autocomplete="off">
                  <h2>Bienvenido a QAWA'</h2>
                  <?php echo (isset($alert)) ? '<p style="color:red;">' . $alert . '</p>' : ''; ?>
                  <div class="inputbox">
                      <ion-icon name="mail-outline"></ion-icon>
                      <input type="text" name="usuario" required>
                      <label for="">Usuario</label>
                  </div>
                  <div class="inputbox">
                      <ion-icon name="lock-closed-outline"></ion-icon>
                      <input type="password" name="clave" required>
                      <label for="">Contraseña</label>
                  </div>
                  <button type="submit">INICIAR</button>
              </form>
          </div>
      </div>
  </section>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
