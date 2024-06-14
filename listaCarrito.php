<?php
// Referencia al header
include("header.php");

// Verificar conexión a la base de datos
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener platillos del carrito desde la sesión
$platillos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;
$lista_carrito = array();

// Construir lista de platillos con detalles desde la base de datos
if ($platillos != null) {
    foreach ($platillos as $clave => $cantidad) {
        $query = mysqli_query($conexion, "SELECT id, nombre, descripcion, precio, imagen FROM almuerzos WHERE id = $clave");
        if ($query) {
            $data = mysqli_fetch_assoc($query);
            if ($data) {
                $data['cantidad'] = $cantidad;
                $lista_carrito[] = $data;
            }
        }
    }
}

// Procesar pago cuando se envíe el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pagar'])) {
    if ($conexion) {
        mysqli_begin_transaction($conexion);

        // Calcular el total del pedido
        $total = 0;
        foreach ($lista_carrito as $platillo) {
            $subtotal = $platillo['cantidad'] * $platillo['precio'];
            $total += $subtotal;
        }

        // Insertar el pedido en la tabla 'pedidos'
        $fecha_pedido = date('Y-m-d H:i:s');
        $codigo_pedido = generarCodigoUnico();
        $query_pedido = mysqli_prepare($conexion, "INSERT INTO pedidos (fecha, total, codigo) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($query_pedido, "sds", $fecha_pedido, $total, $codigo_pedido);
        mysqli_stmt_execute($query_pedido);

        if ($query_pedido) {
            // Obtener el ID del pedido insertado
            $pedido_id = mysqli_insert_id($conexion);
            $detalle_exito = true;

            // Insertar los detalles del pedido en la tabla 'detalle_pedidos'
            $query_detalle = mysqli_prepare($conexion, "INSERT INTO detalle_pedidos (pedido_id, platillo_id, nombre, precio, cantidad, subtotal) VALUES (?, ?, ?, ?, ?, ?)");

            foreach ($lista_carrito as $platillo) {
                $platillo_id = intval($platillo['id']);
                $nombre = mysqli_real_escape_string($conexion, $platillo['nombre']);
                $precio = doubleval($platillo['precio']);
                $cantidad = intval($platillo['cantidad']);
                $subtotal = doubleval($precio * $cantidad);

                mysqli_stmt_bind_param($query_detalle, 'iisddd', $pedido_id, $platillo_id, $nombre, $precio, $cantidad, $subtotal);
                mysqli_stmt_execute($query_detalle);

                if (mysqli_stmt_error($query_detalle)) {
                    $detalle_exito = false;
                    $error = mysqli_stmt_error($query_detalle);
                    error_log("Error en la inserción de detalle: $error");
                    echo '<script>alert("Error al insertar detalle del pedido: ' . $error . '");</script>';
                    break;
                }
            }

            if ($detalle_exito) {
                mysqli_commit($conexion);

                // Limpiar carrito después de completar la compra
                unset($_SESSION['carrito']);

                // Redirigir a la página de confirmación con el ID del pedido
                header("Location: confirmacion.php?pedido_id=$pedido_id");
                exit;
            } else {
                mysqli_rollback($conexion);
                echo '<script>alert("Error al procesar los detalles del pedido. Por favor, inténtalo de nuevo.");</script>';
            }
        } else {
            $error = mysqli_error($conexion);
            error_log("Error en la inserción del pedido: $error");
            mysqli_rollback($conexion);
            echo '<script>alert("Error al procesar el pedido: ' . $error . '. Por favor, inténtalo de nuevo.");</script>';
        }
    } else {
        echo '<script>alert("Error de conexión a la base de datos. Por favor, inténtalo de nuevo.");</script>';
    }
}

function generarCodigoUnico()
{
    return substr(md5(uniqid(mt_rand(), true)), 0, 8);
}
?>

<main>
    <div class="container">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Platillo</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($lista_carrito == null) {
                        echo '<tr><td colspan="5" class="text-center"><b>Lista Vacía</b></td></tr>';
                    } else {
                        $total = 0;
                        foreach ($lista_carrito as $platillo) {
                            $_id = $platillo['id'];
                            $nombre = $platillo['nombre'];
                            $precio = $platillo['precio'];
                            $cantidad = $platillo['cantidad'];
                            $subtotal = $cantidad * $precio;
                            $total += $subtotal;
                    ?>
                            <tr>
                                <td><?php echo $nombre; ?></td>
                                <td><?php echo '$' . number_format($precio, 2); ?></td>
                                <td>
                                    <input type="number" min="1" max="10" step="1" value="<?php echo $cantidad; ?>" size="5" id="cantidad_<?php echo $_id; ?>" onchange="">
                                </td>
                                <td>
                                    <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo '$' . number_format($subtotal, 2); ?></div>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-warning btn-sm" data-bs-id="<?php echo $_id; ?>" data-bs-toggle="modal" data-bs-target="#eliminaModal1">Eliminar</a>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="3"></td>
                            <td colspan="2">
                                <p class="h3" id="total"><?php echo 'Q' . number_format($total, 2); ?></p>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-md-5 offset-md-7 d-grid gap-2">
                <form method="post" action="">
                    <button type="submit" name="pagar" class="btn btn-dark btn-lg">Pagar</button>
                </form>
            </div>
        </div>
    </div>
</main>

<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="eliminaModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este elemento?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="confirmDelete" class="btn btn-danger">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var eliminarBtns = document.querySelectorAll('a[data-bs-toggle="modal"]');
        var confirmDeleteButton = document.getElementById('confirmDelete');
        var itemId;

        eliminarBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                itemId = this.getAttribute('data-bs-id');
                confirmDeleteButton.setAttribute('data-id', itemId);
            });
        });

        confirmDeleteButton.addEventListener('click', function () {
            var id = this.getAttribute('data-id');
            eliminarElemento(id);
        });
    });

    function eliminarElemento(id) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "eliminar.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                console.log(xhr.responseText);
                window.location.href = "index.php";
            }
        };
        xhr.send("id=" + id);
    }
</script>

<!-- Referencia al footer -->
<?php include("footer.php"); ?>
