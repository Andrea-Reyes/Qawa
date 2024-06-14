<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // Verifica si el carrito está en la sesión y si el elemento existe
    if (isset($_SESSION['carrito']['productos'][$id])) {
        unset($_SESSION['carrito']['productos'][$id]);
        echo "Elemento eliminado del carrito.";
    } else {
        echo "Elemento no encontrado en el carrito.";
    }
}
?>
