<?php
$dir = dirname(__FILE__);
require_once $dir . "\accion_conexion.php";

function anadirPersonal($ci,$nombre, $apellido,$permiso){
    $conexion = conectarse();
    $contrasena = $nombre . '.' . $ci;

    // Preparar la consulta SQL para insertar una nueva categoría
    $sql = "INSERT INTO personal (ci, nombre, apellido, contrasena, permiso)
            VALUES ($ci,'$nombre', '$apellido','$contrasena','$permiso')";

    // Ejecutar la consulta SQL
    if ($conexion->query($sql) === TRUE) {
        echo "Categoría añadida exitosamente.";
    } else {
        echo "Error al añadir categoría: " . $conexion->error;
    }

    $conexion->close();
}

function obtenerPersonal() {
    $conexion = conectarse();
    $sql = "SELECT * FROM personal";
    $resultado = $conexion->query($sql);
    $personal = array();

    if ($resultado->num_rows > 0) {
        while($fila = $resultado->fetch_assoc()) {
            $personal[] = $fila;
        }
    }

    $conexion->close();
    return $personal;
}

function eliminarPersonal($ci) {
    $conexion = conectarse();

    $sql = "DELETE FROM personal WHERE ci = $ci";
    
    if ($conexion->query($sql) === TRUE) {
        echo "Categoría eliminada exitosamente.";
    } else {
        echo "Error al eliminar categoría: " . $conexion->error;
    }
    

    $conexion->close();
}

?>
