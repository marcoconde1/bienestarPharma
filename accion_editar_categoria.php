<?php
require_once "funciones_categoria.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    editarCategoria($id, $nombre, $descripcion);
    header("Location: mostrar_categorias.php");
    exit();
}
?>