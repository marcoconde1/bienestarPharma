<?php
require_once "funciones_marca.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $pais_origen = $_POST['pais_origen'];
    $anio_fundacion = $_POST['anio_fundacion'];
    $contacto = $_POST['contacto'];

    anadirMarca($nombre, $descripcion, $pais_origen, $anio_fundacion, $contacto);
    header("Location: mostrar_marcas.php");
    exit();
}
?>
