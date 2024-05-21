<?php
require_once "funciones_sucursal.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    eliminarSucursal($id);
    header("Location: mostrar_sucursales.php");
    exit();
}
?>