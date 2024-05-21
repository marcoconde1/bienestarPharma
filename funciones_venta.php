<?php
$dir = dirname(__FILE__);
require_once $dir . "\accion_conexion.php";

function anadirVenta($ticket_id, $fecha_fin, $total, $cliente_ci, $personal_ci){
    $conexion = conectarse();

    // Preparar la consulta SQL para insertar una nueva venta
    $sql = "INSERT INTO venta (ticket_id, fecha_fin, total, cliente_ci, personal_ci)
            VALUES ('$ticket_id', '$fecha_fin', $total, $cliente_ci, $personal_ci)";

    // Ejecutar la consulta SQL
    if ($conexion->query($sql) === TRUE) {
        echo "Venta añadida exitosamente.";
    } else {
        echo "Error al añadir venta: " . $conexion->error;
    }

    $conexion->close();
}

function mostrarVentas() {
    $conexion = conectarse();
    $sql = "SELECT * FROM venta";

    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Ticket ID</th><th>Fecha Fin</th><th>Total</th><th>Cliente CI</th><th>Personal CI</th></tr>";
        while($fila = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $fila["ticket_id"] . "</td>";
            echo "<td>" . $fila["fecha_fin"] . "</td>";
            echo "<td>" . $fila["total"] . "</td>";
            echo "<td>" . $fila["cliente_ci"] . "</td>";
            echo "<td>" . $fila["personal_ci"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No se encontraron ventas.";
    }

    $conexion->close();
}

function anadirDetalleVenta($cantidad, $subtotal, $producto_id, $venta_ticket_id){
    $conexion = conectarse();

    // Preparar la consulta SQL para insertar un nuevo detalle de venta
    $sql = "INSERT INTO detalle_venta (cantidad, subtotal, producto_id, venta_ticket_id)
            VALUES ($cantidad, $subtotal, $producto_id, '$venta_ticket_id')";

    // Ejecutar la consulta SQL
    if ($conexion->query($sql) === TRUE) {
        echo "Detalle de venta añadido exitosamente.";
    } else {
        echo "Error al añadir detalle de venta: " . $conexion->error;
    }

    $conexion->close();
}



?>
