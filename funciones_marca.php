<?php
$dir = dirname(__FILE__);
require_once $dir . "\accion_conexion.php";

function anadirMarca($nombre, $descripcion, $pais_origen, $anio_fundacion, $contacto){
    $conexion = conectarse();

    // Preparar la consulta SQL para insertar una nueva marca
    $sql = "INSERT INTO marca (nombre, descripcion, pais_origen, anio_fundacion, contacto)
            VALUES ('$nombre', '$descripcion', '$pais_origen', '$anio_fundacion', '$contacto')";

    // Ejecutar la consulta SQL
    if ($conexion->query($sql) === TRUE) {
        echo "Marca añadida exitosamente.";
    } else {
        echo "Error al añadir marca: " . $conexion->error;
    }

    $conexion->close();
}

function obtenerMarcas() {
    $conexion = conectarse();
    $sql = "SELECT * FROM marca";

    $resultado = $conexion->query($sql);

    $marcas = [];
    if ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $marcas[] = $fila;
        }
    }

    $conexion->close();
    return $marcas;
}
function eliminarMarca($id) {
    $conexion = conectarse();

    $sql = "DELETE FROM marca WHERE id = $id";
    
    if ($conexion->query($sql) === TRUE) {
        echo "Marca eliminada exitosamente.";
    } else {
        echo "Error al eliminar marca: " . $conexion->error;
    }
    

    $conexion->close();
}

function editarMarca($id, $nombre, $descripcion, $pais_origen, $anio_fundacion, $contacto) {
    $conexion = conectarse();

    $sql = "UPDATE marca SET nombre='$nombre', descripcion='$descripcion', pais_origen='$pais_origen', anio_fundacion='$anio_fundacion', contacto='$contacto' WHERE id=$id";

    if ($conexion->query($sql) === TRUE) {
        echo "Marca actualizada exitosamente.";
    } else {
        echo "Error al actualizar marca: " . $conexion->error;
    }
    
    $conexion->close();
}



function obtenerMarcaPorId($id) {
    $conexion = conectarse();

    $sql = "SELECT * FROM marca WHERE id = $id";

    $resultado = $conexion->query($sql);
    if ($resultado->num_rows > 0) {
        $marca = $resultado->fetch_assoc();
    } else {
        $marca = false;
    }
    $conexion->close();

    return $marca;
}





?>