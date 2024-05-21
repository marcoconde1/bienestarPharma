<?php
require_once "funciones_personal.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ci = $_POST['ci'];
    eliminarPersonal($ci);
    header("Location: mostrar_personal.php");
    exit();
}
?>
