    <!-- Referencia al header -->
    <?php include("header.php"); ?>
        
        <?php
            //Conexión con la base de datos
            require_once "../configuraciones/conexion.php";

            if (isset($_POST)) {
                if (!empty($_POST)) {
                    $nombre = $_POST['nombre'];
                    $descripcion = $_POST['descripcion'];
                    $precio = $_POST['precio'];
                    $cantidad = $_POST['cantidad'];

                    //Consulta en la base de datos
                    $query = mysqli_query($conexion, "SELECT imagen FROM almuerzos WHERE nombre = '$nombre'");
                    
                    if (!$query) {
                        die('Error en la consulta: ' . mysqli_error($conexion));
                    }

                    $resultado = mysqli_fetch_assoc($query);
                    $nombreImagen = $resultado['imagen'];

                    //Para el nombre de la imagen
                    $imagen = $nombreImagen . ".jpg";
                    $destino = "../css/imagenes/" . $imagen;

                    if (move_uploaded_file($tmpname, $destino)) {
                        header('Location: almuerzos.php');
                        exit();
                    } else {
                        echo "Error al mostrar la imagen";
                    }
                }
            }
        ?>

        <table>
            <thead>
                <tr>
                    <!-- Encabezados de la tabla -->
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php
                    $query = mysqli_query($conexion, "SELECT *  FROM almuerzos");
                    
                    //Mostrando los datos en una tabla
                    while ($data = mysqli_fetch_assoc($query)) { ?>
                        <tr>
                            <td><img class="img-thumbnail" src="../assets/img/<?php echo $data['imagen']; ?>" width="50"></td>
                            <td><?php echo $data['nombre']; ?></td>
                            <td><?php echo $data['descripcion']; ?></td>
                            <td><?php echo $data['precio']; ?></td>
                            <td><?php echo $data['cantidad']; ?></td>
                            <td>
                                <form method="post" action="eliminar.php?accion=pro&id=<?php echo $data['id']; ?>">
                                    <button class="btn eliminar" type="submit">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                <?php } ?>
            </tbody>
        </table>
    </body>
</html>