<!-- Referencia al header -->
<?php include("header.php"); ?>

<?php
// Inicializar la variable $codigo
$codigo = '';

// Verificar si se envió el formulario por POST y no está vacío
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST)) {
    // Consulta de id para incrementarlo y generar un nuevo id
    $query = mysqli_query($conexion, "SELECT MAX(id) AS max_id FROM detalle_pedidos");
    $maxId = mysqli_fetch_assoc($query);
    $id = intval($maxId['max_id']) + 1;

    // Los otros datos
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $subtotal = $_POST['subtotal'];
}
?>
<!-- Tabla para mostrar los almuerzos registrados-->
<div class="container">
    <h1 style="text-align: center;">FACTURA</h1>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Cantidad</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Inicializar la variable para almacenar el total
                $total = 0;

                // Llamado de datos en la base de datos
                $query = mysqli_query($conexion, "SELECT nombre, precio, cantidad, subtotal, codigo FROM detalle_pedidos AS dp JOIN pedidos AS p ON dp.pedido_id = p.id ORDER BY dp.id DESC LIMIT 1;");
                
                // Mostrando los datos en una tabla y sumando el subtotal
                while ($data = mysqli_fetch_assoc($query)) {
                    $subtotal = $data['precio'] * $data['cantidad'];
                    $total += $subtotal;
                    $codigo = $data['codigo']; // Asignar el valor de código en cada iteración
                ?>
                    <tr>
                        <!-- Información de los almuerzos -->
                        <td><?php echo $data['cantidad']; ?></td>
                        <td><?php echo $data['nombre']; ?></td>
                        <td><?php echo $data['precio']; ?></td>
                        <td><?php echo $subtotal; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"></td>
                    <td>Total: <?php echo $total; ?></td>
                </tr>
            </tfoot>
        </table>
        <br>
        <h1 style="text-align: center;"> Código: <?php echo $codigo; ?>
        <br>
        <br>
        <a href="index.php"><button type="button">Menú</button></a> 
        <br>
    </div>
</div>
 

<!-- Referencia al footer -->
<?php include("footer.php"); ?>
