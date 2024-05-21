<?php
require_once "funciones_categoria.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    eliminarCategoria($id);
    header("Location: mostrar_categorias.php");
    exit();
}
?>
