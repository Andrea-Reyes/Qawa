<!-- Referencia al header -->
<?php include("header.php"); ?>

<!-- Contenedor de almuerzos -->
<div class="container mt-5">
    <div class="row">
        <?php
        //Consulta de la tabla "almuerzos"
        $query = mysqli_query($conexion, "SELECT a.* FROM almuerzos a");
        $result = mysqli_num_rows($query);
        if ($result > 0) {
            while ($data = mysqli_fetch_assoc($query)) { ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <img class="card-img-top" src="css/imagenes/<?php echo $data['imagen']; ?>" alt="...">
                        <div class="card-body">
                            <h4 class="card-title"><?php echo $data['nombre'] ?></h4>
                            <p class="card-text"><?php echo $data['descripcion']; ?></p>
                            <h5>Q <?php echo $data['precio']; ?></h5>
                        </div>
                        <div class="card-footer text-center">
                            <?php if ($data['cantidad'] == 0) : ?>
                                <!-- Botón "Agotado" -->
                                <button class="btn btn-danger" disabled>Agotado</button>
                            <?php else : ?>
                                <!-- Botón "Detalles" -->
                                <a href="#detalles" class="btn btn-info" onclick="cargarDetallesAlmuerzo(<?php echo $data['id']; ?>)">Ver Detalles</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
        <?php }
        }
        ?>
    </div>
</div>

<!-- Incluye el contenido de la ventana flotante -->
<div id="detalles" class="ventana-flotante">
    <h2></h2>
    <div id="contenido-detalles">
        <!-- Aquí se cargan los detalles del almuerzo mediante AJAX, ubicado en "detallesAlmuerzos.php" -->
    </div>
    <a href="#" id="btn-cerrar">
        <i class="fa-solid fa-xmark"></i>
    </a>
</div>

<!-- Referencia al footer -->
<?php include("footer.php"); ?>

<script src="clases/carga_detalles.js"></script>