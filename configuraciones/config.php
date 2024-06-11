<?php
define("KEY_TOKEN", "Def.wqwa-298*");
define('CLIENT_ID', '');
define('LOCALE', 'es_ES');
define('MONEDA', 'Q ');
//Para que inicie la sesión una vez el usuario ingresa a la página
session_start();

//Para tener el número de tipos de almuerzos
$numero_carro = 0;
if(isset($_SESSION['carrito']['productos'])){
    $numero_carro = count($_SESSION['carrito']['productos']);
}

?>