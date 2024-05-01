<!-- Conexión con la base de datos -->
<?php require_once "configuraciones/conexion.php"; ?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Título e icono de la página -->
        <title>Qawa'</title>
        <link rel="icon" type="image/x-icon" href="imagenes/icono.webp" />

        <!-- Referencia a las hojas de estilo -->
        <link href="css/styles.css" rel="stylesheet" />
        <link href="css/estilos.css" rel="stylesheet" />
    </head>

    <body>
        <!-- Referencia al header -->
        <?php include("header.php"); ?>

        <!-- Productos -->
        <section class="py-5">
            <div class="container px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                    <?php
                        //Consulta de la tabla "almuerzos"
                        $query = mysqli_query($conexion, "SELECT a.* FROM almuerzos a");
                        $result = mysqli_num_rows($query);
                        if ($result > 0) {
                            while ($data = mysqli_fetch_assoc($query)) { ?>
                                <div class="col mb-5 productos">
                                    <div class="card h-100">
                                        <!-- Etiqueta "Agotado" -->
                                        <div class="badge bg-danger text-white position-absolute" style="top: 0.5rem; right: 0.5rem"><?php echo ($data['cantidad'] == 0) ? 'Agotado' : ''; ?></div>
                                        
                                        <!-- Detalles del almuerzo -->
                                        <img class="card-img-top" src="imagenes/<?php echo $data['imagen']; ?>" alt="..." />
                                        <div class="card-body p-4">
                                            <div class="text-center">
                                                <h5 class="fw-bolder"><?php echo $data['nombre'] ?></h5>
                                                <p><?php echo $data['descripcion']; ?></p>
                                                <p>Q <?php echo $data['precio']; ?></p>
                                                
                                                <!-- Product reviews
                                                <div class="d-flex justify-content-center small text-warning mb-2">
                                                    <div class="bi-star-fill"></div>
                                                    <div class="bi-star-fill"></div>
                                                    <div class="bi-star-fill"></div>
                                                    <div class="bi-star-fill"></div>
                                                    <div class="bi-star-fill"></div>
                                                </div>-->

                                            </div>
                                        </div>

                                        <!-- Botón Agregar -->
                                        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                            <div class="text-center"><a class="btn btn-outline-dark mt-auto agregar" data-id="<?php echo $data['id']; ?>" href="#">Agregar</a></div>
                                        </div>
                                    </div>
                                </div>
                            <?php  }
                        } 
                    ?>
                </div>
            </div>
        </section>

        <!-- Referencia al footer -->
        <?php include("footer.php"); ?>
    </body>
</html>