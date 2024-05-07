    <!-- Referencia al header -->
    <?php include("header.php"); ?>

    <!-- Contenedor de detalles almuerzos -->
    <div class="contenedor-detalles-almuerzos">
        <?php
            //Arreglar lo del id, esto es para probar
            $almuerzo_id = 1; 

            //Consulta de la tabla "almuerzos" por el ID
            $query = mysqli_query($conexion, "SELECT * FROM almuerzos WHERE id = $almuerzo_id");
            $result = mysqli_num_rows($query);

            if ($result > 0) {
                $data = mysqli_fetch_assoc($query); ?>
                <div class="da-imagen">
                    <img src="css/imagenes/<?php echo $data['imagen']; ?>" alt="...">
                </div>

                <div class="da-informacion">
                    <h2>Detalles del almuerzo</h2>
                    <h4 class="da-nombre"><?php echo $data['nombre'] ?></h4>
                    <p class="da-descripcion"><?php echo $data['descripcion']; ?></p>
                    <p class="da-precio">-------------------- <br>Total  Q <?php echo $data['precio']; ?></p>
                </div>
            <?php } else {
                echo "No se encontraron almuerzos con el ID especificado";
            }
        ?>
    </div>

    <!-- Botón Pedido -->
    <a href="#" class="btn-flotante" id="btnPedido">Pedido <span class="badge bg-success" id="pedido">0</span></a>

    <!-- Referencia al footer -->
    <?php include("footer.php"); ?>