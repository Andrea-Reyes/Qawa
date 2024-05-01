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

                        <!-- Botón "Agotado/Agregar" -->
                        <?php 
                            $disabled = ($data['cantidad'] == 0) ? "disabled" : "";
                            $class = ($data['cantidad'] == 0) ? "btn-agotado" : "btn-agregar";
                        ?>
                        
                        <!-- Botón Agregar -->
                        <div class="text-center">
                            <a class="btn btn-outline-dark <?php echo $class; ?>" data-id="<?php echo $data['id']; ?>" href="#" <?php echo $disabled; ?>><?php echo ($data['cantidad'] == 0) ? 'Agotado' : 'Agregar'; ?></a>
                        </div>
                    </div>
                <?php }
            } 
        ?>
    </div>

    <!-- Referencia al footer -->
    <?php include("footer.php"); ?>