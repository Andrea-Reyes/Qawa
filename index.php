<!-- Referencia al header -->
<?php include("header.php"); ?>

    <!-- Contenedor de almuerzos -->
    <div class="contenedor-almuerzos">
        <?php
            //Consulta de la tabla "almuerzos"
            $query = mysqli_query($conexion, "SELECT a.* FROM almuerzos a");
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query)) { ?>
                    <div class="almuerzo">
                        <!-- Detalles del almuerzo -->
                        <img class="almuerzo-imagen" src="css/imagenes/<?php echo $data['imagen']; ?>" alt="..." />
                        <h4 class="almuerzo-nombre"><?php echo $data['nombre'] ?></h4>
                        <p class="almuerzo-descripcion"><?php echo $data['descripcion']; ?></p>
                        <p class="almuerzo-precio">Q <?php echo $data['precio']; ?></p>

                        <?php if ($data['cantidad'] == 0): ?>
                            <!-- Botón "Agotado" -->
                            <div class="text-center">
                                <button class="btn btn-agotado" disabled>Agotado</button>
                            </div>
                        <?php else: ?>
                            <!-- Botón Agregar -->
                            <div class="text-center">
                                <a class="btn btn-outline-dark btn-agregar" data-id="<?php echo $data['id']; ?>" href="#">Agregar</a>
                            </div>

                            <!-- Botón "Detalles" -->
                            <div class="text-center">
                                <a href="detallesAlmuerzos.php" class="btn btn-detalles">Detalles</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php }
            } 
        ?>
    </div>

    <!-- Botón Pedido -->
    <a href="#" class="btn-flotante" id="btnPedido">Pedido <span class="badge bg-success" id="pedido">0</span></a>

    <!-- Referencia al footer -->
    <?php include("footer.php"); ?>