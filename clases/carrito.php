<?php
    require_once "../configuraciones/conexion.php";
    require_once "../configuraciones/config.php";

    // Validación con isset para saber si se está enviando una variable mediante el método POST llamado id
    if (isset($_POST['id']) && isset($_POST['cantidad'])) {
        $id = $_POST['id'];
        $cantidad = intval($_POST['cantidad']);
        
        if ($cantidad > 0) {
            if (!isset($_SESSION['carrito'])) {
                $_SESSION['carrito'] = ['productos' => []];
            }
            
            if (isset($_SESSION['carrito']['productos'][$id])) {
                $_SESSION['carrito']['productos'][$id] += $cantidad;
            } else {
                $_SESSION['carrito']['productos'][$id] = $cantidad; // id obtenido del producto
            }
            
            $datos['numero'] = count($_SESSION['carrito']['productos']);
            $datos['ok'] = true;
        } else {
            $datos['ok'] = false;
        }
    } else {
        // se crea un arreglo, el índice será 'ok'
        $datos['ok'] = false;
    }

    // Responder al cliente con una respuesta JSON
    header('Content-Type: application/json');
    echo json_encode($datos);
?>