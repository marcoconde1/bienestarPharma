<?php
require_once "funciones_personal.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $ci = $_POST['ci'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $permiso = $_POST['permiso'];

        anadirPersonal($ci,$nombre, $apellido,$permiso);
        header("Location: mostrar_personal.php");
    exit();

    }

    exit();
?>
