<?php
$dir = dirname(__FILE__);
require_once $dir . DIRECTORY_SEPARATOR . "accion_conexion.php";

function obtenerLamda() {
    $conexion = conectarse();
    $sql_landa = "SELECT 
        AVG(diferencia) AS promedio_diferencia
    FROM (
        SELECT 
            TIMESTAMPDIFF(SECOND, lag_fecha_emision, fecha_emision) AS diferencia
        FROM (
            SELECT 
                fecha_emision,
                LAG(fecha_emision) OVER (ORDER BY fecha_emision) AS lag_fecha_emision
            FROM 
                ticket
            WHERE
                ticket.atendido = 1
        ) AS lagged
        WHERE DATE(fecha_emision) = DATE(lag_fecha_emision)
    ) AS landa";

    $resultado_landa = mysqli_query($conexion, $sql_landa);
    $row_landa = mysqli_fetch_assoc($resultado_landa);
    return $row_landa['promedio_diferencia'];
}

function obtenerLamdaPersonal($personal_ci) {
    $conexion = conectarse();
    $sql_landa = "SELECT 
        AVG(diferencia) AS promedio_diferencia
    FROM (
        SELECT 
            TIMESTAMPDIFF(SECOND, lag_fecha_emision, fecha_emision) AS diferencia
        FROM (
            SELECT 
                fecha_emision,
                LAG(fecha_emision) OVER (ORDER BY fecha_emision) AS lag_fecha_emision
            FROM 
                ticket, venta
            WHERE
                ticket.atendido = 1 and venta.ticket_id = ticket.id and venta.personal_ci = $personal_ci
        ) AS lagged
        WHERE DATE(fecha_emision) = DATE(lag_fecha_emision)
    ) AS landa";

    $resultado_landa = mysqli_query($conexion, $sql_landa);
    $row_landa = mysqli_fetch_assoc($resultado_landa);
    return $row_landa['promedio_diferencia'];
}

function obtenerLamdaSucursal($sucursal_id) {
    $conexion = conectarse();
    $sql_landa = "SELECT 
        AVG(diferencia) AS promedio_diferencia
    FROM (
        SELECT 
            TIMESTAMPDIFF(SECOND, lag_fecha_emision, fecha_emision) AS diferencia
        FROM (
            SELECT 
                fecha_emision,
                LAG(fecha_emision) OVER (ORDER BY fecha_emision) AS lag_fecha_emision
            FROM 
                ticket, venta
            WHERE
                ticket.atendido = 1 and venta.ticket_id = ticket.id and venta.sucursal_id = $sucursal_id
        ) AS lagged
        WHERE DATE(fecha_emision) = DATE(lag_fecha_emision)
    ) AS landa";

    $resultado_landa = mysqli_query($conexion, $sql_landa);
    $row_landa = mysqli_fetch_assoc($resultado_landa);
    return $row_landa['promedio_diferencia'];
}

function obtenerLamdaPersonalSucursal($personal_ci,$sucursal_id) {
    $conexion = conectarse();
    $sql_landa = "SELECT 
        AVG(diferencia) AS promedio_diferencia
    FROM (
        SELECT 
            TIMESTAMPDIFF(SECOND, lag_fecha_emision, fecha_emision) AS diferencia
        FROM (
            SELECT 
                fecha_emision,
                LAG(fecha_emision) OVER (ORDER BY fecha_emision) AS lag_fecha_emision
            FROM 
                ticket, venta
            WHERE
                ticket.atendido = 1 and venta.ticket_id = ticket.id and venta.sucursal_id = $sucursal_id and venta.personal_ci = $personal_ci
        ) AS lagged
        WHERE DATE(fecha_emision) = DATE(lag_fecha_emision)
    ) AS landa";

    $resultado_landa = mysqli_query($conexion, $sql_landa);
    $row_landa = mysqli_fetch_assoc($resultado_landa);
    return $row_landa['promedio_diferencia'];
}

function obtenerMu() {
    $conexion = conectarse();
    $sql_mu = "SELECT AVG(TIMESTAMPDIFF(SECOND, fecha_inicio, fecha_fin)) AS mu FROM venta WHERE venta.total > 0";
    $resultado_mu = mysqli_query($conexion, $sql_mu);
    $row_mu = mysqli_fetch_assoc($resultado_mu);
    return $row_mu['mu'];
}

function obtenerMuPersonal($personal_ci) {
    $conexion = conectarse();
    $sql_mu = "SELECT AVG(TIMESTAMPDIFF(SECOND, fecha_inicio, fecha_fin)) AS mu FROM venta WHERE venta.total > 0 and personal_ci = $personal_ci";
    $resultado_mu = mysqli_query($conexion, $sql_mu);
    $row_mu = mysqli_fetch_assoc($resultado_mu);
    return $row_mu['mu'];
}

function obtenerMuSucursal($sucursal_id) {
    $conexion = conectarse();
    $sql_mu = "SELECT AVG(TIMESTAMPDIFF(SECOND, fecha_inicio, fecha_fin)) AS mu FROM venta WHERE venta.total > 0 and sucursal_id = $sucursal_id";
    $resultado_mu = mysqli_query($conexion, $sql_mu);
    $row_mu = mysqli_fetch_assoc($resultado_mu);
    return $row_mu['mu'];
}

function obtenerMuPersonalSucursal($personal_ci,$sucursal_id) {
    $conexion = conectarse();
    $sql_mu = "SELECT AVG(TIMESTAMPDIFF(SECOND, fecha_inicio, fecha_fin)) AS mu FROM venta WHERE venta.total > 0 and personal_ci = $personal_ci and sucursal_id = $sucursal_id";
    $resultado_mu = mysqli_query($conexion, $sql_mu);
    $row_mu = mysqli_fetch_assoc($resultado_mu);
    return $row_mu['mu'];
}

// Función para calcular el tiempo de espera en la cola (Wq)
function calcularWq($x, $u) {
    if ($u == $x) {
        return "Error: u no puede ser igual a x en esta ecuación.";
    }
    return $x / ($u * ($u - $x));
}

// Función para calcular el número promedio de clientes en la cola (Lq)
function calcularLq($x, $u) {
    if ($u == $x) {
        return "Error: u no puede ser igual a x en esta ecuación.";
    }
    return pow($x, 2) / ($u * ($u - $x));
}

// Función para calcular el número promedio de clientes en el sistema (Ls)
function calcularLs($x, $u) {
    if ($u == $x) {
        return "Error: u no puede ser igual a x en esta ecuación.";
    }
    return $x / ($u - $x);
}

// Función para calcular el tiempo de espera en el sistema (Ws)
function calcularWs($x, $u) {
    if ($u == $x) {
        return "Error: u no puede ser igual a x en esta ecuación.";
    }
    return 1 / ($u - $x);
}

function calcularRo($x, $u) {
    if ($u == $x) {
        return "Error: u no puede ser igual a x en esta ecuación.";
    }
    return $x/$u;
}

function calcularProbabilidadN($ro, $n) {
    if ($ro == 1) {
        return "Error: ro no puede ser igual a 1 en esta ecuación.";
    }
    return (1 - $ro) * pow($ro, $n);
}


function calcularWSt($u, $ro ,$t) {
    return $ro * pow(M_E, (-$u * (1 - $ro) * $t));
}

function calcularWqt($u, $ro ,$t) {
    return  pow(M_E, (-$u * (1 - $ro) * $t));
}


?>
