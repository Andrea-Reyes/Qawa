<?php
    if(isset($_GET)) {
        //Verificación del contenido del id
        if (!empty($_GET['id'])) {
            //Conexión con la base de datos
            require_once "../configuraciones/conexion.php";

            //Eliminación de un almuerzo por medio del id con SQL
            $id = $_GET['id'];
            $query = mysqli_query($conexion, "DELETE FROM almuerzos WHERE id = $id");

            //Mantenernos en almuerzos.php
            if ($query) {
                header('Location: almuerzos.php');
            }
        }
    }
?>