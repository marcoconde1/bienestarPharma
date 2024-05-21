<?php
$dir = dirname(__FILE__);
require_once $dir . "\accion_conexion.php";

function anadirCategoria($nombre, $descripcion){
    $conexion = conectarse();

    // Preparar la consulta SQL para insertar una nueva categoría
    $sql = "INSERT INTO categoria (nombre, descripcion)
            VALUES ('$nombre', '$descripcion')";

    // Ejecutar la consulta SQL
    if ($conexion->query($sql) === TRUE) {
        echo "Categoría añadida exitosamente.";
    } else {
        echo "Error al añadir categoría: " . $conexion->error;
    }

    $conexion->close();
}

function obtenerCategorias() {
    $conexion = conectarse();
    $sql = "SELECT * FROM categoria";
    $resultado = $conexion->query($sql);
    $categorias = array();

    if ($resultado->num_rows > 0) {
        while($fila = $resultado->fetch_assoc()) {
            $categorias[] = $fila;
        }
    }

    $conexion->close();
    return $categorias;
}

function eliminarCategoria($id) {
    $conexion = conectarse();

    $sql = "DELETE FROM categoria WHERE id = $id";
    
    if ($conexion->query($sql) === TRUE) {
        echo "Categoría eliminada exitosamente.";
    } else {
        echo "Error al eliminar categoría: " . $conexion->error;
    }
    

    $conexion->close();
}

function editarCategoria($id, $nombre, $descripcion) {
    $conexion = conectarse();

    $sql = "UPDATE categoria SET nombre='$nombre', descripcion='$descripcion' WHERE id=$id";

    if ($conexion->query($sql) === TRUE) {
        echo "Categoría actualizada exitosamente.";
    } else {
        echo "Error al actualizar categoría: " . $conexion->error;
    }
    
    $conexion->close();
}

function obtenerCategoriaPorId($id) {
    $conexion = conectarse();

    $sql = "SELECT * FROM categoria WHERE id = $id";

    $resultado = $conexion->query($sql);
    if ($resultado->num_rows > 0) {
        $categoria = $resultado->fetch_assoc();
    } else {
        $categoria = false;
    }
    $conexion->close();

    return $categoria;
}
?>
