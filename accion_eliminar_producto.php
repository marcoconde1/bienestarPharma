<?php
require_once "funciones_producto.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    eliminarProducto($id);
    header("Location: mostrar_productos.php");
    exit();
}
?>
