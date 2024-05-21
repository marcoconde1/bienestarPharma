<?php
$dir = dirname(__FILE__);
require_once $dir . DIRECTORY_SEPARATOR . "accion_conexion.php";

function obtenerEoqDiario() {
    $conexion = conectarse();
    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    $sql = "SELECT 
        sd.producto_id,
        producto.nombre,
        AVG(sd.suma_diaria) AS demanda_diaria,
        lote.precio_ordenar,
        lote.precio_mantener,
        lote.precio_compra
    FROM (
        SELECT 
            dv.producto_id, 
            DATE(v.fecha_fin) AS fecha,
            SUM(dv.cantidad) AS suma_diaria
        FROM 
            detalle_venta dv,
            venta v
        WHERE 
            dv.venta_ticket_id = v.ticket_id
        GROUP BY 
            dv.producto_id, 
            DATE(v.fecha_fin)
    ) AS sd, 
    lote, 
    producto
    WHERE 
        sd.producto_id = lote.producto_id
        AND lote.producto_id = producto.id
    GROUP BY 
        sd.producto_id
    ORDER BY 
        sd.producto_id;";

    $resultado = mysqli_query($conexion, $sql);
    if (!$resultado) {
        die("Error en la consulta: " . mysqli_error($conexion));
    }

    $data = array();
    while ($row = mysqli_fetch_assoc($resultado)) {
        $d = $row['demanda_diaria'];
        $k = $row['precio_ordenar'];
        $c = $row['precio_compra'];
        $h = $row['precio_mantener'];

        // Ensure no division by zero
          if ($h != 0 && $d != 0) {
        $Q = round(sqrt((2 * $d * $k) / $h), 3);
        $T = round($Q / $d, 3);
        $N = round(1 / $T, 3);
        $Cp = round($k + $c * $Q, 3);
        $Cmi = round(($h * pow($Q, 2)) / (2 * $d), 3);
        $Ctc = round($Cp + $Cmi, 3);
        $Ctd = round(($d * $k) / $Q + $c * $d + ($h * $Q) / 2, 3);
    } else {
        $Q = $T = $N = $Cp = $Cmi = $Ctc = $Ctd = "N/A";
    }

        $row['cantidad_optima_pedido'] = $Q;
        $row['tiempo_ciclo_produccion'] = $T;
        $row['numero_ciclos_produccion'] = $N;
        $row['costo_producir_lote'] = $Cp;
        $row['costo_mantener_lote_inventario'] = $Cmi;
        $row['costo_total_ciclo'] = $Ctc;
        $row['costo_total_diario'] = $Ctd;

        $data[] = $row;
    }

    mysqli_free_result($resultado);
    mysqli_close($conexion);

    return $data;
}

function obtenerEoqMensual() {
    $conexion = conectarse();
    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    $sql = "SELECT 
        sd.producto_id,
        producto.nombre,
        AVG(sd.suma_diaria) AS demanda_mensual,
        lote.precio_ordenar,
        lote.precio_mantener,
        lote.precio_compra
    FROM (
        SELECT 
            dv.producto_id, 
            YEAR(v.fecha_fin) AS anio,
            MONTH(v.fecha_fin) AS mes,
            SUM(dv.cantidad) AS suma_diaria
        FROM 
            detalle_venta dv,
            venta v
        WHERE 
            dv.venta_ticket_id = v.ticket_id
        GROUP BY 
            dv.producto_id, 
            YEAR(v.fecha_fin),
            MONTH(v.fecha_fin)
    ) AS sd,
    producto,
    lote
    WHERE 
        sd.producto_id = lote.producto_id
        AND lote.producto_id = producto.id
    GROUP BY 
        sd.producto_id,
        sd.anio,
        sd.mes
    ORDER BY 
        sd.producto_id, sd.anio, sd.mes;";

    $resultado = mysqli_query($conexion, $sql);
    if (!$resultado) {
        die("Error en la consulta: " . mysqli_error($conexion));
    }

    $data = array();
    while ($row = mysqli_fetch_assoc($resultado)) {
        $d = $row['demanda_mensual'];
        $k = $row['precio_ordenar'];
        $c = $row['precio_compra'];
        $h = $row['precio_mantener'];

        $h = $h / 30;

        // Ensure no division by zero
        if ($h != 0 && $d != 0) {
            $Q = round(sqrt((2 * $d * $k) / $h), 3);
            $T = round($Q / $d, 3);
            $N = round(1 / $T, 3);
            $Cp = round($k + $c * $Q, 3);
            $Cmi = round(($h * pow($Q, 2)) / (2 * $d), 3);
            $Ctc = round($Cp + $Cmi, 3);
            $CTm = round(($d * $k) / $Q + $c * $d + ($h * $Q) / 2, 3);
        } else {
            $Q = $T = $N = $Cp = $Cmi = $Ctc = $CTm = "N/A";
        }

        $row['cantidad_optima_pedido'] = $Q;
        $row['tiempo_ciclo_produccion'] = $T;
        $row['numero_ciclos_produccion'] = $N;
        $row['costo_producir_lote'] = $Cp;
        $row['costo_mantener_lote_inventario'] = $Cmi;
        $row['costo_total_ciclo'] = $Ctc;
        $row['costo_total_mensual'] = $CTm;

        $data[] = $row;
    }

    mysqli_free_result($resultado);
    mysqli_close($conexion);

    return $data;
}


function obtenerEoqAnual() {
    $conexion = conectarse();
    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    $sql = "SELECT 
    sd.producto_id,
    producto.nombre,
    AVG(sd.suma_anual) AS demanda_anual,
    lote.precio_ordenar,
    lote.precio_mantener,
    lote.precio_compra
FROM (
    SELECT 
        dv.producto_id, 
        YEAR(v.fecha_fin) AS anio,
        SUM(dv.cantidad) AS suma_anual
    FROM 
        detalle_venta dv,
        venta v
    WHERE 
        dv.venta_ticket_id = v.ticket_id
    GROUP BY 
        dv.producto_id, 
        YEAR(v.fecha_fin)
) AS sd, 
lote, 
producto
WHERE 
    sd.producto_id = lote.producto_id
    AND lote.producto_id = producto.id
GROUP BY 
    sd.producto_id
ORDER BY 
    sd.producto_id;
";

    $resultado = mysqli_query($conexion, $sql);
    if (!$resultado) {
        die("Error en la consulta: " . mysqli_error($conexion));
    }

    $data = array();
    while ($row = mysqli_fetch_assoc($resultado)) {
        $d = $row['demanda_anual'];
        $k = $row['precio_ordenar'];
        $c = $row['precio_compra'];
        $h = $row['precio_mantener'];

        $h = $h / 365;

        // Ensure no division by zero
        if ($h != 0 && $d != 0) {
            $Q = round(sqrt((2 * $d * $k) / $h), 3);
            $T = round($Q / $d, 3);
            $N = round(1 / $T, 3);
            $Cp = round($k + $c * $Q, 3);
            $Cmi = round(($h * pow($Q, 2)) / (2 * $d), 3);
            $Ctc = round($Cp + $Cmi, 3);
            $CTa = round(($d * $k) / $Q + $c * $d + ($h * $Q) / 2, 3);
        } else {
            $Q = $T = $N = $Cp = $Cmi = $Ctc = $CTa = "N/A";
        }

        $row['cantidad_optima_pedido'] = $Q;
        $row['tiempo_ciclo_produccion'] = $T;
        $row['numero_ciclos_produccion'] = $N;
        $row['costo_producir_lote'] = $Cp;
        $row['costo_mantener_lote_inventario'] = $Cmi;
        $row['costo_total_ciclo'] = $Ctc;
        $row['costo_total_anual'] = $CTa;

        $data[] = $row;
    }

    mysqli_free_result($resultado);
    mysqli_close($conexion);

    return $data;
}


?>
