
<?php
$dir = dirname(__FILE__);
require_once $dir . DIRECTORY_SEPARATOR . "accion_conexion.php";


function obtenerVentasPorProductoCategoriaHoy($categoria_id) {
    // Conectar a la base de datos
    $conexion = conectarse();
    
    // Consulta SQL ajustada
    $consulta = "SELECT COALESCE(ventas.cantidad_vendida, 0) AS cantidad_vendida
				FROM producto p
				LEFT JOIN (
				    SELECT dv.producto_id, SUM(dv.cantidad) AS cantidad_vendida
				    FROM detalle_venta dv
				    JOIN venta v ON dv.venta_ticket_id = v.ticket_id
				    WHERE DATE(v.fecha_fin) = CURRENT_DATE
				    GROUP BY dv.producto_id
				) ventas ON p.id = ventas.producto_id
				WHERE p.categoria_id = ?;
				";

    // Preparar la consulta
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("i", $categoria_id);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $resultado = $stmt->get_result();

    // Verificar si hay resultados
    $data = [];
    if ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $data[] = $fila['cantidad_vendida'];
        }
    } else {
        echo "No se encontraron resultados.";
    }

    // Cerrar la conexión
    $stmt->close();
    $conexion->close();

    // Devolver los datos
    return $data;
}

function obtenerVentasPorProductoCategoriaAyer($categoria_id) {
   
	$conexion = conectarse();
    
    
				$consulta = "SELECT COALESCE(ventas.cantidad_vendida, 0) AS cantidad_vendida
				FROM producto p
				LEFT JOIN (
				    SELECT dv.producto_id, SUM(dv.cantidad) AS cantidad_vendida
				    FROM detalle_venta dv
				    JOIN venta v ON dv.venta_ticket_id = v.ticket_id
				    WHERE DATE(v.fecha_fin) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)
				    GROUP BY dv.producto_id
				) ventas ON p.id = ventas.producto_id
				WHERE p.categoria_id = ?;
				";

    // Preparar la consulta
   // Preparar la consulta
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("i", $categoria_id);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $resultado = $stmt->get_result();

    // Verificar si hay resultados
    $data = [];
    if ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $data[] = $fila['cantidad_vendida'];
        }
    } else {
        echo "No se encontraron resultados.";
    }

    // Cerrar la conexión
    $stmt->close();
    $conexion->close();

    // Devolver los datos
    return $data;
}

function obtenerVentasPorProductoCategoriaAntesDeAyer($categoria_id) {

    // Conectarse a la base de datos
    $conexion = conectarse();
    


				$consulta = "SELECT COALESCE(ventas.cantidad_vendida, 0) AS cantidad_vendida
				FROM producto p
				LEFT JOIN (
				    SELECT dv.producto_id, SUM(dv.cantidad) AS cantidad_vendida
				    FROM detalle_venta dv
				    JOIN venta v ON dv.venta_ticket_id = v.ticket_id
				    WHERE DATE(v.fecha_fin) = DATE_SUB(CURDATE(), INTERVAL 2 DAY)
				    GROUP BY dv.producto_id
				) ventas ON p.id = ventas.producto_id
				WHERE p.categoria_id = ?;
				";
    
    // Ejecutar la consulta
   // Preparar la consulta
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("i", $categoria_id);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $resultado = $stmt->get_result();

    // Verificar si hay resultados
    $data = [];
    if ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $data[] = $fila['cantidad_vendida'];
        }
    } else {
        echo "No se encontraron resultados.";
    }

    // Cerrar la conexión
    $stmt->close();
    $conexion->close();

    // Devolver los datos
    return $data;
}






?>
