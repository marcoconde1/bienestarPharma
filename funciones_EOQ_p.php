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
        lote.precio_compra,
        lote.precio_faltantes
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
    // Asignar los valores de la fila a variables
    $D = $row['demanda_diaria'];
    $K = $row['precio_ordenar'];
    $H = $row['precio_mantener'];
    $P = $row['precio_faltantes'];
    $c = $row['precio_compra'];

    // Asegurarse de no dividir por cero
    if ($H != 0 && $D != 0) {
    // Calcular EOQ con faltantes planeados
    $Q = sqrt((2 * $D * $K) / $H) * sqrt(($P + $H) / $P);
    $S = sqrt((2 * $D * $K) / $H) * sqrt($P / ($P + $H));
    $QS = $Q - $S;
    $T = $Q / $D;
    $N = 1 / $T;
    $CTC = $K + $c * $Q + (($H * $S * $S) / (2 * $D)) + ($P * $QS * $QS) / (2 * $D);
    $CT = ($D * $K / $Q) + ($D * $c) + ($H * $S * $S / (2 * $Q)) + ($P * $QS * $QS / (2 * $Q));
    
    // Redondear los valores
    $Q = round($Q, 2);
    $S = round($S, 2);
    $QS = round($QS, 2);
    $T = round($T, 2);
    $N = round($N, 2);
    $CTC = round($CTC, 2);
    $CT = round($CT, 2);
} else {
    // Si se divide por cero, asignar "N/A" o cualquier otro valor por defecto
    $Q = $T = $N = $CTC = $CT = "N/A";
}//Aquí puedes usar los valores calculados según sea necesario

        $row['cantidad_optima_pedido'] = $Q;
        $row['nivel_inventario_optimo'] = $S;
        $row['cantidad_faltantes_maximos'] = $QS;
        $row['tiempo_ciclo_produccion'] = $T;
        $row['numero_ciclos_produccion'] = $N;
        //$row['costo_producir_lote'] = $Cp;
        //$row['costo_mantener_lote_inventario'] = $Cmi;
        $row['costo_total_ciclo'] = $CTC;
        $row['costo_total'] = $CT;

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
        lote.precio_compra,
        lote.precio_faltantes
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
    // Asignar los valores de la fila a variables
    $D = $row['demanda_mensual'];
    $K = $row['precio_ordenar'];
    $H = $row['precio_mantener'];
    $P = $row['precio_faltantes'];
    $c = $row['precio_compra'];

    $H = $H/30;

    // Asegurarse de no dividir por cero
   if ($H != 0 && $D != 0) {
    // Calcular EOQ con faltantes planeados
    $Q = sqrt((2 * $D * $K) / $H) * sqrt(($P + $H) / $P);
    $S = sqrt((2 * $D * $K) / $H) * sqrt($P / ($P + $H));
    $QS = $Q - $S;
    $T = $Q / $D;
    $N = 1 / $T;
    $CTC = $K + $c * $Q + (($H * $S * $S) / (2 * $D)) + ($P * $QS * $QS) / (2 * $D);
    $CT = ($D * $K / $Q) + ($D * $c) + ($H * $S * $S / (2 * $Q)) + ($P * $QS * $QS / (2 * $Q));
    
    // Redondear los valores
    $Q = round($Q, 2);
    $S = round($S, 2);
    $QS = round($QS, 2);
    $T = round($T, 2);
    $N = round($N, 2);
    $CTC = round($CTC, 2);
    $CT = round($CT, 2);
} else {
    // Si se divide por cero, asignar "N/A" o cualquier otro valor por defecto
    $Q = $T = $N = $CTC = $CT = "N/A";
}
    // Aquí puedes usar los valores calculados según sea necesario

        $row['cantidad_optima_pedido'] = $Q;
        $row['nivel_inventario_optimo'] = $S;
        $row['cantidad_faltantes_maximos'] = $QS;
        $row['tiempo_ciclo_produccion'] = $T;
        $row['numero_ciclos_produccion'] = $N;
        //$row['costo_producir_lote'] = $Cp;
        //$row['costo_mantener_lote_inventario'] = $Cmi;
        $row['costo_total_ciclo'] = $CT;
        $row['costo_total'] = $CT;

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
    lote.precio_compra,
    lote.precio_faltantes
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
    // Asignar los valores de la fila a variables
    $D = $row['demanda_anual'];
    $K = $row['precio_ordenar'];
    $H = $row['precio_mantener'];
    $P = $row['precio_faltantes'];
    $c = $row['precio_compra'];

    $H = $H/365;

    // Asegurarse de no dividir por cero
    if ($H != 0 && $D != 0) {
    // Calcular EOQ con faltantes planeados
    $Q = sqrt((2 * $D * $K) / $H) * sqrt(($P + $H) / $P);
    $S = sqrt((2 * $D * $K) / $H) * sqrt($P / ($P + $H));
    $QS = $Q - $S;
    $T = $Q / $D;
    $N = 1 / $T;
    $CTC = $K + $c * $Q + (($H * $S * $S) / (2 * $D)) + ($P * $QS * $QS) / (2 * $D);
    $CT = ($D * $K / $Q) + ($D * $c) + ($H * $S * $S / (2 * $Q)) + ($P * $QS * $QS / (2 * $Q));
    
    // Redondear los valores
    $Q = round($Q, 2);
    $S = round($S, 2);
    $QS = round($QS, 2);
    $T = round($T, 2);
    $N = round($N, 2);
    $CTC = round($CTC, 2);
    $CT = round($CT, 2);
} else {
    // Si se divide por cero, asignar "N/A" o cualquier otro valor por defecto
    $Q = $T = $N = $CTC = $CT = "N/A";
}
    // Aquí puedes usar los valores calculados según sea necesario

        $row['cantidad_optima_pedido'] = $Q;
       $row['nivel_inventario_optimo'] = $S;
        $row['cantidad_faltantes_maximos'] = $QS;
        $row['tiempo_ciclo_produccion'] = $T;
        $row['numero_ciclos_produccion'] = $N;
        //$row['costo_producir_lote'] = $Cp;
        //$row['costo_mantener_lote_inventario'] = $Cmi;
        $row['costo_total_ciclo'] = $CTC;
        $row['costo_total'] = $CT;

        $data[] = $row;
    }
     mysqli_free_result($resultado);
    mysqli_close($conexion);

    return $data;
}


?>
