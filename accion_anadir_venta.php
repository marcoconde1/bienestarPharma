<?php
date_default_timezone_set("America/La_Paz");
// Verificar si se recibió una solicitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se recibió la lista de productos
    if (isset($_POST["listaProductos"])) {
        // Decodificar la lista de productos desde JSON a un array de PHP
        $listaProductos = json_decode($_POST["listaProductos"], true);

        // Conectar a la base de datos
        $dir = dirname(__FILE__);
        require_once $dir . "/accion_conexion.php";
        $conexion = conectarse();

        // Insertar la venta y los productos vendidos en la base de datos
        $ticket = $_POST["ticket"];
        $fecha_fin = date("Y-m-d H:i:s"); // Obtener la fecha y hora actual
        $total = 0; // Inicializar el total de la venta

        // Calcular el total de la venta y preparar la consulta para insertar la venta
        foreach ($listaProductos as $producto) {
            $subtotal = $producto["cantidad"] * $producto["precio"]; // Calcular subtotal
            $total += $subtotal; // Agregar al total de la venta
        }

        $cliente_ci = $_POST["cliente_ci"];
        $personal_ci = $_POST["personal_ci"];
        $sucursal_id = $_POST["sucursal_id"];

        // Insertar la venta en la tabla ventas
        $sql_update_venta = "UPDATE venta SET fecha_fin = '$fecha_fin', total = $total, cliente_ci = '$cliente_ci', personal_ci = '$personal_ci', sucursal_id = '$sucursal_id' WHERE ticket_id = '$ticket'";

        

        if ($conexion->query($sql_update_venta) === TRUE) {
            $id_venta = $conexion->insert_id; // Obtener el ID de la venta insertada
            // Recorrer la lista de productos y agregar cada producto vendido a la tabla detalle_venta
            foreach ($listaProductos as $producto) {
                $cantidad = $producto["cantidad"];
                $subtotal = $producto["cantidad"] * $producto["precio"]; // Calcular subtotal
                $producto_id = $producto["idProducto"];

                // Insertar el detalle de venta en la tabla detalle_venta
                $sql_insert_detalle_venta = "INSERT INTO detalle_venta (cantidad, subtotal, producto_id, venta_ticket_id) VALUES ($cantidad, $subtotal, $producto_id, '$ticket')";
                $conexion->query($sql_insert_detalle_venta);
            }
            $sql_update_ticket = "UPDATE ticket SET atendido = 1 WHERE id = '$ticket'";
            $conexion->query($sql_update_ticket);
            echo "¡La venta se ha registrado exitosamente!";
            header("Location: venta.php");
            exit();


        } else {
            echo "Error al registrar la venta: " . $conexion->error;
        }

        // Cerrar la conexión a la base de datos
        $conexion->close();
    } else {
        echo "Error: No se recibió la lista de productos.";
    }
} else {
    echo "Error: Esta página solo puede ser accedida mediante una solicitud POST.";
}
?>
