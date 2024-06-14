<!- Referencia al header ->
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
            $nombreImagen = '';

            //Subir una imagen
            if(isset($_FILES["imagen"])){
                $file = $_FILES["imagen"];
                $nombreImagen = $file["name"];
                $extension = $file["type"];
                $rutaProvisional = $file["tmp_name"];
                $size = $file["size"];
                $carpeta = "../css/imagenes/";

                //Validaciones de la imagen
                if($extension != 'image/jpg' && $extension != 'image/JPG' && $extension != 'image/jpeg' && $extension != 'image/png'){
                    echo "Error: El archivo no es compatible";
                } else if ($size > 3*1024*1024){
                    echo "Error: El tamaño maximo es 3MB";
                } else {
                    $src = $carpeta.$nombreImagen;
                    move_uploaded_file($rutaProvisional, $src);
                    $foto = "../css/imagenes/". $nombreImagen;

                    //Insercción de datos con SQL
                    $query = mysqli_query($conexion, "INSERT INTO almuerzos(id, nombre, descripcion, precio, cantidad, imagen) VALUES ('$id', '$nombre', '$descripcion', $precio, $cantidad, '$nombreImagen')"); 
                }
            }
        } 
    }
    ?>

    <style>
        /* Estilos generales */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('../css/imagenes/fondoAdmin.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            background-color: rgba(255, 255, 255, 0.8); /* Fondo semitransparente de color blanco */
            color: #012E40;
            padding: 20px;
        }

        /* Estilos para la tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            padding: 12px 15px;
            color: #0A0504;
            text-align: left;
            border-bottom: 1px solid #88B0BF;
            background-color: rgba(128, 128, 128, 0.8); /* Color de tabla general*/
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3  ); /* Sombreado oscuro para las letras */
}


        th {
            background-color: #012E40;
            color: #F2EDD0;
        }

        /* Estilos para los botones */
        .btn-eliminar {
            background-color: #D99F7E;
            color: #012E40;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            
        }

        .btn-eliminar:hover {
            background-color: #F2C6AC;
        }

        .btn-formulario, .btn-cerrar {
            display: inline-block;
            background-color: #88B0BF;
            color: #0A0504;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .btn-formulario:hover, .btn-cerrar:hover {
            background-color: #012E40;
        }

        .btn-cerrar {
            background-color: #D99F7E;
            color: #012E40;
        }

        /* Estilos para el modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #F2EDD0;
            margin: 5% auto;
            padding: 30px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        #formulario label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        #formulario input[type="text"],
        #formulario input[type="number"],
        #formulario textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #88B0BF;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 15px;
            font-size: 16px;
            color: #012E40;
            
        }

        #formulario button[type="submit"] {
            background-color: #88B0BF;
            color: #F2EDD0;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        #formulario button[type="submit"]:hover {
            background-color: #012E40;
        }
    </style>

    <!-- Tabla para mostrar los almuerzos registrados -->
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
            // Llamado de datos en la base de datos
            $query = mysqli_query($conexion, "SELECT * FROM almuerzos");

            // Mostrando los datos en una tabla
            while ($data = mysqli_fetch_assoc($query)) { ?>
                <tr>
                    <!-- Información de los almuerzos -->
                    <td><?php echo '<img src="../css/imagenes/'.$data['imagen'].'" width = 100px>' ?> </td>
                    <td><?php echo $data['nombre']; ?></td>
                    <td><?php echo $data['descripcion']; ?></td>
                    <td><?php echo $data['precio']; ?></td>
                    <td><?php echo $data['cantidad']; ?></td>

                    <!-- Bóton para eliminar-->
                    <td>
                            <form method="post" action="eliminarAlmuerzo.php?id=<?php echo $data['id']; ?>">
                                <button class="btn-eliminar" type="submit">Eliminar</button>
                            </form>
                        </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Botón para crear un nuevo almuerzo -->
    <a href="#formulario" id="btn-formulario" class="btn-formulario">Nuevo almuerzo</a>

    <!-- Modal para el formulario -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="formulario">
                <h2>Nuevo almuerzo</h2>
                <form action="#" method="post" enctype="multipart/form-data">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Nombre" required><br>

                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" rows="4" placeholder="Descripción" required></textarea><br>

                    <label for="precio">Precio</label>
                    <input type="number" id="precio" name="precio" placeholder="Precio" required><br>

                    <label for="cantidad">Cantidad</label>
                    <input type="number" id="cantidad" name="cantidad" placeholder="Cantidad" required><br>

                    <label for="imagen">Imagen</label>
                    <input type="file" id="imagen" name="imagen" required><br>

                    <!-- Botones guardar y cerrar -->
                    <br><button type="submit">Guardar</button><br>
                    <a href="#" class="btn-cerrar">Cerrar</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Referencia al footer -->
    <?php include("footer.php"); ?>

    <script>
        // Get the modal
        var modal = document.getElementById("myModal");

        // Get the button that opens the modal
        var btn = document.getElementById("btn-formulario");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // Get the close button in the form
        var closeBtn = document.getElementsByClassName("btn-cerrar")[0];

        // When the user clicks the button, open the modal
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks on close button in the form, close the modal
        closeBtn.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>