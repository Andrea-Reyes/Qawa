<?php 
    //Para la sesión que está activa
    session_start();
    if (empty($_SESSION['id'])) {
        header('Location: ./');
    } 
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="css/imagenes/logo3.jpeg" />
        <title>Almuerzos</title>
    </head>
    <body>
        <li>
            <div>
                <!-- Información del usuario-->
                <img src="css/imagenes/logo1.jpg">
                <span><?php echo $_SESSION['nombre']; ?></span>
                <!-- <a href="#">Perfil</a> -->
                <a href="../salir.php">Salir</a>
            </div>
        </li>