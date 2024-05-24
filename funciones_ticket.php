<?php
$dir = dirname(__FILE__);
require_once $dir . "\accion_conexion.php";

function crearTicket(){
    $conexion = conectarse();

    do {
        $id = generarCodigo();
    } while (verificarTicket($id, $conexion)); 

    date_default_timezone_set("America/La_Paz");

   $fecha_emision = date("Y-m-d H:i:s");
  

    $consulta = $conexion->prepare("INSERT INTO ticket (id, fecha_emision, atendido) VALUES (?, ?, ?)");
    $consulta->bind_param("ssi", $id, $fecha_emision, $atendido);
    $atendido = 0; 
    $resultado = $consulta->execute();

    /*
    if ($resultado) {
        echo "Ticket creado correctamente.";
    } else {
        echo "Error al crear el ticket.";
    }
    */

    $conexion->close(); // Cerrar la conexión mysqli
}

function generarCodigo() {
    $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; 
    $longitud = 3; 
    $codigo = '';

    for ($i = 0; $i < $longitud; $i++) {
        $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)]; 
    }

    return $codigo;
}

function verificarTicket($id_ticket, $conexion) {
    $id_ticket = mysqli_real_escape_string($conexion, $id_ticket);
    $consulta = "SELECT COUNT(*) AS existe FROM ticket WHERE id = '$id_ticket'";
    $resultado = mysqli_query($conexion, $consulta);
    if ($resultado) {
        $fila = mysqli_fetch_assoc($resultado);
        return $fila['existe'] > 0;
    } else { 
        return false;
    }
}

function mostrarUltimoTicket() {
    $conexion = conectarse();
    $consulta = $conexion->query("SELECT * FROM ticket ORDER BY fecha_emision DESC LIMIT 1");

    if ($consulta->num_rows > 0) {
        echo "<table border='1'>
        <tr>
        <th>ID</th>
        <th>Fecha de Emisión</th>
        </tr>";

        $fila = $consulta->fetch_assoc();
        echo "<tr>";
        echo "<td>" . $fila["id"] . "</td>";
        echo "<td>" . $fila["fecha_emision"] . "</td>";
        echo "</tr>";

        echo "</table>";
    } else {
        echo "No se encontraron resultados.";
    }

    $conexion->close();
}

function obtenerUltimoTicket() {
    $conexion = conectarse();
    $consulta = $conexion->query("SELECT * FROM ticket ORDER BY fecha_emision DESC LIMIT 1");
    $fila = $consulta->fetch_assoc();
    $conexion->close();
    
    return $fila;
}



?>
