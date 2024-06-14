<!-- Conexión con la base de datos -->
<?php require_once "configuraciones/conexion.php";
<?php require_once "configuraciones/conexion.php";
require_once "configuraciones/config.php"; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="css/imagenes/iconoNavegador.png" />
    <link rel="stylesheet" href="css/index.css" />
    <link rel="stylesheet" href="css/detallesAlmuerzos.css" />
    <link rel="stylesheet" href="css/estilo_flotante.css">
    <script src="https://kit.fontawesome.com/15898edb4f.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Qawa'</title>
</head>

<body>
    <header class="encabezado">
        <div class="titulo">
            <br><br>
            <h1>Qawa'</h1>
            <p>Cafetería UVG</p><br><br><br>
        </div>
    </header>

    <!-- Botón Pedido -->
    <a href="listaCarrito.php" class="btn-flotante" id="btnPedido">
        <i class="fa-solid fa-cart-plus"></i>
        <span class="badge bg-success" id="pedido"><?php echo $numero_carro ?></span>
    </a>