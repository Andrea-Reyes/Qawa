    <!-- Referencia al header -->
    <?php include("header.php"); ?>

    <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            //Creación de un nuevo almuerzo
            if (isset($_POST['accion']) && $_POST['accion'] == 'crear') {
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

                //Datos para subir una imagen
                if (isset($_FILES["imagen"])) {
                    $file = $_FILES["imagen"];
                    $nombreImagen = $file["name"];
                    $extension = $file["type"];
                    $rutaProvisional = $file["tmp_name"];
                    $size = $file["size"];
                    $carpeta = "../css/imagenes/";

                    //Validaciones de la imagen
                    $extensionesPermitidas = array('jpg', 'jpeg', 'png');
                    if (!in_array($extension, $extensionesPermitidas)) {
                        echo "Error: El archivo no es compatible";
                    } else if ($size > 3 * 1024 * 1024) {
                        echo "Error: El tamaño máximo es 3MB";
                    } else {
                        $src = $carpeta . $nombreImagen;
                        move_uploaded_file($rutaProvisional, $src);
                        $foto = "../css/imagenes/" . $nombreImagen;

                        //Inserción de datos con SQL
                        $query = mysqli_query($conexion, "INSERT INTO almuerzos(id, nombre, descripcion, precio, cantidad, imagen) VALUES ('$id', '$nombre', '$descripcion', $precio, $cantidad, '$nombreImagen')");
                    }
                }

                //Actualización un almuerzo existente
            } else if (isset($_POST['accion']) && $_POST['accion'] == 'editar') {
                $id = $_POST['id'];
                $nombre = $_POST['nombre'];
                $descripcion = $_POST['descripcion'];
                $precio = $_POST['precio'];
                $cantidad = $_POST['cantidad'];
                $nombreImagen = '';

                //Subir una imagen nueva si se selecciona
                if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
                    $file = $_FILES["imagen"];
                    $nombreImagen = $file["name"];
                    $extension = $file["type"];
                    $rutaProvisional = $file["tmp_name"];
                    $size = $file["size"];
                    $carpeta = "../css/imagenes/";

                    //Validaciones de la imagen
                    $extensionesPermitidas = array('jpg', 'jpeg', 'png');
                    if (!in_array($extension, $extensionesPermitidas)) {
                        echo "Error: El archivo no es compatible";
                        exit();
                    } elseif ($size > 3 * 1024 * 1024) {
                        echo "Error: El tamaño máximo es 3MB";
                        exit();
                    } else {
                        $src = $carpeta . $nombreImagen;
                        move_uploaded_file($rutaProvisional, $src);
                    }
                } else {
                    //Si no se selecciona una nueva imagen, mantener la imagen anterior
                    $query = mysqli_query($conexion, "SELECT imagen FROM almuerzos WHERE id = $id");
                    $row = mysqli_fetch_assoc($query);
                    $nombreImagen = $row['imagen'];
                }

                //Actualización de datos con SQL
                $stmt = $conexion->prepare("UPDATE almuerzos SET nombre = ?, descripcion = ?, precio = ?, cantidad = ?, imagen = ? WHERE id = ?");
                $stmt->bind_param("ssdisi", $nombre, $descripcion, $precio, $cantidad, $nombreImagen, $id);
                if ($stmt->execute()) {
                    echo "Almuerzo actualizado exitosamente";
                } else {
                    echo "Error: " . $stmt->error;
                }

                // Cerrar la sentencia y la conexión
                //$stmt->close();
                //$conexion->close();
            }
        }
    ?>

    <!-- Botón para crear un nuevo almuerzo -->
    <a href="#formulario" id="btn-formulario">Nuevo almuerzo</a>

    <!-- Tabla para mostrar los almuerzos registrados-->
    <table>
        <!-- Encabezados de la tabla -->
        <thead>
            <tr>
                <th>Imagen</th>
                <th>No. Almuerzo</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th> </th>
                <th> </th>
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
                <tr data-id="<?php echo $data['id']; ?>">
                    <!-- Información de los almuerzos -->
                    <td><?php echo '<img src="../css/imagenes/' . $data['imagen'] . '" width="100px">' ?> </td>
                    <td class="id"><?php echo $data['id']; ?></td>
                    <td class="nombre"><?php echo $data['nombre']; ?></td>
                    <td class="descripcion"><?php echo $data['descripcion']; ?></td>
                    <td class="precio"><?php echo $data['precio']; ?></td>
                    <td class="cantidad"><?php echo $data['cantidad']; ?></td>

                    <!-- Bóton para eliminar-->
                    <td>
                        <form method="post" action="eliminar.php?id=<?php echo $data['id']; ?>">
                            <button class="btn-eliminar" type="submit">Eliminar</button>
                        </form>
                    </td>

                    <!-- Bóton para editar-->
                    <td>
                        <a href="#formulario" id="btn-formulario2" class="btn-editar" data-id="<?php echo $data['id']; ?>">Editar</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Contenido del formulario para crear un almuerzo -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Nuevo almuerzo</h2>
            <form action="#" method="post" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="crear">

                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" placeholder="Nombre" required><br>

                <label for="descripcion">Descripción</label>
                <textarea type="text" id="descripcion" name="descripcion" rows="4" placeholder="Descripción" required></textarea><br>

                <label for="precio">Precio</label>
                <input type="number" id="precio" name="precio" placeholder="Precio" required><br>

                <label for="cantidad">Cantidad</label>
                <input type="number" id="cantidad" name="cantidad" placeholder="Cantidad" required><br>

                <label for="imagen">Imagen</label>
                <input type="file" id="imagen" name="imagen" required><br>

                <!-- Botone guardar -->
                <br><button type="submit">Guardar</button><br>
            </form>
        </div>
    </div>

    <!-- Contenido del formulario para editar un almuerzo -->
    <div id="editarFormulario" style="display:none;" clase="modal-editar">
        <div class="modal-content-editar">
            <span class="close">&times;</span>
            <h2>Editar almuerzo</h2>
            <form action="#" method="post" enctype="multipart/form-data">
                <input type="hidden" id="editId" name="id">

                <input type="hidden" name="accion" value="editar">

                <label for="editNombre">Nombre</label>
                <input type="text" id="editNombre" name="nombre" placeholder="Nombre" required><br>

                <label for="editDescripcion">Descripción</label>
                <textarea id="editDescripcion" name="descripcion" rows="4" placeholder="Descripción" required></textarea><br>

                <label for="editPrecio">Precio</label>
                <input type="number" id="editPrecio" name="precio" placeholder="Precio" required><br>

                <label for="editCantidad">Cantidad</label>
                <input type="number" id="editCantidad" name="cantidad" placeholder="Cantidad" required><br>

                <label for="editImagen">Imagen</label>
                <input type="file" id="editImagen" name="imagen"><br>

                <!-- Botones actualizar y cerrar -->
                <br><button type="submit">Actualizar</button><br>
                <a href="#" id="btn-cerrar">Cerrar</a>
            </form>
        </div>
    </div>

    <!-- Referencia al footer -->
    <?php include("footer.php"); ?>

    <!-- Script para actualizar almuerzos -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            //Abrir formulario
            $('.btn-editar').click(function() {
                var id = $(this).data('id');
                var row = $('tr[data-id="' + id + '"]');
                $('#editId').val(id);
                $('#editNombre').val(row.find('.nombre').text());
                $('#editDescripcion').val(row.find('.descripcion').text());
                $('#editPrecio').val(row.find('.precio').text());
                $('#editCantidad').val(row.find('.cantidad').text());
                $('#editarFormulario').show();
            });

            //Cerrar formulario
            $('#btn-cerrar-editar').click(function(e) {
                e.preventDefault();
                $('#editarFormulario').hide();
            });
        });
    </script>

    <script>
        // Get the modal
        var modal = document.getElementById("myModal");
        var modal2 = document.getElementById("editarFormulario");

        // Get the button that opens the modal
        var btn = document.getElementById("btn-formulario");
        var btn2 = document.getElementById("btn-formulario2");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];
        var span2 = document.getElementsByClassName("close2")[0];

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


    <!-- Diseño de la página -->
    <style>
        /* Estilos generales */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('../css/imagenes/fondoAdmin.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            background-color: rgba(255, 255, 255, 0.8);
            /* Fondo semitransparente de color blanco */
            color: #012E40;
            padding: 20px;
        }

        /* Estilos para la tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th,
        td {
            padding: 12px 15px;
            color: #0A0504;
            text-align: left;
            border-bottom: 1px solid #88B0BF;
            background-color: rgba(128, 128, 128, 0.8);
            /* Color de tabla general*/
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            /* Sombreado oscuro para las letras */
        }

        th {
            background-color: #012E40;
            color: #F2EDD0;
        }

        /* Estilos para los botones */
        .btn-eliminar,
        .btn-editar {
            background-color: #D99F7E;
            color: #012E40;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-eliminar:hover,
        .btn-editar:hover {
            background-color: #F2C6AC;
        }

        .btn-formulario,
        .btn-cerrar {
            display: inline-block;
            background-color: #88B0BF;
            color: #0A0504;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .btn-formulario:hover,
        .btn-cerrar:hover {
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

        #formulario label,
        #editarFormulario label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        #formulario input[type="text"],
        #formulario input[type="number"],
        #formulario textarea,
        #editarFormulario input[type="text"],
        #editarFormulario input[type="number"],
        #editarFormulario textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #88B0BF;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 15px;
            font-size: 16px;
            color: #012E40;

        }

        #formulario button[type="submit"],
        #editarFormulario button[type="submit"] {
            background-color: #88B0BF;
            color: #F2EDD0;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        #formulario button[type="submit"]:hover,
        #editarFormulario button[type="submit"]:hover {
            background-color: #012E40;
        }
    </style>