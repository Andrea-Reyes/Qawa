    <!-- Referencia al header -->
    <?php include("header.php"); ?> 

    <?php
        if (isset($_POST)) {
            if (!empty($_POST)) {
                //Consulta de id para incrementarlo y generar un nuevo id
                $query = mysqli_query($conexion, "SELECT MAX(id) AS max_id FROM almuerzos");
                $maxId = mysqli_fetch_assoc($query);
                $id = intval($maxId['max_id']) + 1;

                //Los otros datos
                $nombre = $_POST['nombre'];
                $descripcion = $_POST['descripcion'];
                $precio = $_POST['precio'];
                $cantidad = $_POST['cantidad'];

                //Insercción de datos con SQL       //Arreglar la parte de imagen :c
                $query = mysqli_query($conexion, "INSERT INTO almuerzos(id, nombre, descripcion, precio, cantidad, imagen) VALUES ('$id', '$nombre', '$descripcion', $precio, $cantidad, 'c1_milanesaPollo.jpg')"); 
            } 
        }
    ?>
 
    <!-- Tabla para mostrar los almuerzos registrados-->
    <table>
        <!-- Encabezados de la tabla -->
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th></th>
            </tr>
        </thead>

        <!-- Cuerpo de la tabla -->
        <tbody>
            <?php
                //Llamado de datos en la base de datos
                $query = mysqli_query($conexion, "SELECT *  FROM almuerzos");
                
                //Mostrando los datos en una tabla
                while ($data = mysqli_fetch_assoc($query)) { ?>
                    <tr>
                        <!-- Información de los almuerzos -->
                        <td><img class="img-thumbnail" src="../assets/img/<?php echo $data['imagen']; ?>" width="150px"></td>
                        <td><?php echo $data['nombre']; ?></td>
                        <td><?php echo $data['descripcion']; ?></td>
                        <td><?php echo $data['precio']; ?></td>
                        <td><?php echo $data['cantidad']; ?></td>

                        <!-- Bóton para eliminar-->
                        <td>
                            <form method="post" action="eliminar.php?id=<?php echo $data['id']; ?>">
                                <button class="btn-eliminar" type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Botón para creafr un nuevo almuerzo -->
    <a href="#formulario" id="btn-formulario">Nuevo almuerzo</a>

    <!-- Contenido del formulario -->
    <div id="formulario">
        <h2>Nuevo almuerzo</h2>
        <form action="#" method="post">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" placeholder="Nombre" require><br>

            <label for="descripcion">Descripción</label>
            <textarea type="text" id="descripcion" name="descripcion" rows="4" placeholder="Descripción" require></textarea><br>

            <label for="precio">Precio</label>
            <input type="number" id="precio" name="precio" placeholder="Precio" require><br>

            <label for="cantidad">Cantidad</label>
            <input type="number" id="cantidad" name="cantidad" placeholder="Cantidad" require><br>

            <label for="imagen">Imagen</label>
            <input type="file" id="imagen" name="imagen" required><br>
           
            <!-- Botones guardar y cerrar -->
            <br><button type="submit">Guardar</button><br>
            <a href="#" id="btn-cerrar">Cerrar</a>
        </form>
    </div>

    <!-- Referencia al footer -->
    <?php include("footer.php"); ?>