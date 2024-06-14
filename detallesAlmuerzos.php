<?php
    require_once "configuraciones/conexion.php";
    require_once "configuraciones/config.php";
    // Validación de envío de datos
    $id = isset($_GET['id']) ? $_GET['id'] : '';

    if ($id == '') {
        echo 'Error al procesar la petición';
        exit;
    }

    // Consulta para verificar la existencia del almuerzo
    $query = mysqli_prepare($conexion, "SELECT COUNT(id) FROM almuerzos WHERE id = ?");
    if ($query === false) {
        echo 'Error en la preparación de la consulta: ' . mysqli_error($conexion);
        exit;
    }

    mysqli_stmt_bind_param($query, 'i', $id);
    mysqli_stmt_execute($query);
    mysqli_stmt_bind_result($query, $count);
    mysqli_stmt_fetch($query);
    mysqli_stmt_close($query);

    if ($count > 0) {
        // Consulta para obtener los detalles del almuerzo
        $query = mysqli_prepare($conexion, "SELECT * FROM almuerzos WHERE id = ? LIMIT 1");
        if ($query === false) {
            echo 'Error en la preparación de la consulta: ' . mysqli_error($conexion);
            exit;
        }

        mysqli_stmt_bind_param($query, 'i', $id);
        mysqli_stmt_execute($query);
        $result = mysqli_stmt_get_result($query);
        $data = mysqli_fetch_assoc($result);
        mysqli_stmt_close($query);
    } else {
        echo 'No se encontró el almuerzo';
        exit;
    }
?>

<!-- Contenedor de detalles almuerzos -->
<?php if (!empty($data)) { ?>
    <div class="da-imagen">
        <img src="css/imagenes/<?php echo $data['imagen']; ?>" alt="...">
    </div>

    <div class="da-informacion">
        <h2>Detalles del almuerzo</h2>
        <h4 class="da-nombre"><?php echo $data['nombre'] ?></h4>
        <p class="da-descripcion"><?php echo $data['descripcion']; ?></p>
        <p class="da-precio">-------------------- <br>Precio <?php echo MONEDA . $data['precio']; ?></p>

        <div class="d-flex align-items-center justify-content-center gap-3">
            <button class="btn btn-outline-danger" type="button" id="restar">
                <i class="fa-solid fa-square-minus"></i>
            </button>
            <input type="number" id="cantidad" class="form-control text-center w-auto" value="0">
            <button class="btn btn-outline-success" type="button" id="sumar">
                <i class="fa-solid fa-square-plus"></i>
            </button>
        </div>
        <div class="container-md">
            <button class="btn btn-dark" type="button" id="agregar" onclick=" agregarPlatillo(<?php echo $data['id']; ?>)">
                <i class="fa-solid fa-cart-shopping"></i> Agregar
            </button>
        </div>
    </div>

<?php } else {
    echo "No se encontraron almuerzos con el ID especificado";
} ?>

<script src="clases/carga_detalles.js"></script>