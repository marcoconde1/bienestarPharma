<?php
require_once "funciones_ticket.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    crearTicket();
    header("Location: generar_ticket.php");
    exit();
}
?>
