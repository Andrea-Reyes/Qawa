<?php
/*
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //Verificación del contenido del id
        if (!empty($_POST['id'])) {
            //Conexión con la base de datos
            require_once "../configuraciones/conexion.php";

            //Valores para actualizar
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $precio = $_POST['precio'];
            $cantidad = $_POST['cantidad'];
            $imagen = $_POST['imagen'];

            //Actualización de un almuerzo por medio del id con SQL
            $stmt = $conexion->prepare("UPDATE almuerzos SET nombre = ?, descripcion = ?, precio = ?, cantidad = ?, imagen = ? WHERE id = ?");
            $stmt->bind_param("ssdis", $nombre, $descripcion, $precio, $cantidad, $imagen);

            //Ejecutar la sentencia
            if ($stmt->execute()) {
                header('Location: almuerzos.php');
            } else {
                echo "Error: " . $stmt->error;
            }

            //Cerrar la sentencia y la conexión
            $stmt->close();
            $conexion->close();
        }
    }
        */
?>


<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once "../configuraciones/conexion.php";

    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $nombreImagen = '';

    // Subir una imagen nueva si se selecciona
    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
        $file = $_FILES["imagen"];
        $nombreImagen = basename($file["name"]);
        $extension = pathinfo($nombreImagen, PATHINFO_EXTENSION);
        $rutaProvisional = $file["tmp_name"];
        $size = $file["size"];
        $carpeta = "../css/imagenes/";

        // Validaciones de la imagen
        $extensionesPermitidas = array('jpg', 'jpeg', 'png');
        if (!in_array($extension, $extensionesPermitidas)) {
            echo "Error: El archivo no es compatible";
            exit();
        } elseif ($size > 3*1024*1024) {
            echo "Error: El tamaño máximo es 3MB";
            exit();
        } else {
            $src = $carpeta . $nombreImagen;
            move_uploaded_file($rutaProvisional, $src);
        }
    } else {
        // Si no se selecciona una nueva imagen, mantener la imagen anterior
        $query = mysqli_query($conexion, "SELECT imagen FROM almuerzos WHERE id = $id");
        $row = mysqli_fetch_assoc($query);
        $nombreImagen = $row['imagen'];
    }

    // Actualización de datos con SQL
    $stmt = $conexion->prepare("UPDATE almuerzos SET nombre = ?, descripcion = ?, precio = ?, cantidad = ?, imagen = ? WHERE id = ?");
    $stmt->bind_param("ssdisi", $nombre, $descripcion, $precio, $cantidad, $nombreImagen, $id);
    if ($stmt->execute()) {
        echo "Almuerzo actualizado exitosamente";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Cerrar la sentencia y la conexión
    $stmt->close();
    $conexion->close();
}
?>