<?php
require_once "funciones_categoria.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre_categoria = $_POST['nombre'];
        $descripcion_categoria = $_POST['descripcion'];

        anadirCategoria($nombre_categoria, $descripcion_categoria);
        header("Location: mostrar_categorias.php");
        exit();
    }

    exit();
?>
