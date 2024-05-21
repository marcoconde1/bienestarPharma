<?php
require_once "funciones_sucursal.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_sucursal = $_POST['nombre'];
    $direccion_sucursal = $_POST['direccion'];
    $latitud_sucursal = $_POST['latitud'];
    $longitud_sucursal = $_POST['longitud'];

   

    agregarSucursal($nombre_sucursal, $direccion_sucursal, $latitud_sucursal, $longitud_sucursal);
    header("Location: mostrar_sucursales.php");
        exit();
}

exit();
?>
