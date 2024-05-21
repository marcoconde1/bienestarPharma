<?php
$dir = dirname(__FILE__);
require_once $dir . "\accion_conexion.php";

function agregarSucursal($nombre, $direccion, $latitud, $longitud){
    $conexion = conectarse();

    // Preparar la consulta SQL para insertar una nueva sucursal
    $sql = "INSERT INTO sucursal (nombre, direccion, latitud, longitud)
            VALUES ('$nombre', '$direccion', '$latitud', '$longitud')";

    // Ejecutar la consulta SQL
    if ($conexion->query($sql) === TRUE) {
        echo "Sucursal añadida exitosamente.";
    } else {
        echo "Error al añadir sucursal: " . $conexion->error;
    }

    $conexion->close();
}

function obtenerSucursales() {
    $conexion = conectarse();
    $sql = "SELECT * FROM sucursal";
    $resultado = $conexion->query($sql);
    $sucursales = [];

    if ($resultado->num_rows > 0) {
        while($fila = $resultado->fetch_assoc()) {
            $sucursales[] = $fila;
        }
    }

    $conexion->close();

    return $sucursales;
}




function eliminarSucursal($id) {
    $conexion = conectarse();

    $sql = "DELETE FROM sucursal WHERE id = $id";
    
    if ($conexion->query($sql) === TRUE) {
        echo "Sucursal eliminada exitosamente.";
    } else {
        echo "Error al eliminar sucursal: " . $conexion->error;
    }
    

    $conexion->close();
}

function editarSucursal($id, $nombre, $direccion, $latitud, $longitud) {
    $conexion = conectarse();

    $sql = "UPDATE sucursal SET nombre='$nombre', direccion='$direccion', latitud='$latitud', longitud='$longitud' WHERE id=$id";

    if ($conexion->query($sql) === TRUE) {
        echo "Sucursal actualizada exitosamente.";
    } else {
        echo "Error al actualizar sucursal: " . $conexion->error;
    }
    
    $conexion->close();
}

function obtenerSucursalPorId($id) {
    $conexion = conectarse();

    $sql = "SELECT * FROM sucursal WHERE id = $id";

    $resultado = $conexion->query($sql);
    if ($resultado->num_rows > 0) {
        $sucursal = $resultado->fetch_assoc();
    } else {
        $sucursal = false;
    }
    $conexion->close();

    return $sucursal;
}
?>
