<?php 
    //Para la sesión que está activa
    session_start();
    if (empty($_SESSION['id'])) {
        header('Location: ./');
    } 

    //Conexión con la base de datos
    require_once "../configuraciones/conexion.php";
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="css/imagenes/logo2.webp" />
        <link href="almuerzosAdmin.css" rel="stylesheet" /> 
        <title>Almuerzos</title>
    </head>
    <body>
        <li>
            <div>
                <!-- Información del usuario-->
                <img src="../css/imagenes/perfil.png">
                <span><?php echo $_SESSION['nombre']; ?></span>
                <!-- <a href="#">Perfil</a> -->
                <a href="../salir.php">Salir</a>
            </div>
        </li>