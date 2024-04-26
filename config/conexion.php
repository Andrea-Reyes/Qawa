<?php
    $host = "biljodk4j6hgxshdehhu-mysql.services.clever-cloud.com";
    $user = "ulof6dqlkxf4xizn";
    $password = "XXAQCO1DMtreVIhweMAb";
    $bd = "biljodk4j6hgxshdehhu";
    $conexion = mysqli_connect($host,$user,$password,$bd);
    if (mysqli_connect_errno()){
        echo "No se pudo conectar a la base de datos";
        exit();
    }
    mysqli_select_db($conexion,$bd) or die("No se encuentra la base de datos");
    mysqli_set_charset($conexion,"utf8");