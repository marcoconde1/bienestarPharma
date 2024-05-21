<?php
require_once "funciones_marca.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    eliminarMarca($id);
    header("Location: mostrar_marcas.php");
    exit();
}
?>