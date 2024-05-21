<?php
$dir = dirname(__FILE__);
require_once $dir . "\accion_conexion.php";

function agregarProducto($nombre, $cantidad, $precio, $imagen_temporal, $categoria_id, $marca_id){
    $conexion = conectarse();

    $nombre = $conexion->real_escape_string($nombre);
    $cantidad = intval($cantidad);
    $precio = floatval($precio); 

    $imagen_contenido = file_get_contents($imagen_temporal);

    $imagen_contenido = $conexion->real_escape_string($imagen_contenido);

    $sql = "INSERT INTO producto (nombre, cantidad, precio, imagen, categoria_id, marca_id)
            VALUES ('$nombre', $cantidad, $precio, '$imagen_contenido', $categoria_id, $marca_id)";

    // Ejecutar la consulta SQL
    if ($conexion->query($sql) === TRUE) {
        echo "Producto agregado exitosamente.";
    } else {
        echo "Error al agregar producto: " . $conexion->error;
    }

    $conexion->close();
}

function agregarLote($producto_id, $cantidad, $precio_ordenar, $precio_mantener, $precio_compra, $precio_faltantes){
    $conexion = conectarse();

    $sql = "INSERT INTO lote (producto_id, cantidad, precio_ordenar, precio_mantener, precio_compra, precio_faltantes)
            VALUES ('$producto_id', $cantidad, $precio_ordenar, '$precio_mantener', $precio_compra, $precio_faltantes)";

    // Ejecutar la consulta SQL
    if ($conexion->query($sql) === TRUE) {
        echo "Producto agregado exitosamente.";
    } else {
        echo "Error al agregar producto: " . $conexion->error;
    }

    $conexion->close();
}

function obtenerUltimoIdProducto() {
    $conexion = conectarse();
    $sql = "SELECT MAX(id) as ultimo_id FROM producto";
    $result = $conexion->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $ultimo_id = $row["ultimo_id"];
    } else {
        $ultimo_id = null;
    }
    $conexion->close();
    return $ultimo_id;
}

function obtenerProductos() {
    $conexion = conectarse();
    $sql = "SELECT p.id,p.nombre,p.cantidad,p.precio, p.imagen, c.nombre as c_nombre, m.nombre as m_nombre,
    l.precio_ordenar, l.precio_mantener, l.precio_compra, l.precio_faltantes
    FROM producto p, categoria c, marca m, lote l
    WHERE p.categoria_id = c.id
    and p.marca_id = m.id
    and p.id = l.producto_id";
    $resultado = $conexion->query($sql);
    $productos = [];

    if ($resultado->num_rows > 0) {
        while($fila = $resultado->fetch_assoc()) {
            $productos[] = $fila;
        }
    }

    $conexion->close();
    return $productos;
}


function eliminarProducto($id) {
    $conexion = conectarse();

    $sql = "DELETE FROM producto WHERE id = $id";
    
    if ($conexion->query($sql) === TRUE) {
        echo "Producto eliminado exitosamente.";
    } else {
        echo "Error al eliminar producto: " . $conexion->error;
    }
    

    $conexion->close();
}


function editarProducto($id, $nombre, $cantidad, $precio, $imagen_temporal, $categoria_id, $marca_id){
    $conexion = conectarse();

    $nombre = $conexion->real_escape_string($nombre);
    $cantidad = intval($cantidad);
    $precio = floatval($precio);

    if (!empty($imagen_temporal)) {
        $imagen_contenido = file_get_contents($imagen_temporal);
        $imagen_contenido = $conexion->real_escape_string($imagen_contenido);

        $sql = "UPDATE producto SET nombre='$nombre', cantidad=$cantidad, precio=$precio, imagen='$imagen_contenido', categoria_id=$categoria_id, marca_id=$marca_id WHERE id=$id";
    } else {
        $sql = "UPDATE producto SET nombre='$nombre', cantidad=$cantidad, precio=$precio, categoria_id=$categoria_id, marca_id=$marca_id WHERE id=$id";
    }

    if ($conexion->query($sql) === TRUE) {
        //echo "Editado correcatamente.";
    } else {
        echo "Error al agregar lote: " . $conexion->error;
    }

    $conexion->close();
}

function editarLote($id, $cantidad, $precio_ordenar, $precio_mantener, $precio_compra, $precio_faltantes) {
    $conexion = conectarse();

    $id = intval($id);
    $cantidad = intval($cantidad);
    $precio_ordenar = floatval($precio_ordenar);
    $precio_mantener = floatval($precio_mantener);
    $precio_compra = floatval($precio_compra);
    $precio_faltantes = floatval($precio_faltantes);

    $sql = "UPDATE lote SET 
                cantidad = $cantidad, 
                precio_ordenar = $precio_ordenar, 
                precio_mantener = $precio_mantener, 
                precio_compra = $precio_compra, 
                precio_faltantes = $precio_faltantes 
            WHERE producto_id = $id";

    if ($conexion->query($sql) === TRUE) {
        echo "Lote actualizado exitosamente.";
    } else {
        echo "Error al actualizar lote: " . $conexion->error;
    }

    $conexion->close();
}

function obtenerProductoPorId($id) {
    $conexion = conectarse();

    $sql = "SELECT * FROM producto,lote 
    WHERE producto.id = $id AND lote.producto_id = producto.id";

    $resultado = $conexion->query($sql);
    if ($resultado->num_rows > 0) {
        $producto = $resultado->fetch_assoc();
    } else {
        $producto = false;
    }
    $conexion->close();

    return $producto;
}
?>
