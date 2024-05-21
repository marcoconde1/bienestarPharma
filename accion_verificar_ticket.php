<?php
require_once "accion_conexion.php";
date_default_timezone_set("America/La_Paz");
// Verificar si se recibió el ticket en la solicitud POST
if (!isset($_POST['ticket'])) {
    echo "error: No se recibió el ticket en la solicitud.";
    exit; // Terminar el script
}

$conexion = conectarse();

$ticket = $_POST['ticket'];

// Consultar si el ticket existe
$consulta_existe_ticket = "SELECT COUNT(*) AS existe FROM ticket WHERE id = ? and atendido = 0";
$stmt_existe_ticket = $conexion->prepare($consulta_existe_ticket);
$stmt_existe_ticket->bind_param("s", $ticket);
$stmt_existe_ticket->execute();
$stmt_existe_ticket->bind_result($existe);
$stmt_existe_ticket->fetch();
$stmt_existe_ticket->close();

if ($existe > 0) {
    // Consultar el total de la venta
    $consulta_total_venta = "SELECT COUNT(*) AS total FROM venta WHERE ticket_id = ?";
    $stmt_total_venta = $conexion->prepare($consulta_total_venta);
    $stmt_total_venta->bind_param("s", $ticket);
    $stmt_total_venta->execute();
    $stmt_total_venta->bind_result($total);
    $stmt_total_venta->fetch();
    $stmt_total_venta->close();
    
    if ($total === 0) {
        // Insertar nueva venta si no existe
        $fecha_inicio = date("Y-m-d H:i:s");
        $sql_insert_venta = "INSERT INTO venta (ticket_id, fecha_inicio, total) VALUES (?, ?, 0)";
        $stmt_insert_venta = $conexion->prepare($sql_insert_venta);
        $stmt_insert_venta->bind_param("ss", $ticket, $fecha_inicio);
        $stmt_insert_venta->execute();
        $stmt_insert_venta->close();
        echo "existe";
    } else {
        // Actualizar la fecha de inicio si ya existe una venta
        $fecha_inicio = date("Y-m-d H:i:s");
        $sql_update_venta = "UPDATE venta SET fecha_inicio = ? WHERE ticket_id = ?";
        $stmt_update_venta = $conexion->prepare($sql_update_venta);
        $stmt_update_venta->bind_param("ss", $fecha_inicio, $ticket);
        $stmt_update_venta->execute();
        $stmt_update_venta->close();
        echo "existe";
    }
} else {
    echo "no_existe";
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>
