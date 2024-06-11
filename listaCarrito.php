<!-- Referencia al header -->
<?php include("header.php"); ?>

<?php

$platillos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;
//print_r($_SESSION); // Debug: Imprimir la sesión
session_destroy();
$lista_carrito = array();

if ($platillos != null) {
    // Variable clave tiene el valor del id del platillo
    foreach ($platillos as $clave => $cantidad) {
        $query = mysqli_query($conexion, "SELECT id, nombre, descripcion, precio, imagen, $cantidad AS cantidad FROM almuerzos WHERE id = $clave");
        if ($query) {
            $data = mysqli_fetch_assoc($query);
            if ($data) {
                $lista_carrito[] = $data;
            }
        }
    }
}

// Para imprimir la lista de carritos
//print_r($lista_carrito);
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
                                <td><?php echo MONEDA . ($precio); ?></td>
                                <td>
                                    <input type="number" min="1" max="10" step="1" value="<?php echo $cantidad ?>" size="5" id="cantidad_<?php echo $_id; ?>" onchange="">
                                </td>
                                <td>
                                    <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"> <?php echo MONEDA . number_format($subtotal,2,'.', ','); ?></div>
                                </td>
                                <td>
                                    <a href="#" id="eliminar" class="btn btn-warning btn-sm" data-bs-id="<?php echo $_id; ?>" data-bs-toggle="modal" data-bs-target="#eliminaModal1">Eliminar</a>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="3"></td>
                            <td colspan="2">
                                <p class="h3" id="total"><?php echo MONEDA . number_format($total, 2, '.', ','); ?></p>
                            </td>                          
                        </tr>
                </tbody>
            <?php } ?>
            </table>
        </div>
        <div class="row">
            <div class="col-md-5 offset-md-7 d-grid gap-2">
                <button class="btn btn-dark btn-lg">Pagar</button>
            </div>
        </div>
    </div>
</main>

<!-- Referencia al footer -->
<?php include("footer.php"); ?>
